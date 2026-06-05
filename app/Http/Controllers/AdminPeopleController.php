<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Support\AdminNavigation;
use App\Support\AdminReferenceData;
use App\Support\CustomerApprovalQueue;
use App\Support\CustomerPricing;
use App\Support\EmailValidation;
use App\Support\PasswordManager;
use App\Support\SiteResolver;
use App\Support\SiteContext;
use App\Support\PortalMailer;
use App\Support\SignupOfferService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPeopleController extends Controller
{
    public function customers(Request $request)
    {
        $customers = AdminUser::query()
            ->customers()
            ->active()
            ->where('is_active', 1)
            ->when($request->filled('txtUserID'), function (Builder $query) use ($request) {
                $query->where('user_id', 'like', '%'.trim((string) $request->string('txtUserID')).'%');
            })
            ->when($request->filled('txtUserName'), function (Builder $query) use ($request) {
                $term = '%'.$request->string('txtUserName')->trim().'%';
                $query->where(function (Builder $searchQuery) use ($term) {
                    $searchQuery
                        ->where('user_name', 'like', $term)
                        ->orWhere('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('company', 'like', $term)
                        ->orWhereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", [$term]);
                });
            })
            ->when($request->filled('txtEmail'), function (Builder $query) use ($request) {
                $query->where('user_email', 'like', '%'.$request->string('txtEmail')->trim().'%');
            })
            ->when(
                $this->sortColumn((string) $request->input('column_name'), 'user_id', ['user_id', 'user_name', 'user_email', 'user_country', 'userip_addrs', 'date_added', 'topup', 'subscription_plan']) === 'topup',
                fn ($q) => $q->orderByRaw('CAST(topup AS DECIMAL(12,2)) '.$this->sortDirection((string) $request->input('sort'), 'desc')),
                fn ($q) => $q->orderBy($this->sortColumn((string) $request->input('column_name'), 'user_id', ['user_id', 'user_name', 'user_email', 'user_country', 'userip_addrs', 'date_added', 'topup', 'subscription_plan']), $this->sortDirection((string) $request->input('sort'), 'desc'))
            )
            ->paginate(30)
            ->withQueryString();

        return view('admin.people.customers', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'customers' => $customers,
        ]);
    }

    public function pendingApprovals(Request $request)
    {
        $approvalState = trim((string) $request->input('approval_state'));
        $queueUserIds = CustomerApprovalQueue::userIds(null, $approvalState !== '' ? $approvalState : null);
        $claimStatuses = CustomerApprovalQueue::claimStatusMap($queueUserIds);

        $customers = AdminUser::query()
            ->customers()
            ->active()
            ->whereIn('user_id', $queueUserIds === [] ? [0] : $queueUserIds)
            ->when($request->filled('txtUserID'), function (Builder $query) use ($request) {
                $query->where('user_id', 'like', '%'.trim((string) $request->string('txtUserID')).'%');
            })
            ->when($request->filled('txtUserName'), function (Builder $query) use ($request) {
                $term = '%'.$request->string('txtUserName')->trim().'%';
                $query->where(function (Builder $searchQuery) use ($term) {
                    $searchQuery
                        ->where('user_name', 'like', $term)
                        ->orWhere('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('company', 'like', $term)
                        ->orWhereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", [$term]);
                });
            })
            ->when($request->filled('txtEmail'), function (Builder $query) use ($request) {
                $query->where('user_email', 'like', '%'.$request->string('txtEmail')->trim().'%');
            })
            ->orderBy($this->sortColumn((string) $request->input('column_name'), 'user_id', ['user_id', 'user_name', 'user_email', 'website', 'user_country', 'date_added']), $this->sortDirection((string) $request->input('sort'), 'desc'))
            ->paginate(30)
            ->withQueryString();

        $customers->getCollection()->transform(function (AdminUser $customer) use ($claimStatuses) {
            $approvalState = CustomerApprovalQueue::stateForCustomer(
                $customer,
                $claimStatuses[(int) $customer->user_id] ?? null
            );

            $customer->approval_state = $approvalState;
            $customer->approval_state_label = CustomerApprovalQueue::stateLabel($approvalState);
            $customer->signup_path_label = trim((string) $customer->user_term) === 'ip'
                ? 'Welcome Payment'
                : 'Admin Approval';

            return $customer;
        });

        return view('admin.people.pending-approvals', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'customers' => $customers,
            'approvalState' => $approvalState,
            'approvalStateOptions' => CustomerApprovalQueue::stateFilterOptions(),
        ]);
    }

    public function verifyCustomerEmail(Request $request, AdminUser $customer)
    {
        abort_unless((int) $customer->usre_type_id === AdminUser::TYPE_CUSTOMER, 404);

        $approvalState = CustomerApprovalQueue::stateForCustomer(
            $customer,
            CustomerApprovalQueue::claimStatusMap([(int) $customer->user_id], trim((string) $customer->website))[(int) $customer->user_id] ?? null
        );

        if ($approvalState !== CustomerApprovalQueue::STATE_PENDING_VERIFICATION) {
            return redirect()->to($this->withQuery('/v/customer-approvals.php', $request->except('_token')))
                ->with('error', 'This customer account is not waiting for email verification.');
        }

        $this->clearActivationToken($customer);

        if (trim(strtolower((string) ($customer->user_term ?? ''))) === 'dc') {
            $customer->update([
                'is_active' => 0,
                'exist_customer' => '0',
            ]);

            $message = 'Customer email has been marked verified and the account is now waiting for admin approval.';
        } else {
            if (! SignupOfferService::adminVerifyPendingClaim($customer)) {
                return redirect()->to($this->withQuery('/v/customer-approvals.php', $request->except('_token')))
                    ->with('error', 'No pending verification record was found for this customer account.');
            }

            $customer->update([
                'is_active' => 0,
                'exist_customer' => '0',
            ]);

            $message = 'Customer email has been marked verified. The account is now waiting for the customer welcome payment.';
        }

        return redirect()->to($this->withQuery('/v/customer-approvals.php', $request->except('_token')))
            ->with('success', $message);
    }

    public function approveCustomer(Request $request, AdminUser $customer)
    {
        abort_unless((int) $customer->usre_type_id === AdminUser::TYPE_CUSTOMER, 404);

        $adminName = $request->attributes->get('adminUser')?->user_name ?: 'admin';
        $isManualApprovalSignup = trim(strtolower((string) ($customer->user_term ?? ''))) === 'dc';

        $customer->update([
            'is_active' => 1,
            'exist_customer' => '1',
        ]);

        $welcomePaymentPending = $isManualApprovalSignup
            ? false
            : SignupOfferService::adminApprovePendingPayment($customer, $adminName);

        if ($isManualApprovalSignup) {
            SignupOfferService::completeManualApprovalClaim($customer, $adminName);
        }

        $this->sendApprovalEmail($customer);

        return redirect()->to($this->withQuery('/v/customer-approvals.php', $request->except('_token')))
            ->with('success', $welcomePaymentPending
                ? 'Customer has been approved successfully. The welcome payment offer still remains pending until the $1 payment is completed.'
                : 'Customer has been approved successfully.');
    }

    public function blockCustomer(Request $request, AdminUser $customer)
    {
        abort_unless((int) $customer->usre_type_id === 1, 404);

        $returnTo = trim((string) $request->input('return_to', ''));

        $updateData = ['is_active' => 0];

        if ($returnTo === 'customer-approvals') {
            // Pre-approval block: set user_term='blocked' so the account
            // disappears from all approval-queue queries (which check for
            // user_term='dc' or user_term='ip') but remains visible in the
            // Inactive Customers report via the widened scopeBlockedCustomerAccounts.
            $updateData['user_term'] = 'blocked';

            // Cancel any pending promotion claim so this user_id is no longer
            // returned by verifiedPendingPaymentUserIds() queue lookups.
            if (Schema::hasTable('site_promotion_claims')) {
                DB::table('site_promotion_claims')
                    ->where('user_id', $customer->user_id)
                    ->whereIn('status', [
                        SignupOfferService::STATUS_PENDING_VERIFICATION,
                        SignupOfferService::STATUS_PENDING_PAYMENT,
                    ])
                    ->update(['status' => 'rejected', 'updated_at' => now()->format('Y-m-d H:i:s')]);
            }
        }

        $customer->update($updateData);

        // Pause an active subscription so it doesn't accrue credits while blocked.
        if (
            trim((string) ($customer->subscription_plan ?? '')) !== ''
            && strtolower(trim((string) ($customer->subscription_status ?? ''))) === 'active'
        ) {
            $customer->update(['subscription_status' => 'paused']);
        }

        $redirectBase = $returnTo === 'customer-approvals'
            ? url('/v/customer-approvals.php')
            : url('/v/customer_list.php');

        return redirect()->to($redirectBase.'?'.http_build_query($request->except(['_token', 'return_to'])))
            ->with('success', 'Customer has been blocked successfully.');
    }

    public function deleteCustomer(Request $request, AdminUser $customer)
    {
        abort_unless((int) $customer->usre_type_id === 1, 404);

        $adminUser = $request->attributes->get('adminUser');

        $customer->update([
            'end_date' => now()->format('Y-m-d H:i:s'),
            'deleted_by' => $adminUser?->user_name ?: 'admin',
        ]);

        return redirect()->to(url('/v/customer_list.php').'?'.http_build_query($request->except('_token')))
            ->with('success', 'Customer has been deleted successfully.');
    }

    public function createCustomer(Request $request)
    {
        return view('admin.people.create-customer', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'countries' => AdminReferenceData::countriesForCustomerForms(),
            'companyTypes' => AdminReferenceData::companyTypes(),
        ]);
    }

    public function storeCustomer(Request $request)
    {
        $site = SiteResolver::forRequest($request);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'user_email' => ['required', EmailValidation::rule(), 'max:190'],
            'user_password' => ['required', 'string', 'min:6', 'max:100'],
            'user_country' => ['required', 'string', 'max:150', Rule::in(AdminReferenceData::countries())],
            'user_phone' => ['required', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:150'],
            'company_type' => ['nullable', 'string', 'max:100'],
            'package_type' => ['required', 'string', 'in:BASIC,BUSINESS,CORPORATE'],
        ]);

        $email = strtolower(trim((string) $validated['user_email']));
        $username = $this->deriveCustomerUsername($email, $site);

        $existing = AdminUser::query()
            ->customers()
            ->active()
            ->forWebsite($site->legacyKey)
            ->where(function ($query) use ($email, $username) {
                $query->where('user_email', $email)
                    ->orWhere('user_name', $username);
            })
            ->first();

        if ($existing) {
            return back()
                ->withErrors(['user_email' => 'A customer with this email or username already exists.'])
                ->withInput();
        }

        $now = now()->format('Y-m-d H:i:s');
        $ipAddress = (string) ($request->ip() ?? '127.0.0.1');

        $customer = AdminUser::query()->create(array_merge([
            'site_id' => $site->id,
            'website' => $site->legacyKey,
            'usre_type_id' => AdminUser::TYPE_CUSTOMER,
            'user_name' => $username,
            'first_name' => trim((string) $validated['first_name']),
            'last_name' => trim((string) $validated['last_name']),
            'company' => trim((string) ($validated['company'] ?? '')),
            'company_type' => trim((string) ($validated['company_type'] ?? '')),
            'user_email' => $email,
            'user_country' => trim((string) $validated['user_country']),
            'user_phone' => trim((string) $validated['user_phone']),
            'is_active' => 1,
            'exist_customer' => '1',
            'payment_terms' => 5,
            'date_added' => $now,
            'customer_pending_order_limit' => 3,
            'userip_addrs' => $ipAddress,
            'user_term' => 'dc',
            'package_type' => (string) $validated['package_type'],
            'real_user' => '1',
            'ref_code' => strtolower($site->legacyKey).random_int(10000, 99999).str_replace('.', '', $ipAddress),
            'ref_code_other' => 'Admin Portal',
        ], CustomerPricing::sitePricingPayload($site), $this->legacyCustomerDefaults($site)));

        $customer->forceFill(PasswordManager::payload((string) $validated['user_password']))->save();

        return redirect()->to(url('/v/customer_list.php'))
            ->with('success', 'Customer '.$customer->display_name.' has been created successfully. Username: '.$username);
    }

    private function deriveCustomerUsername(string $email, SiteContext $site): string
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

    private function legacyCustomerDefaults(SiteContext $site): array
    {
        static $userColumns = null;

        if ($userColumns === null) {
            $userColumns = collect(Schema::getColumns('users'))
                ->pluck('name')
                ->flip()
                ->all();
        }

        $defaults = [];
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

    public function teams(Request $request)
    {
        $statusFilter = trim((string) $request->input('status', 'all'));

        $teams = AdminUser::query()
            ->teamPortalUsers()
            ->when($statusFilter === 'active', fn (Builder $q) => $q->where('is_active', 1))
            ->when($statusFilter === 'locked', fn (Builder $q) => $q->where('is_active', 0))
            ->when($request->filled('txtUserID'), function (Builder $query) use ($request) {
                $query->where('user_id', 'like', '%'.trim((string) $request->string('txtUserID')).'%');
            })
            ->when($request->filled('txtUserName'), function (Builder $query) use ($request) {
                $term = '%'.trim((string) $request->string('txtUserName')).'%';
                $query->where(function (Builder $searchQuery) use ($term) {
                    $searchQuery
                        ->where('user_name', 'like', $term)
                        ->orWhere('user_email', 'like', $term)
                        ->orWhere('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) like ?", [$term]);
                });
            })
            ->when($request->filled('account_type'), function (Builder $query) use ($request) {
                $type = trim((string) $request->string('account_type'));

                if ($type === 'team') {
                    $query->where('usre_type_id', AdminUser::TYPE_TEAM);
                } elseif ($type === 'supervisor') {
                    $query->where('usre_type_id', AdminUser::TYPE_SUPERVISOR);
                }
            })
            ->orderBy($this->sortColumn((string) $request->input('column_name'), 'user_id', ['user_id', 'user_name', 'date_added']), $this->sortDirection((string) $request->input('sort'), 'desc'))
            ->paginate(50)
            ->withQueryString();

        return view('admin.people.teams', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'teams' => $teams,
        ]);
    }

    public function disableTeam(Request $request, AdminUser $team)
    {
        abort_unless(in_array((int) $team->usre_type_id, [AdminUser::TYPE_TEAM, AdminUser::TYPE_SUPERVISOR], true), 404);

        $team->update(['is_active' => 0]);

        return redirect()->to(url('/v/show-all-teams.php').'?'.http_build_query($request->except('_token')))
            ->with('success', 'Team account has been deactivated.');
    }

    public function unlockTeam(Request $request, AdminUser $team)
    {
        abort_unless(in_array((int) $team->usre_type_id, [AdminUser::TYPE_TEAM, AdminUser::TYPE_SUPERVISOR], true), 404);

        $team->update(['is_active' => 1]);

        return redirect()->to(url('/v/show-all-teams.php').'?'.http_build_query($request->except('_token')))
            ->with('success', 'Team account has been activated.');
    }

    public function destroyTeam(Request $request, AdminUser $team)
    {
        abort_unless(in_array((int) $team->usre_type_id, [AdminUser::TYPE_TEAM, AdminUser::TYPE_SUPERVISOR], true), 404);

        $team->delete();

        return redirect()->to(url('/v/show-all-teams.php').'?'.http_build_query($request->except('_token')))
            ->with('success', 'Team member has been permanently deleted.');
    }

    private function sortColumn(string $column, string $default, array $allowed): string
    {
        return in_array($column, $allowed, true) ? $column : $default;
    }

    private function sortDirection(string $direction, string $default = 'desc'): string
    {
        $direction = strtolower($direction);

        return in_array($direction, ['asc', 'desc'], true) ? $direction : $default;
    }

    private function withQuery(string $path, array $query): string
    {
        $query = array_filter($query, static fn ($value) => $value !== null && $value !== '');

        return $query === [] ? url($path) : url($path).'?'.http_build_query($query);
    }

    private function configuredMoneyDefault(string $configKey): string
    {
        $raw = config($configKey, 0);
        $value = is_numeric($raw) ? max(0, (float) $raw) : 0.0;

        return number_format($value, 2, '.', '');
    }

    private function clearActivationToken(AdminUser $customer): void
    {
        if (! Schema::hasTable('customer_activation_tokens')) {
            return;
        }

        DB::table('customer_activation_tokens')
            ->where('customer_user_id', $customer->user_id)
            ->when(trim((string) $customer->website) !== '', function ($query) use ($customer) {
                $query->where('site_legacy_key', trim((string) $customer->website));
            })
            ->delete();
    }

    private function sendApprovalEmail(AdminUser $customer): void
    {
        $email = trim((string) ($customer->user_email ?? ''));
        if ($email === '') {
            return;
        }

        $customerName = htmlspecialchars($customer->display_name, ENT_QUOTES);
        $subject = 'Your account has been approved — Welcome to 1 Dollar Digitizing';

        $body = '<p>Hi '.$customerName.',</p>
<p>Great news! Your account has been successfully reviewed and approved, and you are all set up in our system.</p>
<p>You can now log in and start placing new embroidery digitizing orders whenever you are ready.</p>
<p>If you need any assistance submitting your first design or have any questions about the workflow, please don\'t hesitate to reach out. We look forward to working with you!</p>
<p>Best regards,<br>1 Dollar Digitizing</p>';

        PortalMailer::sendHtml($email, $subject, $body);
    }
}
