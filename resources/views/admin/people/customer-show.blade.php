@extends('layouts.admin')

@section('title', 'Customer #'.$customer->user_id.' | 1Dollar Admin')
@section('page_heading', 'Customer Detail #'.$customer->user_id)
@section('page_subheading', 'Review customer account details, pricing, and approval limits.')

@section('content')
    @php
        $source = request('source');
        $backUrl = match($source) {
            'customer-approvals'   => url('/v/customer-approvals.php'),
            'inactive-customers'   => url('/v/block-customer_list.php'),
            default                => url('/v/customer_list.php'),
        };
        $backLabel = match($source) {
            'customer-approvals'   => 'Customer Approvals',
            'inactive-customers'   => 'Inactive Customers',
            default                => 'Customers',
        };
        $isInactive = $source === 'inactive-customers' || (int) $customer->is_active !== 1;
    @endphp
    <section class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;gap:16px;align-items:center;flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0 0 6px;font-size:1.15rem;">{{ $customer->display_name }}</h3>
                    <p class="muted" style="margin:0;">Customer account, contact info, pricing, and approval limits.</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a class="badge" href="{{ $backUrl }}">Back to {{ $backLabel }}</a>
                    <a class="badge" href="{{ url('/v/edit-customer-detail.php?uid='.$customer->user_id.($source ? '&source='.rawurlencode($source) : '')) }}">Edit Customer</a>
                    @if ($isInactive)
                        <form method="post" action="{{ url('/v/block-customer_list/'.$customer->user_id.'/unblock') }}" onsubmit="return confirm('Unblock this customer?');">
                            @csrf
                            <button type="submit" style="background:linear-gradient(135deg,#1b8d5a,#146845);">Unblock</button>
                        </form>
                    @else
                        <form method="post" action="{{ url('/v/simulate-login/'.$customer->user_id) }}" onsubmit="return confirm('Start a simulated customer session for support?');">
                            @csrf
                            <input type="hidden" name="return_to" value="{{ request()->fullUrl() }}">
                            <button type="submit">Simulate Login</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="table-wrap" style="margin-top:18px;">
                <table>
                    <tbody>
                    <tr><th>User ID</th><td>{{ $customer->user_id }}</td><th>Username</th><td>{{ $customer->user_name ?: '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $customer->user_email ?: '-' }}</td><th>Status</th><td>{{ (int) $customer->is_active === 1 ? 'Active' : 'Blocked' }}</td></tr>
                    <tr><th>First Name</th><td>{{ $customer->first_name ?: '-' }}</td><th>Last Name</th><td>{{ $customer->last_name ?: '-' }}</td></tr>
                    <tr><th>Company</th><td>{{ $customer->company ?: '-' }}</td><th>Company Type</th><td>{{ $customer->company_type ?: '-' }}</td></tr>
                    <tr><th>Address</th><td>{{ $customer->company_address ?: '-' }}</td><th>Zip Code</th><td>{{ $customer->zip_code ?: '-' }}</td></tr>
                    <tr><th>City</th><td>{{ $customer->user_city ?: '-' }}</td><th>Country</th><td>{{ $customer->user_country ?: '-' }}</td></tr>
                    <tr><th>Phone</th><td>{{ $customer->user_phone ?: '-' }}</td><th>Last IP</th><td>{{ $customer->userip_addrs ?: '-' }}</td></tr>
                    @php
                        $plan = strtolower(trim((string) ($customer->subscription_plan ?? '')));
                        $billingMap = [
                            'production' => ['priority' => 'standard', 'superrush' => 'priority', 'flashrush' => 'superrush'],
                            'enterprise' => ['priority' => 'standard', 'superrush' => 'priority', 'flashrush' => 'superrush'],
                            'corporate'  => ['superrush' => 'standard', 'flashrush' => 'superrush'],
                        ][$plan] ?? [];
                        $turnaroundLabels = [
                            'standard'  => 'Standard (24h)',
                            'priority'  => 'Priority (12h)',
                            'superrush' => 'Super Rush (6h)',
                            'flashrush' => 'Flash Rush (4h)',
                        ];
                        $availableTurnarounds = match($plan) {
                            'production', 'enterprise' => ['standard', 'priority', 'superrush', 'flashrush'],
                            'corporate'                => ['standard', 'superrush', 'flashrush'],
                            default                    => ['standard', 'priority', 'superrush'],
                        };
                        $scheduleRows = collect($availableTurnarounds)->map(function($code) use ($feeSchedule, $turnaroundLabels, $billingMap) {
                            $entry = $feeSchedule[$code] ?? null;
                            $billedAs = $billingMap[$code] ?? null;
                            $note = $billedAs ? '(billed at ' . $turnaroundLabels[$billedAs] . ' rate — plan benefit)' : null;
                            return [
                                'label'  => $turnaroundLabels[$code] ?? $code,
                                'rate'   => isset($entry['amount']) ? '$' . number_format($entry['amount'], 2) . '/1k' : '-',
                                'note'   => $note,
                            ];
                        })->chunk(2);
                    @endphp
                    @foreach ($scheduleRows as $pair)
                        @php $row = $pair->values(); @endphp
                        <tr>
                            <th>{{ $row[0]['label'] }}</th>
                            <td>{{ $row[0]['rate'] }}@if($row[0]['note']) <span style="color:#64748b;font-size:0.8em;font-weight:400;">{{ $row[0]['note'] }}</span>@endif</td>
                            @if(isset($row[1]))
                                <th>{{ $row[1]['label'] }}</th>
                                <td>{{ $row[1]['rate'] }}@if($row[1]['note']) <span style="color:#64748b;font-size:0.8em;font-weight:400;">{{ $row[1]['note'] }}</span>@endif</td>
                            @else
                                <th></th><td></td>
                            @endif
                        </tr>
                    @endforeach
                    @php
                        $spLabel = match($customer->subscription_plan ?? '') {
                            'growth'     => 'Starter',
                            'studio'     => 'Growth',
                            'production' => 'Studio',
                            'enterprise' => 'Production',
                            'corporate'  => 'Enterprise',
                            default      => $customer->subscription_plan ? ucfirst($customer->subscription_plan) : '— None —',
                        };
                    @endphp
                    <tr><th>Subscription Plan</th><td>{{ $spLabel }}</td><th>Subscription Renews On</th><td>{{ $customer->subscription_renews_at ? \Carbon\Carbon::parse($customer->subscription_renews_at)->format('M d, Y') : '—' }}</td></tr>
                    @php
                        $pendingDisplay = (int) ($customer->customer_pending_order_limit ?? 0) > 0
                            ? (int) $customer->customer_pending_order_limit
                            : \App\Support\CustomerPendingLimit::calculate($customer);
                    @endphp
                    <tr><th>Pending Orders Limit</th><td>{{ $pendingDisplay }}</td><th>Payment Terms</th><td>{{ $customer->payment_terms ?: '-' }}</td></tr>
                    <tr>
                        <th>Credit Balance</th>
                        <td style="font-weight:700;color:{{ $depositBalance > 0 ? '#2a7d4f' : '#526071' }};">
                            ${{ number_format($depositBalance, 2) }}
                        </td>
                        <th>Date Added</th>
                        <td>{{ $customer->date_added ?: '-' }}</td>
                    </tr>
                    @if (!empty($customer->max_num_stiches))
                    <tr><th>Max Number of Stitches Override</th><td colspan="3">{{ $customer->max_num_stiches }}</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div style="margin-bottom:16px;">
                <h3 style="margin:0 0 6px;font-size:1.15rem;">Add / Deduct Credit</h3>
                <p class="muted" style="margin:0;">Enter a positive amount to credit the balance (e.g. payment received outside the system), or a negative amount to deduct (e.g. taking payment for an email order). A note is required.</p>
            </div>

            @if (session('credit_success'))
                <div class="alert alert-success" style="margin-bottom:16px;">{{ session('credit_success') }}</div>
            @endif

            <form id="manualCreditForm" method="post" action="{{ url('/v/customers/'.$customer->user_id.'/add-credit') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
                @csrf
                <input type="hidden" name="source" value="{{ $source ?? '' }}">
                <div style="flex:0 0 auto;min-width:130px;max-width:180px;">
                    <label style="display:block;font-size:0.84rem;font-weight:700;margin-bottom:6px;">Amount ($) <span style="font-weight:400;color:#64748b;">— Current: ${{ number_format($depositBalance, 2) }}</span></label>
                    <input type="number" id="manualCreditAmt" name="credit_amount" required step="0.01" placeholder="e.g. 25.00 or -10.00" style="width:100%;">
                </div>
                <div style="flex:1;min-width:220px;">
                    <label style="display:block;font-size:0.84rem;font-weight:700;margin-bottom:6px;">Note / Reason <span style="color:#9d2d17;">*</span></label>
                    <input type="text" id="manualCreditNote" name="credit_note" required minlength="3" maxlength="500" placeholder="e.g. Bank transfer, order #1234 email payment…" style="width:100%;">
                </div>
                <button type="submit" onclick="var a=document.getElementById('manualCreditAmt').value,n=document.getElementById('manualCreditNote').value;if(!n){alert('Please enter a note.');return false;}var v=parseFloat(a);return v&&confirm((v>0?'Add $'+v.toFixed(2):'Deduct $'+Math.abs(v).toFixed(2))+' for {{ addslashes($customer->display_name) }}?')">Apply</button>
            </form>
            @error('credit_amount')
                <div style="margin-top:8px;color:#9d2d17;font-size:0.88rem;">{{ $message }}</div>
            @enderror
            @error('credit_note')
                <div style="margin-top:8px;color:#9d2d17;font-size:0.88rem;">{{ $message }}</div>
            @enderror
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div style="margin-bottom:16px;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0 0 6px;font-size:1.15rem;">Credit History</h3>
                    <p class="muted" style="margin:0;">Admin credit adjustments, payments, and deductions for this customer.</p>
                </div>
                <a href="{{ url('/v/customer-detail.php') }}?uid={{ $customer->user_id }}&export=credit-history" class="button secondary" style="white-space:nowrap;flex-shrink:0;">Download Excel</a>
            </div>

            @if ($creditLedger->isEmpty())
                <div class="empty-state">No credit history found for this customer.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Reference</th>
                                <th>Notes</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($creditLedger as $entry)
                                @php
                                    $entryLabel = match((string) $entry->entry_type) {
                                        'payment'      => 'Payment',
                                        'overpayment'  => 'Overpayment',
                                        'applied'      => 'Applied to Invoice',
                                        'adjustment'   => 'Manual Adjustment',
                                        default        => ucfirst((string) $entry->entry_type),
                                    };
                                    $amountClass = ((float) $entry->amount) >= 0 ? 'status-success' : 'status-error';
                                @endphp
                                <tr>
                                    <td style="font-size:0.8rem;white-space:nowrap;">{{ $entry->date_added }}</td>
                                    <td><span class="badge {{ $amountClass }}">{{ $entryLabel }}</span></td>
                                    <td style="font-weight:700;color:{{ ((float) $entry->amount) >= 0 ? '#2a7d4f' : '#9d2d17' }};">${{ number_format((float) $entry->amount, 2) }}</td>
                                    <td style="font-size:0.78rem;word-break:break-all;max-width:160px;">{{ $entry->reference_no }}</td>
                                    <td style="font-size:0.85rem;max-width:260px;">{{ $entry->notes }}</td>
                                    <td style="font-size:0.8rem;">{{ $entry->created_by }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div style="margin-bottom:16px;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0 0 6px;font-size:1.15rem;">Payment Transactions</h3>
                    <p class="muted" style="margin:0;">Recent payment attempts, credit purchases, and checkout sessions for this customer.</p>
                </div>
                <a href="{{ url('/v/customer-detail.php') }}?uid={{ $customer->user_id }}&export=payment-transactions" class="button secondary" style="white-space:nowrap;flex-shrink:0;">Download Excel</a>
            </div>

            @if ($paymentTransactions->isEmpty())
                <div class="empty-state">No payment transactions found for this customer.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reference</th>
                                <th>Scope</th>
                                <th>Provider</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Confirmed</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentTransactions as $tx)
                                <tr>
                                    <td>{{ $tx->id }}</td>
                                    <td style="font-size:0.78rem;word-break:break-all;max-width:180px;">{{ $tx->merchant_reference }}</td>
                                    <td>{{ $tx->payment_scope }}</td>
                                    <td>{{ $tx->provider }}</td>
                                    <td>
                                        @php
                                            $statusTone = match((string) $tx->status) {
                                                'verified', 'success' => 'status-success',
                                                'initiated', 'pending' => 'status-pending',
                                                'failed', 'verification_failed', 'amount_mismatch' => 'status-error',
                                                default => 'status-pending',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusTone }}">{{ $tx->status }}</span>
                                    </td>
                                    <td>${{ $tx->requested_amount }}</td>
                                    <td>${{ $tx->confirmed_amount ?: '-' }}</td>
                                    <td style="font-size:0.8rem;white-space:nowrap;">{{ $tx->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

@endsection
