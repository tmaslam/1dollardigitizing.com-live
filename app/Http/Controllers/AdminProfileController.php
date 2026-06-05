<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\Attachment;
use App\Models\Billing;
use App\Models\FreelanceQuote;
use App\Models\Order;
use App\Models\OrderComment;
use App\Models\SupervisorTeamMember;
use App\Support\AdminNavigation;
use App\Support\CustomerBalance;
use App\Support\AdminOrderQueues;
use App\Support\AdminReferenceData;
use App\Support\CustomerPricing;
use App\Support\DownstreamSharing;
use App\Support\PasswordManager;
use App\Support\PortalMailer;
use App\Support\SecurityAudit;
use App\Support\SignupOfferService;
use App\Support\TwoFactorAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProfileController extends Controller
{
    public function adminPasswordForm(Request $request)
    {
        $adminUser = $request->attributes->get('adminUser');

        abort_unless($adminUser && (int) $adminUser->usre_type_id === AdminUser::TYPE_ADMIN, 403);

        return view('admin.people.admin-password', [
            'adminUser' => $adminUser,
            'navCounts' => AdminNavigation::counts(),
        ]);
    }

    public function adminPasswordSave(Request $request)
    {
        $adminUser = $request->attributes->get('adminUser');

        abort_unless($adminUser && (int) $adminUser->usre_type_id === AdminUser::TYPE_ADMIN, 403);

        $validated = $request->validate([
            'txtPassword' => ['required', 'string', 'min:6', 'max:100'],
            'txtCPassword' => ['required', 'same:txtPassword'],
        ], [
            'txtCPassword.same' => 'The confirm password must match the password.',
        ], [
            'txtPassword' => 'password',
            'txtCPassword' => 'confirm password',
        ]);

        $email = trim((string) ($adminUser->user_email ?? ''));
        if ($email === '') {
            return back()->withErrors(['txtPassword' => 'Cannot send verification code: no email address is registered on this admin account.'])->withInput();
        }

        $code = TwoFactorAuth::issueCode('admin', (int) $adminUser->user_id, length: 10, tokenTypeSuffix: 'password');
        if ($code === '') {
            return back()->withErrors(['txtPassword' => 'Unable to generate a verification code right now. Please try again shortly.'])->withInput();
        }

        TwoFactorAuth::sendCode($email, (string) ($adminUser->display_name ?: $adminUser->user_name), $code, (string) config('app.name', '1Dollar'), purpose: 'password change');

        $request->session()->put('admin_pending_password_change', (string) $validated['txtPassword']);

        return redirect()->to(url('/v/change-password-verify.php'))
            ->with('success', 'A 6-digit verification code has been sent to your registered email address.');
    }

    public function adminPasswordVerifyForm(Request $request)
    {
        $adminUser = $request->attributes->get('adminUser');

        abort_unless($adminUser && (int) $adminUser->usre_type_id === AdminUser::TYPE_ADMIN, 403);
        abort_unless($request->session()->has('admin_pending_password_change'), 403);

        return view('admin.people.admin-password-verify', [
            'adminUser' => $adminUser,
            'navCounts' => AdminNavigation::counts(),
        ]);
    }

    public function adminPasswordVerify(Request $request)
    {
        $adminUser = $request->attributes->get('adminUser');

        abort_unless($adminUser && (int) $adminUser->usre_type_id === AdminUser::TYPE_ADMIN, 403);

        $pendingPassword = $request->session()->get('admin_pending_password_change');
        if (! $pendingPassword) {
            return redirect()->to(url('/v/change-password.php'))
                ->withErrors(['txtPassword' => 'Your password change session has expired. Please start again.']);
        }

        $validated = $request->validate([
            'code' => ['required', 'string'],
        ], [], [
            'code' => 'verification code',
        ]);

        $result = TwoFactorAuth::verifyCode('admin', (int) $adminUser->user_id, (string) $validated['code'], tokenTypeSuffix: 'password');

        if ($result === null) {
            $request->session()->forget('admin_pending_password_change');

            return redirect()->to(url('/v/change-password.php'))
                ->withErrors(['txtPassword' => 'The verification code has expired or too many incorrect attempts were made. Please start again.']);
        }

        if ($result === false) {
            $remaining = TwoFactorAuth::remainingAttempts('admin', (int) $adminUser->user_id, tokenTypeSuffix: 'password');

            return back()->withErrors(['code' => 'Incorrect verification code. '.$remaining.' attempt'.($remaining === 1 ? '' : 's').' remaining.']);
        }

        $adminUser->forceFill(PasswordManager::payload((string) $pendingPassword))->save();
        $request->session()->forget('admin_pending_password_change');

        SecurityAudit::record($request, 'admin.password_changed', 'Admin password was changed after 2FA verification.', [
            'admin_user_id' => $adminUser->user_id,
            'admin_user_name' => $adminUser->user_name,
        ], 'info');

        return redirect()->to(url('/v/change-password.php'))
            ->with('success', 'Admin password updated successfully.');
    }

    public function customerShow(Request $request)
    {
        $customer = AdminUser::query()->findOrFail((int) $request->query('uid'));
        $export   = (string) $request->query('export', '');

        if ($export === 'credit-history') {
            $ledger = \App\Models\CustomerCreditLedger::query()
                ->where('user_id', $customer->user_id)
                ->orderByDesc('date_added')
                ->get();

            $entryLabel = fn (string $type) => match ($type) {
                'payment'     => 'Payment',
                'overpayment' => 'Overpayment',
                'applied'     => 'Applied to Invoice',
                'adjustment'  => 'Manual Adjustment',
                default       => ucfirst($type),
            };

            $rows = $ledger->map(fn ($e) => [
                $e->date_added,
                $entryLabel((string) $e->entry_type),
                number_format((float) $e->amount, 2),
                $e->reference_no,
                $e->notes,
                $e->created_by,
            ])->all();

            $total = $ledger->sum(fn ($e) => (float) $e->amount);
            $rows[] = ['', 'TOTAL', number_format($total, 2), '', '', ''];

            return $this->csvResponse(
                'credit-history-uid'.$customer->user_id,
                ['Date', 'Type', 'Amount', 'Reference', 'Notes', 'Created By'],
                $rows
            );
        }

        if ($export === 'payment-transactions') {
            $transactions = \App\Models\PaymentTransaction::query()
                ->where('user_id', $customer->user_id)
                ->orderByDesc('created_at')
                ->get();

            $rows = $transactions->map(fn ($tx) => [
                $tx->id,
                $tx->merchant_reference,
                $tx->payment_scope,
                $tx->provider,
                $tx->status,
                number_format((float) $tx->requested_amount, 2),
                $tx->confirmed_amount ? number_format((float) $tx->confirmed_amount, 2) : '',
                $tx->created_at,
            ])->all();

            $totalRequested  = $transactions->sum(fn ($tx) => (float) $tx->requested_amount);
            $totalConfirmed  = $transactions->sum(fn ($tx) => (float) $tx->confirmed_amount);
            $rows[] = ['', '', '', '', 'TOTAL', number_format($totalRequested, 2), number_format($totalConfirmed, 2), ''];

            return $this->csvResponse(
                'payment-transactions-uid'.$customer->user_id,
                ['ID', 'Reference', 'Scope', 'Provider', 'Status', 'Requested', 'Confirmed', 'Created'],
                $rows
            );
        }

        $paymentTransactions = \App\Models\PaymentTransaction::query()
            ->where('user_id', $customer->user_id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $creditLedger = \App\Models\CustomerCreditLedger::query()
            ->where('user_id', $customer->user_id)
            ->orderByDesc('date_added')
            ->limit(50)
            ->get();

        $depositBalance = CustomerBalance::deposit($customer->topup);

        $site = \App\Support\SiteResolver::fromLegacyKey((string) ($customer->website ?: config('sites.primary_legacy_key', '1dollar')))
            ?: \App\Support\SiteResolver::fromHost((string) config('sites.primary_host', 'localhost'));

        $feeSchedule = \App\Support\SitePricing::turnaroundFeeSchedule($customer, $site, 'digitizing');

        return view('admin.people.customer-show', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'customer' => $customer,
            'paymentTransactions' => $paymentTransactions,
            'creditLedger' => $creditLedger,
            'depositBalance' => $depositBalance,
            'feeSchedule' => $feeSchedule,
        ]);
    }

    private function csvResponse(string $prefix, array $headers, array $rows): \Illuminate\Http\Response
    {
        $filename = $prefix.'-'.now()->format('Ymd-His').'.csv';
        $handle   = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function resetCustomerPassword(Request $request, AdminUser $customer)
    {
        // Guard 1 — middleware already enforces admin.auth (TYPE_ADMIN only),
        // but be explicit: the acting user must be a verified admin.
        $actingAdmin = $request->attributes->get('adminUser');
        abort_unless($actingAdmin instanceof AdminUser && (int) $actingAdmin->usre_type_id === AdminUser::TYPE_ADMIN, 403);

        // Guard 2 — target must be a customer account, never an admin/team/supervisor.
        abort_unless((int) $customer->usre_type_id === AdminUser::TYPE_CUSTOMER, 403);

        // Guard 3 — an admin cannot use this endpoint on their own account.
        abort_if((int) $actingAdmin->user_id === (int) $customer->user_id, 403);

        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'max:100'],
        ], [], [
            'new_password' => 'new password',
        ]);

        $customer->forceFill(PasswordManager::payload((string) $validated['new_password']))->save();

        // Notify the customer so they are aware their password was changed by support.
        $customerEmail = trim((string) $customer->user_email);
        if ($customerEmail !== '') {
            $name = trim((string) ($customer->display_name ?: $customer->user_name));
            $body = '<!DOCTYPE html><html><body style="font-family:sans-serif;color:#19232e;padding:32px;">'
                .'<p>Hi '.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').',</p>'
                .'<p>Your account password was recently reset by a support representative. You can sign in now using your new password.</p>'
                .'<p>If you did not request this change or do not recognise it, please contact support immediately.</p>'
                .'</body></html>';
            PortalMailer::sendHtml($customerEmail, 'Your password has been reset', $body);
        }

        // Audit trail — every admin-initiated customer password reset is logged.
        SecurityAudit::record(
            $request,
            'admin.customer_password_reset',
            'Admin reset customer password.',
            [
                'admin_user_id' => $actingAdmin->user_id,
                'admin_user_name' => $actingAdmin->user_name,
                'target_customer_id' => $customer->user_id,
                'target_customer_name' => $customer->user_name,
            ],
            'info'
        );

        $source = trim((string) $request->input('source'));
        $redirectUrl = url('/v/customer-detail.php?uid='.$customer->user_id.($source ? '&source='.rawurlencode($source) : ''));

        return redirect()->to($redirectUrl)->with('success', 'Customer password has been reset successfully.');
    }

    public function addCustomerCredit(Request $request, AdminUser $customer)
    {
        $actingAdmin = $request->attributes->get('adminUser');
        abort_unless($actingAdmin instanceof AdminUser && (int) $actingAdmin->usre_type_id === AdminUser::TYPE_ADMIN, 403);
        abort_unless((int) $customer->usre_type_id === AdminUser::TYPE_CUSTOMER, 403);

        $validated = $request->validate([
            'credit_amount' => ['required', 'numeric', 'not_in:0', 'min:-99999', 'max:99999'],
            'credit_note'   => ['required', 'string', 'min:3', 'max:500'],
        ], [
            'credit_note.required' => 'A note is required whenever a credit adjustment is made.',
        ], [
            'credit_amount' => 'credit amount',
            'credit_note'   => 'note',
        ]);

        $amount    = round((float) $validated['credit_amount'], 2);
        $note      = trim((string) $validated['credit_note']);
        $adminName = (string) ($actingAdmin->user_name ?: 'admin');
        $refNo     = 'admin-manual-'.now()->format('YmdHis').'-'.$customer->user_id;
        $website   = CustomerBalance::normalizeWebsite((string) ($customer->website ?: config('sites.primary_legacy_key', '1dollar')));

        if ($amount > 0) {
            CustomerBalance::recordIncomingPayment(
                userId: (int) $customer->user_id,
                amount: $amount,
                referenceNo: $refNo,
                createdBy: $adminName,
                notes: $note,
                applyToDue: true,
                website: $website,
            );
        } else {
            $absAmount      = abs($amount);
            $currentBalance = CustomerBalance::deposit($customer->topup);
            if ($absAmount > $currentBalance + 0.001) {
                return back()->withErrors(['credit_amount' => 'Deduction ($'.number_format($absAmount, 2).') exceeds current balance ($'.number_format($currentBalance, 2).').'])->withInput();
            }
            CustomerBalance::recordManualDeduction(
                userId: (int) $customer->user_id,
                website: $website,
                amount: $absAmount,
                referenceNo: $refNo,
                createdBy: $adminName,
                notes: $note,
            );
        }

        SecurityAudit::record(
            $request,
            $amount > 0 ? 'admin.manual_credit_added' : 'admin.manual_credit_deducted',
            $amount > 0 ? 'Admin added manual credit to customer balance.' : 'Admin deducted credit from customer balance.',
            [
                'admin_user_id'   => $actingAdmin->user_id,
                'admin_user_name' => $adminName,
                'customer_id'     => $customer->user_id,
                'amount'          => $amount,
                'note'            => $note,
                'reference_no'    => $refNo,
            ],
            'info'
        );

        $source      = trim((string) $request->input('source'));
        $redirectUrl = url('/v/customer-detail.php?uid='.$customer->user_id.($source ? '&source='.rawurlencode($source) : ''));
        $msg = $amount > 0
            ? '$'.number_format($amount, 2).' added to credit balance.'
            : '$'.number_format(abs($amount), 2).' deducted from credit balance.';

        return redirect()->to($redirectUrl)->with('credit_success', $msg);
    }

    public function customerEdit(Request $request)
    {
        $customer = AdminUser::query()->findOrFail((int) $request->query('uid'));

        return view('admin.people.customer-edit', [
            'adminUser'      => $request->attributes->get('adminUser'),
            'navCounts'      => AdminNavigation::counts(),
            'customer'       => $customer,
            'depositBalance' => CustomerBalance::deposit($customer->topup),
            'companyTypes'   => AdminReferenceData::companyTypes(),
            'countries'      => AdminReferenceData::countries(),
            'feeSchedule'    => \App\Support\SitePricing::turnaroundFeeSchedule(
                $customer,
                \App\Support\SiteResolver::fromLegacyKey((string) ($customer->website ?: config('sites.primary_legacy_key', '1dollar')))
                    ?: \App\Support\SiteResolver::fromHost((string) config('sites.primary_host', 'localhost')),
                'digitizing'
            ),
        ]);
    }

    public function customerUpdate(Request $request)
    {
        $customer = AdminUser::query()->findOrFail((int) $request->input('uid'));
        $source = trim((string) $request->input('source'));

        $validated = $request->validate([
            'user_name' => ['nullable', 'string', 'max:150'],
            'txtPassword' => ['nullable', 'string', 'min:6', 'max:100'],
            'txtFirstName' => ['nullable', 'string', 'max:150'],
            'txtLastName' => ['nullable', 'string', 'max:150'],
            'txtCompany' => ['nullable', 'string', 'max:150'],
            'selCompanyTypes' => ['nullable', 'string', 'max:150'],
            'txtEmail' => ['nullable', 'string', 'max:150'],
            'txtCompanyAddress' => ['nullable', 'string', 'max:255'],
            'txtZipCode' => ['nullable', 'string', 'max:20'],
            'txtCity' => ['nullable', 'string', 'max:150'],
            'selCountry' => ['nullable', 'string', 'max:150'],
            'txtTelephone' => ['nullable', 'string', 'max:150'],
            'txtContactPerson' => ['nullable', 'string', 'max:150'],
            'is_active' => ['required', 'in:0,1'],
            'normal_fee' => ['nullable', 'string', 'max:20'],
            'middle_fee' => ['nullable', 'string', 'max:20'],
            'urgent_fee' => ['nullable', 'string', 'max:20'],
            'super_fee' => ['nullable', 'string', 'max:20'],
            'flash_fee' => ['nullable', 'string', 'max:20'],
            'subscription_plan' => ['nullable', 'string', 'in:,growth,studio,production,enterprise,corporate'],
            'subscription_renews_at' => ['nullable', 'date'],
            'payment_terms' => ['nullable', 'string', 'max:5'],
            'customer_pending_order_limit' => ['nullable', 'string', 'max:11'],
            'add_credit'      => ['nullable', 'numeric', 'not_in:0', 'min:-99999', 'max:99999'],
            'add_credit_note' => ['required_with:add_credit', 'nullable', 'string', 'min:3', 'max:500'],
            'max_num_stiches' => ['nullable', 'string', 'max:11'],
        ], [], [
            'user_name' => 'user name',
            'txtPassword' => 'password',
            'txtFirstName' => 'first name',
            'txtLastName' => 'last name',
            'txtCompany' => 'company',
            'selCompanyTypes' => 'company type',
            'txtEmail' => 'email address',
            'txtCompanyAddress' => 'company address',
            'txtZipCode' => 'zip code',
            'txtCity' => 'city',
            'selCountry' => 'country',
            'txtTelephone' => 'telephone',
            'txtContactPerson' => 'contact person',
            'is_active' => 'status',
            'normal_fee' => 'normal fee',
            'middle_fee' => 'express fee',
            'urgent_fee' => 'urgent fee',
            'super_fee' => 'super rush fee',
            'flash_fee' => 'flash rush fee',
            'subscription_plan' => 'subscription plan',
            'subscription_renews_at' => 'subscription renewal date',
            'payment_terms' => 'payment terms',
            'customer_pending_order_limit' => 'pending order limit',
            'add_credit'      => 'credit amount',
            'add_credit_note' => 'credit note',
            'max_num_stiches' => 'maximum stitches',
        ]);

        $updates = [
            'user_name' => $validated['user_name'] ?? '',
            'first_name' => $validated['txtFirstName'] ?? '',
            'last_name' => $validated['txtLastName'] ?? '',
            'company' => $validated['txtCompany'] ?? '',
            'company_type' => $validated['selCompanyTypes'] ?? '',
            'user_email' => $validated['txtEmail'] ?? '',
            'company_address' => $validated['txtCompanyAddress'] ?? '',
            'zip_code' => $validated['txtZipCode'] ?? '',
            'user_city' => $validated['txtCity'] ?? '',
            'user_country' => $validated['selCountry'] ?? '',
            'user_phone' => $validated['txtTelephone'] ?? '',
            'contact_person' => $validated['txtContactPerson'] ?? '',
            'is_active' => (int) $validated['is_active'],
            'customer_pending_order_limit' => $validated['customer_pending_order_limit'] ?? '',
            'payment_terms' => $validated['payment_terms'] ?? '',
            'usre_type_id' => (int) $customer->usre_type_id,
            'max_num_stiches' => $validated['max_num_stiches'] ?? '',
            'subscription_plan' => trim((string) ($validated['subscription_plan'] ?? '')),
            'subscription_renews_at' => $validated['subscription_renews_at'] ?? null,
        ];

        $updates = array_merge($updates, CustomerPricing::customPricingPayload($validated));

        $customer->update($updates);

        // Apply manual credit/deduction if provided
        if ($request->filled('add_credit') && ($creditAmount = round((float) $validated['add_credit'], 2)) !== 0.0) {
            $adminName  = (string) ($request->attributes->get('adminUser')?->user_name ?: 'admin');
            $creditNote = trim((string) ($validated['add_credit_note'] ?? ''));
            $refNo      = 'admin-edit-'.now()->format('YmdHis').'-'.$customer->user_id;
            $website    = CustomerBalance::normalizeWebsite((string) ($customer->website ?: config('sites.primary_legacy_key', '1dollar')));
            if ($creditAmount > 0) {
                CustomerBalance::recordIncomingPayment(
                    userId: (int) $customer->user_id,
                    amount: $creditAmount,
                    referenceNo: $refNo,
                    createdBy: $adminName,
                    notes: $creditNote ?: 'Manual credit added via edit form.',
                    applyToDue: true,
                    website: $website,
                );
            } else {
                CustomerBalance::recordManualDeduction(
                    userId: (int) $customer->user_id,
                    website: $website,
                    amount: abs($creditAmount),
                    referenceNo: $refNo,
                    createdBy: $adminName,
                    notes: $creditNote ?: 'Manual deduction via edit form.',
                );
            }
        }
        // Recalculate pending orders limit whenever balance or subscription changes
        $customer->refresh();
        $recalculated = \App\Support\CustomerPendingLimit::calculate($customer);
        if ((string) ($updates['customer_pending_order_limit'] ?? '') === '') {
            $customer->update(['customer_pending_order_limit' => $recalculated]);
        }

        if ($request->filled('txtPassword')) {
            $customer->forceFill(PasswordManager::payload((string) $validated['txtPassword']))->save();
        }

        if ((int) $updates['is_active'] === 1) {
            SignupOfferService::adminFinalizeCustomerActivation(
                $customer->fresh(),
                (string) ($request->attributes->get('adminUser')?->user_name ?: 'admin')
            );

            $customer->refresh();

            if ((string) ($customer->exist_customer ?? '') !== '1') {
                $customer->update(['exist_customer' => '1']);
            }
        }

        $editUrl = url('/v/edit-customer-detail.php?uid='.$customer->user_id.($source ? '&source='.rawurlencode($source) : ''));

        return redirect()->to($editUrl)
            ->with('success', 'Customer information updated successfully.');
    }

    public function teamForm(Request $request)
    {
        $team = $request->filled('user_id')
            ? AdminUser::query()->findOrFail((int) $request->query('user_id'))
            : new AdminUser(['usre_type_id' => 2, 'is_active' => 1]);
        abort_unless(in_array((int) ($team->usre_type_id ?: AdminUser::TYPE_TEAM), [AdminUser::TYPE_TEAM, AdminUser::TYPE_SUPERVISOR], true), 404);
        $accountType = (int) ($team->usre_type_id ?: AdminUser::TYPE_TEAM) === AdminUser::TYPE_SUPERVISOR ? 'supervisor' : 'team';

        return view('admin.people.team-form', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'team' => $team,
            'mode' => $team->exists ? 'edit' : 'create',
            'accountType' => $accountType,
        ]);
    }

    public function teamSave(Request $request)
    {
        $team = $request->filled('user_id')
            ? AdminUser::query()->findOrFail((int) $request->input('user_id'))
            : new AdminUser();
        if ($team->exists) {
            abort_unless(in_array((int) $team->usre_type_id, [AdminUser::TYPE_TEAM, AdminUser::TYPE_SUPERVISOR], true), 404);
        }

        $validated = $request->validate([
            'user_id'     => ['nullable', 'integer'],
            'role'        => ['required', 'in:supervisor,inhouse,freelance,vector'],
            'txtTeamName' => ['required', 'string', 'max:150'],
            'txtPassword' => [$team->exists ? 'nullable' : 'required', 'string', 'min:6', 'max:100'],
            'txtCPassword'=> [$team->exists ? 'nullable' : 'required', 'same:txtPassword'],
            'txtEmail'    => ['required', 'email', 'max:150'],
        ], [
            'txtCPassword.same' => 'The confirm password must match the password.',
        ], [
            'user_id'     => 'user',
            'role'        => 'account role',
            'txtTeamName' => 'user name',
            'txtPassword' => 'password',
            'txtCPassword'=> 'confirm password',
            'txtEmail'    => 'email address',
        ]);

        $targetType = $validated['role'] === 'supervisor' ? AdminUser::TYPE_SUPERVISOR : AdminUser::TYPE_TEAM;
        $targetGroup = match ($validated['role']) {
            'supervisor' => null,
            'freelance'  => 'freelance',
            'vector'     => 'vector',
            default      => 'inhouse',
        };

        $duplicateQuery = AdminUser::query()
            ->where('user_name', $validated['txtTeamName']);

        if (in_array($targetType, [AdminUser::TYPE_TEAM, AdminUser::TYPE_SUPERVISOR], true)) {
            $duplicateQuery->teamPortalUsers();
        } else {
            $duplicateQuery->where('usre_type_id', $targetType);
        }

        if ($team->exists) {
            $duplicateQuery->where('user_id', '!=', $team->user_id);
        }

        if ($duplicateQuery->exists()) {
            return back()->withErrors(['txtTeamName' => 'User name already exists.'])->withInput();
        }

        $team->fill([
            'user_name'    => $validated['txtTeamName'],
            'user_email'   => $validated['txtEmail'],
            'usre_type_id' => $targetType,
            'team_group'   => $targetGroup,
            'is_active'    => $team->exists ? (int) ($team->is_active ?? 1) : 1,
        ]);

        if (! $team->exists) {
            $createdBy = $request->attributes->get('adminUser')?->user_name ?: 'admin';
            $team->fill([
                'first_name' => '',
                'last_name' => '',
                'security_key' => Str::random(40),
                'company' => '',
                'company_type' => '',
                'alternate_email' => '',
                'company_address' => '',
                'zip_code' => '',
                'user_city' => '',
                'user_country' => '',
                'user_phone' => '',
                'contact_person' => '',
                'middle_fee' => 1.50,
                'super_fee' => 0,
                'date_added' => now()->format('Y-m-d H:i:s'),
                'userip_addrs' => '',
                'digitzing_format' => '',
                'vertor_format' => '',
                'topup' => '',
                'exist_customer' => '0',
                'user_term' => '',
                'package_type' => '',
                'real_user' => '0',
                'ref_code' => '',
                'ref_code_other' => '',
                'register_by' => $createdBy,
            ]);
        }

        if ($request->filled('txtPassword')) {
            $team->forceFill(PasswordManager::payload((string) $validated['txtPassword']));
        }

        $team->save();

        if ($targetType === AdminUser::TYPE_TEAM && $request->filled('supervisor_user_id')) {
            SupervisorTeamMember::query()->updateOrCreate([
                'supervisor_user_id' => (int) $request->input('supervisor_user_id'),
                'member_user_id' => $team->user_id,
            ], [
                'date_added' => now()->format('Y-m-d H:i:s'),
                'end_date' => null,
                'deleted_by' => null,
            ]);
        }

        $accountType = $targetType === AdminUser::TYPE_SUPERVISOR ? 'Supervisor' : 'Team';
        $redirectUrl = url('/v/show-all-teams.php');

        return redirect()->to($redirectUrl)
            ->with('success', $team->wasRecentlyCreated ? $accountType.' has been successfully created.' : $accountType.' information updated successfully.');
    }

    public function assignForm(Request $request)
    {
        $orderId = (int) $request->query('design_id');
        $page = in_array($request->query('page'), ['order', 'quote', 'vector'], true) ? (string) $request->query('page') : 'order';

        $order = Order::query()
            ->with(['customer:user_id,user_name,first_name,last_name,user_email', 'assignee:user_id,user_name,user_email'])
            ->findOrFail($orderId);

        $shareableAttachments = Attachment::query()
            ->where('order_id', $orderId)
            ->whereIn('file_source', [$page === 'quote' ? 'quote' : 'order', 'quote', 'vector', 'color', 'edit order'])
            ->orderByDesc('id')
            ->get();

        $handoffComments = OrderComment::query()
            ->where('order_id', $orderId)
            ->where('comment_source', 'orderTeamComments')
            ->latest('id')
            ->get();

        $sharedAttachmentKeys = Attachment::query()
            ->where('order_id', $orderId)
            ->where('file_source', 'orderTeamImages')
            ->pluck('file_name_with_date')
            ->filter()
            ->all();
        $defaultSelectedAttachmentIds = $shareableAttachments
            ->when($sharedAttachmentKeys !== [], function ($attachments) use ($sharedAttachmentKeys) {
                return $attachments->filter(fn (Attachment $attachment) => in_array($attachment->file_name_with_date, $sharedAttachmentKeys, true));
            })
            ->when($sharedAttachmentKeys === [], fn ($attachments) => $attachments)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $customerSubmissionText = DownstreamSharing::customerSubmissionText($order);
        $existingSharedCustomerText = DownstreamSharing::existingSharedCustomerText($order);
        $existingHandoffText = DownstreamSharing::existingHandoffText($order);
        $customerCommentMode = 'original';

        if ((int) $order->notes_by_admin === 0 && $existingSharedCustomerText === '' && $existingHandoffText === '') {
            $customerCommentMode = 'original';
        } elseif ($order->notes_by_admin) {
            $customerCommentMode = $existingSharedCustomerText !== '' && $existingSharedCustomerText !== $customerSubmissionText
                ? 'edited'
                : 'original';
        } else {
            $customerCommentMode = 'none';
        }

        $backQueue = AdminOrderQueues::normalize((string) $request->query('back', 'all-orders'));

        return view('admin.orders.assign', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'order' => $order,
            'page' => $page,
            'backQueue' => $backQueue,
            'backLabel' => AdminOrderQueues::label($backQueue),
            'shareableAttachments' => $shareableAttachments,
            'handoffComments' => $handoffComments,
            'defaultSelectedAttachmentIds' => $defaultSelectedAttachmentIds,
            'sharedAttachmentKeys' => $sharedAttachmentKeys,
            'customerSubmissionText' => $customerSubmissionText,
            'existingSharedCustomerText' => $existingSharedCustomerText,
            'existingHandoffText' => $existingHandoffText,
            'customerCommentMode' => $customerCommentMode,
            'freelanceQuotes' => FreelanceQuote::query()
                ->with('teamUser:user_id,user_name,first_name,last_name')
                ->where('order_id', $orderId)
                ->orderBy('created_at')
                ->get(),
        ]);
    }

    public function assignSave(Request $request)
    {
        $validated = $request->validate([
            'design_id'              => ['required', 'integer'],
            'page'                   => ['required', 'in:order,quote,vector'],
            'status'                 => ['nullable', 'string'],
            'back'                   => ['nullable', 'string'],
            'group'                  => ['required', 'in:inhouse,freelance,vector'],
            'handoff_comment'        => ['nullable', 'string'],
            'customer_comment_mode'  => ['required', 'in:none,original,edited'],
            'shared_customer_comment'=> ['nullable', 'string'],
            'attachment_ids'         => ['array'],
            'attachment_ids.*'       => ['integer'],
            'notes_by_admin'         => ['nullable', 'string'],
        ], [], [
            'design_id'              => 'order',
            'page'                   => 'page',
            'status'                 => 'status',
            'back'                   => 'return page',
            'group'                  => 'team group',
            'handoff_comment'        => 'handoff comment',
            'customer_comment_mode'  => 'customer note sharing',
            'shared_customer_comment'=> 'shared customer note',
            'attachment_ids'         => 'files',
            'attachment_ids.*'       => 'file',
            'notes_by_admin'         => 'admin notes option',
        ]);

        $order = Order::query()->findOrFail((int) $validated['design_id']);
        $assignedGroup = $validated['group'];
        $submitDate = now()->format('Y-m-d G:i');
        $currentAssignee = (string) $order->assign_to;

        $customerSubmissionText = DownstreamSharing::customerSubmissionText($order);
        $shareMode = (string) $validated['customer_comment_mode'];
        $sharedCustomerComment = match ($shareMode) {
            'original' => $customerSubmissionText,
            'edited' => trim((string) ($validated['shared_customer_comment'] ?? '')),
            default => '',
        };

        if ($shareMode === 'edited' && $sharedCustomerComment === '') {
            return back()->withErrors(['shared_customer_comment' => 'Please enter the customer note text you want to share downstream.'])->withInput();
        }

        $order->update([
            'notes_by_admin' => $shareMode === 'none' ? 0 : 1,
        ]);

        DownstreamSharing::replaceSharedComments($order, [
            [
                'comments' => $sharedCustomerComment,
                'source_page' => 'customer-shared',
            ],
            [
                'comments' => trim((string) ($validated['handoff_comment'] ?? '')),
                'source_page' => 'handoff',
            ],
        ]);

        Attachment::query()
            ->where('order_id', $order->order_id)
            ->where('file_source', 'orderTeamImages')
            ->delete();

        foreach ($validated['attachment_ids'] ?? [] as $attachmentId) {
            $attachment = Attachment::query()
                ->where('order_id', $order->order_id)
                ->find($attachmentId);

            if (! $attachment) {
                continue;
            }

            Attachment::query()->create([
                'order_id' => $order->order_id,
                'file_name' => $attachment->file_name,
                'file_name_with_date' => $attachment->file_name_with_date,
                'file_name_with_order_id' => $attachment->file_name_with_order_id,
                'file_source' => 'orderTeamImages',
                'date_added' => $submitDate,
            ]);
        }

        $requestedStatus = strtolower((string) ($validated['status'] ?? ''));

        if ($currentAssignee !== '' && $currentAssignee !== '0' && $validated['page'] === 'order' && $requestedStatus !== 'disapproved') {
            Billing::query()
                ->where('order_id', $order->order_id)
                ->where('approved', 'yes')
                ->where('payment', 'no')
                ->delete();
        }

        $nextStatus = $requestedStatus === 'disapproved' ? 'disapprove' : 'Underprocess';

        $order->update([
            'assign_to'             => 0,
            'assigned_group'        => $assignedGroup,
            'status'                => $nextStatus,
            'assigned_date'         => $submitDate,
            'working'               => '',
            'supervisor_status'     => null,
            'vender_complete_date'  => null,
            'completion_date'       => null,
        ]);

        $detailUrl = url('/v/orders/'.$order->order_id.'/detail/'.$validated['page']);
        $back = isset($validated['back']) ? AdminOrderQueues::normalize((string) $validated['back']) : null;

        return redirect()->to($back !== null && $back !== ''
            ? $detailUrl.'?'.http_build_query(['back' => $back])
            : $detailUrl)
            ->with('success', 'Order assignment updated successfully.');
    }

    private function sendAssignmentMail(Order $order, AdminUser $team, string $page, string $currentAssignee, string $status): void
    {
        if (! $team->user_email) {
            return;
        }

        if ($currentAssignee === '' || $currentAssignee === '0') {
            if ($page === 'order') {
                $subject = 'New Order has been assigned';
                $text = 'A new order has been assigned to you.';
            } elseif ($page === 'vector') {
                $subject = 'New Vector order has been assigned';
                $text = 'A new vector order has been assigned to you.';
            } else {
                $subject = 'New Quotation has been assigned';
                $text = 'A new quotation has been assigned to you.';
            }
        } else {
            if ($page === 'order' && $status === 'disapproved') {
                $subject = 'Order has been disapproved';
                $text = 'An order has been disapproved and reassigned to you by admin.';
            } elseif ($page === 'vector') {
                $subject = 'Vector has been reassigned';
                $text = 'A vector order has been reassigned to you.';
            } elseif ($page === 'quote') {
                $subject = 'Quotation has been reassigned';
                $text = 'A quotation has been reassigned to you.';
            } else {
                $subject = 'Order has been reassigned';
                $text = 'An order has been reassigned to you by admin.';
            }
        }

        $detailUrl = url('/v/orders/'.$order->order_id.'/detail/'.$page);
        $itemLabel = $page === 'quote' ? 'Quote' : 'Order';
        $queueLabel = ucfirst($page);
        $body = view('admin.emails.team-assignment', [
            'teamName' => trim((string) ($team->display_name ?: $team->user_name ?: 'Team Member')),
            'message' => $text,
            'orderId' => $order->order_id,
            'queueLabel' => $queueLabel,
            'itemLabel' => $itemLabel,
            'detailUrl' => $detailUrl,
            'loginUrl' => url('/'),
        ])->render();

        PortalMailer::sendHtml($team->user_email, $subject, $body);
    }

    public function acceptFreelanceQuote(Request $request, Order $order)
    {
        $validated = $request->validate([
            'quote_id' => ['required', 'integer'],
        ]);

        $quote = FreelanceQuote::query()
            ->where('order_id', $order->order_id)
            ->where('status', 'pending')
            ->findOrFail((int) $validated['quote_id']);

        $freelancer = AdminUser::query()->findOrFail($quote->team_user_id);
        $now = now();

        // Accept chosen quote
        $quote->update([
            'status'      => 'accepted',
            'reviewed_by' => $request->attributes->get('adminUser')->user_id ?? null,
            'reviewed_at' => $now,
        ]);

        // Reject all other pending quotes for this order
        FreelanceQuote::query()
            ->where('order_id', $order->order_id)
            ->where('status', 'pending')
            ->where('id', '!=', $quote->id)
            ->update([
                'status'      => 'rejected',
                'reviewed_by' => $request->attributes->get('adminUser')->user_id ?? null,
                'reviewed_at' => $now,
            ]);

        // Assign the order to the winning freelancer and auto-start it
        $order->update([
            'assign_to'    => $freelancer->user_id,
            'working'      => $now->format('Y-m-d H:i:s'),
            'assigned_date'=> $now->format('Y-m-d G:i'),
        ]);

        // Notify the freelancer
        if ($freelancer->user_email) {
            $body = view('admin.emails.freelance-quote-accepted', [
                'freelancerName' => trim((string) ($freelancer->display_name ?: $freelancer->user_name)),
                'orderId'        => $order->order_id,
                'quotedPrice'    => number_format((float) $quote->quoted_price, 2),
                'detailUrl'      => url('/team/orders/'.$order->order_id.'/detail/order'),
            ])->render();

            PortalMailer::sendHtml($freelancer->user_email, 'Your quote has been accepted — Order #'.$order->order_id, $body);
        }

        return redirect()->back()->with('success', 'Quote accepted. Order assigned to '.$freelancer->display_name.'.');
    }
}
