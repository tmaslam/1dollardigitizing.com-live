<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\PaymentTransaction;
use App\Support\AdminReferenceData;
use App\Support\CustomerPricing;
use App\Support\CustomerRememberLogin;
use App\Support\CustomerPublicRateLimit;
use App\Support\EmailValidation;
use App\Support\PasswordManager;
use App\Support\PortalMailer;
use App\Support\SecurityAudit;
use App\Support\SiteContext;
use App\Support\SignupOfferService;
use App\Support\SystemEmailTemplates;
use App\Support\TurnstileVerifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerRegistrationController extends Controller
{
    private const ACTIVATION_TABLE = 'customer_activation_tokens';

    public function show(Request $request)
    {
        $this->clearCustomerSignupSession($request);

        return view('customer.auth.register', [
            'pageTitle'    => 'Member Sign Up',
            'countries'    => AdminReferenceData::countriesForCustomerForms(),
            'preferredCountries' => AdminReferenceData::preferredCustomerCountries(),
            'companyTypes' => AdminReferenceData::companyTypes(),
            'signupOffer'  => SignupOfferService::offerSummary(SignupOfferService::activeSignupOffer($this->site($request))),
            'signupPlans'  => self::signupPlans(),
        ]);
    }

    public function register(Request $request)
    {
        $this->clearCustomerSignupSession($request);

        $site = $this->site($request);

        if (CustomerPublicRateLimit::tooManyAttempts($request, 'signup', $site->legacyKey, 'registration', 5, 1800)) {
            return back()->withErrors(['signup' => 'Too many signup attempts from this connection. Please try again later.'])->withInput();
        }

        $allPlanIds = collect(self::signupPlans())->flatten(1)->pluck('id')->all();

        $validated = $request->validate([
            'first_name'       => ['required', 'string', 'max:100'],
            'last_name'        => ['required', 'string', 'max:100'],
            'selCountry'       => ['required', 'string', 'max:150', Rule::in(AdminReferenceData::countries())],
            'telephone_num'    => ['required', 'string', 'max:50'],
            'package_type'     => ['required', 'string', 'in:BASIC,BUSINESS,CORPORATE'],
            'useremail'        => ['required', EmailValidation::rule(), 'max:190'],
            'confirmuseremail' => ['required', 'same:useremail'],
            'user_psw'         => ['required', 'string', 'min:6', 'max:100'],
            'confirm_psw'      => ['required', 'same:user_psw'],
            'selCompanyTypes'  => ['nullable', 'string', 'max:100'],
            'company_name'     => ['nullable', 'string', 'max:150'],
            'company_address'  => ['nullable', 'string', 'max:500'],

            'terms'            => ['accepted'],
            'signup_plan_type' => ['nullable', 'string', 'in:none,credit,subscription'],
            'signup_plan_id'   => ['nullable', 'string', Rule::in(array_merge(['', 'none'], $allPlanIds))],
        ], [
            'confirmuseremail.same' => 'The confirm email address must match the email address.',
            'confirm_psw.same'      => 'The confirm password must match the password.',
        ]);

        if (! TurnstileVerifier::verify($request, 'customer-signup')) {
            return back()->withErrors(['signup' => 'Please complete the security verification and try again.'])->withInput();
        }

        if (strcasecmp(trim((string) $validated['selCountry']), 'Pakistan') === 0) {
            return back()->withErrors(['country' => 'We cannot accept registration from the selected country on this website.'])->withInput();
        }

        $email = strtolower(trim((string) $validated['useremail']));
        $username = $this->deriveUsername($email, $site);
        $ipAddress = (string) ($request->ip() ?? '127.0.0.1');

        $existingAccount = AdminUser::query()
            ->customers()
            ->active()
            ->forWebsite($site->legacyKey)
            ->where(function ($query) use ($email, $username) {
                $query->where('user_email', $email)
                    ->orWhere('user_name', $username);
            })
            ->exists();

        if ($existingAccount) {
            return back()->withErrors(['signup' => 'You are already registered on this website. Please log in or contact support if you need help.'])->withInput();
        }

        $existingIp = AdminUser::query()
            ->customers()
            ->active()
            ->forWebsite($site->legacyKey)
            ->where('userip_addrs', $ipAddress)
            ->exists();

        if ($existingIp) {
            SecurityAudit::record($request, 'auth.signup_blocked', 'Signup blocked because an active account already exists from the same IP for this site.', [
                'site_legacy_key' => $site->legacyKey,
                'signup_email' => $email,
                'signup_username' => $username,
            ], 'notice');

            return back()->withErrors(['signup' => 'We are unable to process your registration at this time. If you need assistance, please contact our support team.'])->withInput();
        }

        $referralSource = trim((string) ($validated['refralcode'] ?? ''));
        if ($referralSource === '') {
            $referralSource = trim((string) ($validated['refraloptions'] ?? ''));
        }
        $now = now()->format('Y-m-d H:i:s');
        $refCode = strtolower($site->legacyKey).random_int(10000, 99999).str_replace('.', '', $ipAddress);

        $selectedPlanType = $validated['signup_plan_type'] ?? 'none';

        $customer = AdminUser::query()->create(array_merge([
            'site_id' => $site->id,
            'website' => $site->legacyKey,
            'usre_type_id' => AdminUser::TYPE_CUSTOMER,
            'user_name' => $username,
            'first_name' => trim((string) $validated['first_name']),
            'last_name' => trim((string) $validated['last_name']),
            'company' => trim((string) ($validated['company_name'] ?? '')),
            'company_type' => trim((string) ($validated['selCompanyTypes'] ?? '')),
            'user_email' => $email,
            'company_address' => trim((string) ($validated['company_address'] ?? '')),
            'zip_code' => '',
            'user_city' => '',
            'user_country' => trim((string) $validated['selCountry']),
            'user_phone' => trim((string) $validated['telephone_num']),
            'is_active' => 0,
            'payment_terms' => 5,
            'date_added' => $now,
            'customer_pending_order_limit' => 3,
            'userip_addrs' => $ipAddress,
            'user_term' => 'dc',
            'package_type' => (string) $validated['package_type'],
            'real_user' => '1',
            'ref_code' => $refCode,
            'ref_code_other' => $referralSource,
            'exist_customer' => '0',
        ], CustomerPricing::sitePricingPayload($site), $this->legacyRegistrationDefaults($site)));

        $customer->forceFill(PasswordManager::payload((string) $validated['user_psw']))->save();

        $this->storeSignupPlanInSession($request, $validated);

        $plan = $request->session()->get('signup_selected_plan');
        $hasPlan = $plan !== null;

        // If user selected a plan during signup, skip email verification and go directly to payment.
        if ($hasPlan) {
            // Auto-login the customer so they can access the payment flow and return page.
            $request->session()->put([
                'customer_user_id'   => (int) $customer->user_id,
                'customer_user_name' => (string) $customer->display_name,
                'customer_site_key'  => $site->legacyKey,
            ]);

            $planType = $plan['type'] ?? '';
            $paymentLink = $plan['plan']['payment_link'] ?? '';

            // Credit packs and subscriptions with a payment link: redirect directly to Stripe.
            if (($planType === 'credit' || $planType === 'subscription') && $paymentLink !== '') {
                $price = round((float) $plan['plan']['price'], 2);
                $merchantReference = strtoupper(implode('-', [
                    'CREDIT',
                    $site->slug ?: $site->legacyKey,
                    $customer->user_id,
                    now()->format('YmdHis'),
                    Str::upper(Str::random(6)),
                ]));

                $payload = [
                    'plan_id'      => $plan['plan']['id'] ?? '',
                    'plan_label'   => $plan['plan']['label'] ?? '',
                    'payment_link' => $paymentLink,
                    'is_signup'    => true,
                ];

                if ($planType === 'credit') {
                    $payload['plan_full_price'] = (float) ($plan['plan']['full_price'] ?? 0);
                }

                if ($planType === 'subscription') {
                    $payload['plan_credits'] = (int) ($plan['plan']['credits'] ?? 0);
                }

                PaymentTransaction::query()->create([
                    'site_id'            => $site->id,
                    'user_id'            => $customer->user_id,
                    'order_id'           => null,
                    'billing_id'         => null,
                    'legacy_website'     => $site->legacyKey,
                    'provider'           => 'stripe_payment_link',
                    'merchant_reference' => $merchantReference,
                    'payment_scope'      => 'credit_purchase',
                    'status'             => 'initiated',
                    'currency'           => 'USD',
                    'requested_amount'   => number_format($price, 2, '.', ''),
                    'return_url'         => url('/stripe-return.php'),
                    'provider_payload'   => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]);

                $stripeUrl = $paymentLink
                    . '?client_reference_id=' . urlencode($merchantReference)
                    . '&prefilled_email=' . urlencode((string) $customer->user_email);

                return redirect($stripeUrl);
            }

            // Plans without a payment link (or custom): redirect to plan checkout page.
            return redirect(url('/plan-checkout.php'))->with('success',
                'Your account has been created. Complete your plan payment below to activate it.'
            );
        }

        // Skip for Now — existing email verification flow.
        $activationSent = $this->sendActivation($site, $customer);

        $message = $activationSent
            ? 'Your account has been created. Please use the verification link sent to your email before signing in. If you do not see it in your inbox, please check your spam or junk folder.'
            : 'Your account has been created. Please contact support to activate your account.';

        if ($activationSent) {
            $message .= ' After verification, your account will stay pending until an admin approves it.';
        }

        return redirect(url('/login.php'))->with('success', $message);
    }

    public function activate(Request $request)
    {
        $site = $this->site($request);
        $selector = trim((string) $request->query('selector', ''));
        $token = trim((string) $request->query('token', ''));

        $record = $this->activationRecord($selector, $token, $site);
        abort_unless($record, 404);

        $customer = AdminUser::query()
            ->customers()
            ->forWebsite($site->legacyKey)
            ->where('user_id', $record->customer_user_id)
            ->first();

        abort_unless($customer, 404);

        DB::table(self::ACTIVATION_TABLE)
            ->where('customer_user_id', $customer->user_id)
            ->where('site_legacy_key', $site->legacyKey)
            ->delete();

        if ($this->requiresAdminApproval($customer)) {
            $customer->update([
                'is_active' => 0,
                'exist_customer' => '0',
            ]);

            PortalMailer::sendHtml(
                '1dollardigitizing@gmail.com',
                'New customer pending approval — '.$site->brandName,
                '<p>A new customer has registered and is waiting for admin approval.</p>'
                .'<ul>'
                .'<li><strong>Name:</strong> '.e(trim((string) ($customer->display_name ?: $customer->user_name))).'</li>'
                .'<li><strong>Email:</strong> '.e((string) $customer->user_email).'</li>'
                .'<li><strong>Username:</strong> '.e((string) $customer->user_name).'</li>'
                .'<li><strong>Site:</strong> '.e($site->displayLabel()).'</li>'
                .'</ul>'
                .'<p><a href="'.e(url('/v/customer-approvals.php')).'">Review pending customers</a></p>'
            );

            return view('customer.auth.activation-result', [
                'pageTitle' => 'Verification Complete',
                'activated' => true,
                'message' => 'Your email has been verified. This account is now waiting for admin approval before you can sign in.',
                'nextStepUrl' => '/',
                'nextStepLabel' => 'Return To Website',
            ]);
        }

        // If the customer selected a plan during signup, send them to plan checkout.
        if ($request->session()->has('signup_selected_plan')) {
            $request->session()->forget([
                'admin_user_id',
                'admin_user_name',
                'team_user_id',
                'team_user_name',
            ]);
            $request->session()->regenerate();
            $request->session()->put([
                'customer_user_id'   => (int) $customer->user_id,
                'customer_user_name' => (string) $customer->display_name,
                'customer_site_key'  => $site->legacyKey,
            ]);

            return redirect(url('/plan-checkout.php'))->with('success', 'Your email has been verified. Complete your plan payment below and your account will be activated.');
        }

        return view('customer.auth.activation-result', [
            'pageTitle' => 'Account Activated',
            'activated' => true,
            'message' => 'Your customer account for '.$site->displayLabel().' is now active. You can sign in and continue with quotes, orders, billing, and downloads inside this website.',
            'nextStepUrl' => '/login.php',
            'nextStepLabel' => 'Go to Login',
        ]);
    }

    public function showResend(Request $request)
    {
        return view('customer.auth.resend-verification', [
            'pageTitle' => 'Resend Verification Email',
            'signupOffer' => SignupOfferService::offerSummary(SignupOfferService::activeSignupOffer($this->site($request))),
        ]);
    }

    public function resend(Request $request)
    {
        $site = $this->site($request);

        if (CustomerPublicRateLimit::tooManyAttempts($request, 'resend-verification', $site->legacyKey, 'verification', 5, 1800)) {
            return back()->withErrors(['verification' => 'Too many verification requests from this connection. Please try again later.'])->withInput();
        }

        $validated = $request->validate([
            'identity' => ['required', 'string', 'max:190'],
        ], [], [
            'identity' => 'email or user name',
        ]);

        if (! TurnstileVerifier::verify($request, 'customer-resend-verification')) {
            return back()->withErrors(['verification' => 'Please complete the security verification and try again.'])->withInput();
        }

        $identity = trim((string) $validated['identity']);

        $customer = AdminUser::query()
            ->customers()
            ->forWebsite($site->legacyKey)
            ->where('is_active', 0)
            ->where(function ($query) use ($identity) {
                $query->where('user_email', $identity)
                    ->orWhere('alternate_email', $identity)
                    ->orWhere('user_name', $identity);
            })
            ->orderByDesc('user_id')
            ->first();

        if ($customer && ! $this->sendActivation($site, $customer)) {
            return back()->withErrors([
                'verification' => 'We could not send a verification email right now. Please try again shortly or contact support.',
            ])->withInput();
        }

        return redirect(url('/login.php'))->with(
            'success',
            'If we found a pending account for this website, we sent a fresh verification email. Please check your inbox and spam or junk folder.'
        );
    }

    public function showPlanCheckout(Request $request)
    {
        // Must have a customer session (set during activation) to access this page.
        if (! $request->session()->has('customer_user_id')) {
            return redirect(url('/login.php'));
        }

        $plan = $request->session()->get('signup_selected_plan');

        // If no plan in session, redirect to pricing so they can still choose.
        if (! $plan) {
            return redirect(url('/price-plan.php'))->with('info', 'Choose a plan below and contact us to complete your purchase.');
        }

        $customer = AdminUser::query()
            ->customers()
            ->forWebsite($this->site($request)->legacyKey)
            ->where('user_id', (int) $request->session()->get('customer_user_id'))
            ->first();

        if (! $customer) {
            return redirect(url('/login.php'));
        }

        return view('customer.payments.plan-checkout', [
            'plan'        => $plan,
            'allPlans'    => self::signupPlans(),
            'siteContext' => $this->site($request),
            'customer'    => $customer,
        ]);
    }

    public function startPlanCheckoutPayment(Request $request)
    {
        if (! $request->session()->has('customer_user_id')) {
            return redirect(url('/login.php'));
        }

        $plan = $request->session()->get('signup_selected_plan');
        if (! $plan || ($plan['type'] ?? '') !== 'credit') {
            return redirect(url('/plan-checkout.php'))->withErrors(['payment' => 'Please select a credit pack to continue.']);
        }

        $site = $this->site($request);
        $customer = AdminUser::query()
            ->customers()
            ->forWebsite($site->legacyKey)
            ->where('user_id', (int) $request->session()->get('customer_user_id'))
            ->first();

        if (! $customer) {
            return redirect(url('/login.php'));
        }

        $paymentLink = $plan['plan']['payment_link'] ?? '';
        if ($paymentLink === '') {
            return redirect(url('/plan-checkout.php'))->withErrors(['payment' => 'Payment link is not configured for this plan.']);
        }

        $price = round((float) $plan['plan']['price'], 2);
        $now   = now()->format('Y-m-d H:i:s');

        $merchantReference = strtoupper(implode('-', [
            'CREDIT',
            $site->slug ?: $site->legacyKey,
            $customer->user_id,
            now()->format('YmdHis'),
            Str::upper(Str::random(6)),
        ]));

        PaymentTransaction::query()->create([
            'site_id'            => $site->id,
            'user_id'            => $customer->user_id,
            'order_id'           => null,
            'billing_id'         => null,
            'legacy_website'     => $site->legacyKey,
            'provider'           => 'stripe_payment_link',
            'merchant_reference' => $merchantReference,
            'payment_scope'      => 'credit_purchase',
            'status'             => 'initiated',
            'currency'           => 'USD',
            'requested_amount'   => number_format($price, 2, '.', ''),
            'return_url'         => url('/stripe-return.php'),
            'provider_payload'   => json_encode([
                'plan_id'         => $plan['plan']['id'] ?? '',
                'plan_full_price' => (float) ($plan['plan']['full_price'] ?? 0),
                'plan_label'      => $plan['plan']['label'] ?? '',
                'payment_link'    => $paymentLink,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        $stripeUrl = $paymentLink
            . '?client_reference_id=' . urlencode($merchantReference)
            . '&prefilled_email=' . urlencode((string) $customer->user_email);

        return redirect($stripeUrl);
    }

    private function storeSignupPlanInSession(Request $request, array $validated): void
    {
        $type = $validated['signup_plan_type'] ?? 'none';
        $id   = $validated['signup_plan_id']   ?? '';

        if ($type === 'none' || $id === '' || $id === 'none') {
            return;
        }

        $plans = self::signupPlans();
        $list  = $plans[$type] ?? [];
        $match = collect($list)->firstWhere('id', $id);

        if ($match) {
            $request->session()->put('signup_selected_plan', [
                'type' => $type,
                'plan' => $match,
            ]);
        }
    }

    public static function signupPlans(): array
    {
        return [
            'credit' => [
                ['id' => 'credit-100',  'label' => 'Starter',    'stitches' => '100,000',   'price' => 95,  'full_price' => 100,  'saving' => 5,   'discount' => '5% off',  'per_k' => '$0.95', 'payment_link' => 'https://buy.stripe.com/aFa6oG1XX9RN5sF9na4Ja0e'],
                ['id' => 'credit-200',  'label' => 'Basic',      'stitches' => '200,000',   'price' => 186, 'full_price' => 200,  'saving' => 14,  'discount' => '7% off',  'per_k' => '$0.93', 'payment_link' => 'https://buy.stripe.com/14A3cu5a91lhbR3gPC4Ja0f'],
                ['id' => 'credit-300',  'label' => 'Value',      'stitches' => '300,000',   'price' => 270, 'full_price' => 300,  'saving' => 30,  'discount' => '10% off', 'per_k' => '$0.90', 'payment_link' => 'https://buy.stripe.com/4gMfZgeKJbZV4oB0QE4Ja0g'],
                ['id' => 'credit-400',  'label' => 'Studio',     'stitches' => '400,000',   'price' => 352, 'full_price' => 400,  'saving' => 48,  'discount' => '12% off', 'per_k' => '$0.88', 'payment_link' => 'https://buy.stripe.com/5kQeVc0TT6FB2gt7f24Ja0h'],
                ['id' => 'credit-500',  'label' => 'Production', 'stitches' => '500,000',   'price' => 425, 'full_price' => 500,  'saving' => 75,  'discount' => '15% off', 'per_k' => '$0.85', 'payment_link' => 'https://buy.stripe.com/28E28qdGF6FB08l8j64Ja0i'],
                ['id' => 'credit-1000', 'label' => 'Enterprise', 'stitches' => '1,000,000', 'price' => 800, 'full_price' => 1000, 'saving' => 200, 'discount' => '20% off', 'per_k' => '$0.80', 'payment_link' => 'https://buy.stripe.com/cNifZg8ml1lh3kxbvi4Ja0j'],
            ],
            'subscription' => [
                ['id' => 'sub-growth',     'label' => 'Starter',    'price' => 90,   'credits' => 100,  'turnaround' => '⏰ 24-Hour Standard', 'rate' => '$0.90', 'billing' => '/month', 'payment_link' => 'https://buy.stripe.com/cNi4gyeKJ9RN5sFdDq4Ja09'],
                ['id' => 'sub-studio',     'label' => 'Growth',     'price' => 170,  'credits' => 200,  'turnaround' => '⏰ 24-Hour Standard', 'rate' => '$0.85', 'billing' => '/month', 'payment_link' => 'https://buy.stripe.com/cNi00iaut3tp5sFare4Ja0a'],
                ['id' => 'sub-production', 'label' => 'Studio',     'price' => 320,  'credits' => 400,  'turnaround' => '⚡ 12-Hour Priority', 'rate' => '$0.80', 'billing' => '/month', 'payment_link' => 'https://buy.stripe.com/3cIfZgeKJ7JF4oBeHu4Ja0b'],
                ['id' => 'sub-enterprise', 'label' => 'Production', 'price' => 700,  'credits' => 1000, 'turnaround' => '⚡ 12-Hour Priority', 'rate' => '$0.70', 'billing' => '/month', 'payment_link' => 'https://buy.stripe.com/7sYaEWfONaVR8ERfLy4Ja0c'],
                ['id' => 'sub-corporate',  'label' => 'Enterprise', 'price' => 1200, 'credits' => 2000, 'turnaround' => '🏆 8-Hour Super Rush', 'rate' => '$0.60', 'billing' => '/month', 'payment_link' => 'https://buy.stripe.com/8x2bJ0dGFfc7aMZeHu4Ja0d'],
            ],
        ];
    }

    private function sendActivation(SiteContext $site, AdminUser $customer): bool
    {
        if (! Schema::hasTable(self::ACTIVATION_TABLE)) {
            return false;
        }

        $selector = bin2hex(random_bytes(8));
        $validator = bin2hex(random_bytes(32));
        $expiresAt = now()->addDays(3);

        DB::table(self::ACTIVATION_TABLE)
            ->where('customer_user_id', $customer->user_id)
            ->where('site_legacy_key', $site->legacyKey)
            ->delete();

        DB::table(self::ACTIVATION_TABLE)->insert([
            'site_id' => $site->id,
            'site_legacy_key' => $site->legacyKey,
            'customer_user_id' => $customer->user_id,
            'selector' => $selector,
            'token_hash' => hash('sha256', $validator),
            'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $activationUrl = url('/confirmation_registration.php?selector='.$selector.'&token='.$validator);
        return SystemEmailTemplates::send(
            (string) $customer->user_email,
            'customer_account_activation',
            $site,
            [
                'customer_name' => trim((string) ($customer->display_name ?: $customer->user_name)),
                'customer_email' => (string) $customer->user_email,
                'activation_url' => $activationUrl,
                'expires_at' => $expiresAt->format('F j, Y g:i A'),
            ],
            fn () => [
                'subject' => $site->brandName.' account activation',
                'body' => view('customer.emails.activation', [
                    'customer' => $customer,
                    'siteContext' => $site,
                    'activationUrl' => $activationUrl,
                    'expiresAt' => $expiresAt,
                    'signupOffer' => SignupOfferService::offerSummary(SignupOfferService::activeSignupOffer($site)),
                ])->render(),
            ]
        );
    }

    private function activationRecord(string $selector, string $validator, SiteContext $site): ?object
    {
        if (! Schema::hasTable(self::ACTIVATION_TABLE) || $selector === '' || $validator === '') {
            return null;
        }

        $record = DB::table(self::ACTIVATION_TABLE)
            ->where('site_legacy_key', $site->legacyKey)
            ->where('selector', $selector)
            ->where('expires_at', '>=', now()->format('Y-m-d H:i:s'))
            ->first();

        if (! $record) {
            return null;
        }

        return hash_equals((string) $record->token_hash, hash('sha256', $validator)) ? $record : null;
    }

    private function deriveUsername(string $email, SiteContext $site): string
    {
        $base = strtolower(trim((string) explode('@', $email)[0]));
        $base = preg_replace('/[^a-z0-9._-]/', '', $base) ?: 'customer';

        $username = $base;
        $suffix = 1;

        while (AdminUser::query()->customers()->active()->forWebsite($site->legacyKey)->where('user_name', $username)->exists()) {
            $suffix++;
            $username = $base.$suffix;
        }

        return $username;
    }

    private function legacyRegistrationDefaults(SiteContext $site): array
    {
        static $userColumns = null;

        if ($userColumns === null) {
            $userColumns = collect(Schema::getColumns('users'))
                ->pluck('name')
                ->flip()
                ->all();
        }

        $defaults = [];

        // Keep signup compatible with legacy user tables that still require
        // internal bookkeeping fields without defaults.
        $legacyValues = [
            'security_key' => Str::random(40),
            'alternate_email' => '',
            'digitzing_format' => '',
            'vertor_format' => '',
            'topup' => '',
            'register_by' => $site->legacyKey,
        ];

        foreach ($legacyValues as $column => $value) {
            if (isset($userColumns[$column])) {
                $defaults[$column] = $value;
            }
        }

        return $defaults;
    }

    private function requiresAdminApproval(AdminUser $customer): bool
    {
        return trim(strtolower((string) ($customer->user_term ?? ''))) === 'dc';
    }

    private function clearCustomerSignupSession(Request $request): void
    {
        if (! $request->session()->has('customer_user_id') && ! $request->session()->has('customer_pending_2fa')) {
            return;
        }

        CustomerRememberLogin::clearCurrent($request);
        $request->session()->forget([
            'customer_user_id',
            'customer_user_name',
            'customer_site_key',
            'customer_pending_2fa',
        ]);
        $request->session()->regenerate();
    }

    private function site(Request $request): SiteContext
    {
        return $request->attributes->get('siteContext');
    }

    private function defaultCustomerCreditLimit(): string
    {
        return $this->configuredMoneyDefault('sites.default_customer_credit_limit');
    }

    private function defaultCustomerSingleOrderLimit(): string
    {
        return $this->configuredMoneyDefault('sites.default_customer_single_order_limit');
    }

    private function configuredMoneyDefault(string $configKey): string
    {
        $raw = config($configKey, 0);
        $value = is_numeric($raw) ? max(0, (float) $raw) : 0.0;

        return number_format($value, 2, '.', '');
    }
}
