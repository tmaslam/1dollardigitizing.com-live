@extends('layouts.admin')

@section('title', 'Edit Customer #'.$customer->user_id.' | 1Dollar Admin')
@section('page_heading', 'Edit Customer #'.$customer->user_id)
@section('page_subheading', 'Update customer account details, pricing, and approval limits.')

@section('content')
    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    @php $source = request('source', old('source')); @endphp
    <section class="card">
        <div class="card-body">
            <form method="post" action="{{ url('/v/edit-customer-detail.php') }}">
                @csrf
                <input type="hidden" name="uid" value="{{ $customer->user_id }}">
                <input type="hidden" name="source" value="{{ $source }}">

                <div class="toolbar">
                    <div class="field"><label>User Name</label><input type="text" name="user_name" value="{{ old('user_name', $customer->user_name) }}"></div>
                    <div class="field"><label>Password</label><input type="password" name="txtPassword" value="{{ old('txtPassword') }}" autocomplete="new-password" placeholder="Leave blank to keep current password"></div>
                    <div class="field"><label>First Name</label><input type="text" name="txtFirstName" value="{{ old('txtFirstName', $customer->first_name) }}"></div>
                    <div class="field"><label>Last Name</label><input type="text" name="txtLastName" value="{{ old('txtLastName', $customer->last_name) }}"></div>
                    <div class="field"><label>Company</label><input type="text" name="txtCompany" value="{{ old('txtCompany', $customer->company) }}"></div>
                    <div class="field">
                        <label>Company Type</label>
                        <select name="selCompanyTypes">
                            <option value="">Please Select</option>
                            @foreach ($companyTypes as $type)
                                <option value="{{ $type }}" @selected(old('selCompanyTypes', $customer->company_type) === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label>Email</label><input type="text" name="txtEmail" value="{{ old('txtEmail', $customer->user_email) }}"></div>
                    <div class="field"><label>Address</label><input type="text" name="txtCompanyAddress" value="{{ old('txtCompanyAddress', $customer->company_address) }}"></div>
                    <div class="field"><label>Zip Code</label><input type="text" name="txtZipCode" value="{{ old('txtZipCode', $customer->zip_code) }}"></div>
                    <div class="field"><label>City</label><input type="text" name="txtCity" value="{{ old('txtCity', $customer->user_city) }}"></div>
                    <div class="field">
                        <label>Country</label>
                        <select name="selCountry">
                            <option value="">Please Select</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}" @selected(old('selCountry', $customer->user_country) === $country)>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label>Phone</label><input type="text" name="txtTelephone" value="{{ old('txtTelephone', $customer->user_phone) }}"></div>

                    @php
                        $editPlan = strtolower(trim((string) ($customer->subscription_plan ?? '')));
                        $editBillingMap = [
                            'production' => ['priority' => 'standard', 'superrush' => 'priority', 'flashrush' => 'superrush'],
                            'enterprise' => ['priority' => 'standard', 'superrush' => 'priority', 'flashrush' => 'superrush'],
                            'corporate'  => ['superrush' => 'standard', 'flashrush' => 'superrush'],
                        ][$editPlan] ?? [];
                        $editTurnaroundLabels = [
                            'standard'  => 'Standard (24h)',
                            'priority'  => 'Priority (12h)',
                            'superrush' => 'Super Rush (6h)',
                            'flashrush' => 'Flash Rush (4h)',
                        ];
                        $editAvailable = match($editPlan) {
                            'production', 'enterprise' => ['standard', 'priority', 'superrush', 'flashrush'],
                            'corporate'                => ['standard', 'superrush', 'flashrush'],
                            default                    => ['standard', 'priority', 'superrush'],
                        };
                    @endphp
                    <div class="field" style="flex-basis:100%;max-width:100%;">
                        <label style="margin-bottom:8px;display:block;">Effective Rates <span style="font-weight:400;font-size:0.8em;color:#64748b;">(current — reflects plan benefits)</span></label>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            @foreach ($editAvailable as $code)
                                @php
                                    $entry    = $feeSchedule[$code] ?? null;
                                    $billedAs = $editBillingMap[$code] ?? null;
                                    $rateStr  = isset($entry['amount']) ? '$'.number_format($entry['amount'], 2).'/1k' : '—';
                                    $noteStr  = $billedAs ? 'billed at '.$editTurnaroundLabels[$billedAs].' rate' : null;
                                @endphp
                                <span style="display:inline-flex;flex-direction:column;background:var(--surface-alt,#f4f5f7);border:1px solid var(--line,#e2e6ea);border-radius:8px;padding:6px 12px;font-size:0.82rem;min-width:120px;">
                                    <strong style="font-size:0.75rem;color:#64748b;font-weight:600;margin-bottom:2px;">{{ $editTurnaroundLabels[$code] }}</strong>
                                    <span style="font-weight:700;">{{ $rateStr }}</span>
                                    @if($noteStr)<span style="font-size:0.72rem;color:#64748b;">{{ $noteStr }}</span>@endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @php
                        $overridePlaceholder = function(string $code) use ($feeSchedule, $editBillingMap, $editTurnaroundLabels): string {
                            $entry    = $feeSchedule[$code] ?? null;
                            $billedAs = $editBillingMap[$code] ?? null;
                            $rate     = isset($entry['amount']) ? '$'.number_format($entry['amount'], 2).'/1k' : null;
                            if (!$rate) return 'Blank uses site/plan pricing';
                            if ($billedAs) return 'Effective: '.$rate.' (billed at '.$editTurnaroundLabels[$billedAs].' rate — plan benefit)';
                            return 'Effective: '.$rate;
                        };
                    @endphp
                    <div class="field"><label>Standard Rate Override</label><input type="text" name="normal_fee" value="{{ old('normal_fee', $customer->normal_fee) }}" placeholder="{{ $overridePlaceholder('standard') }}"></div>
                    <div class="field"><label>Priority Rate Override</label><input type="text" name="urgent_fee" value="{{ old('urgent_fee', $customer->urgent_fee) }}" placeholder="{{ $overridePlaceholder('priority') }}"></div>
                    <div class="field"><label>Super Rush Rate Override</label><input type="text" name="super_fee" value="{{ old('super_fee', $customer->super_fee) }}" placeholder="{{ $overridePlaceholder('superrush') }}"></div>
                    <div class="field"><label>Flash Rush Rate Override</label><input type="text" name="flash_fee" value="{{ old('flash_fee', $customer->flash_fee) }}" placeholder="{{ $overridePlaceholder('flashrush') }}"></div>
                    <input type="hidden" name="middle_fee" value="{{ $customer->middle_fee }}">
                    <div class="field">
                        <label>Subscription Plan</label>
                        <select name="subscription_plan">
                            <option value="" @selected(old('subscription_plan', $customer->subscription_plan ?? '') === '')>— None —</option>
                            <option value="growth" @selected(old('subscription_plan', $customer->subscription_plan ?? '') === 'growth')>Starter</option>
                            <option value="studio" @selected(old('subscription_plan', $customer->subscription_plan ?? '') === 'studio')>Growth ⚡ (Flash Rush unlocked)</option>
                            <option value="production" @selected(old('subscription_plan', $customer->subscription_plan ?? '') === 'production')>Studio ⚡ (Flash Rush unlocked)</option>
                            <option value="enterprise" @selected(old('subscription_plan', $customer->subscription_plan ?? '') === 'enterprise')>Production ⚡ (Flash Rush unlocked)</option>
                            <option value="corporate" @selected(old('subscription_plan', $customer->subscription_plan ?? '') === 'corporate')>Enterprise ⚡ (Flash Rush unlocked)</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Subscription Renews On</label>
                        <input type="date" name="subscription_renews_at" value="{{ old('subscription_renews_at', $customer->subscription_renews_at ?? '') }}">
                    </div>
                    <div class="field"><label>Pending Orders Limit</label><input type="text" name="customer_pending_order_limit" value="{{ old('customer_pending_order_limit', $customer->customer_pending_order_limit) }}"></div>
                    <div class="field">
                        <label>Add / Deduct Credit ($) <span style="font-weight:400;font-size:0.85em;color:#64748b;">— Current: ${{ number_format($depositBalance, 2) }}</span></label>
                        <input type="number" id="editCreditAmt" name="add_credit" step="0.01" placeholder="e.g. 25.00 or -10.00 to deduct">
                    </div>
                    <div class="field">
                        <label>Credit Note / Reference <span id="editCreditNoteRequired" style="color:#9d2d17;display:none;">*</span></label>
                        <input type="text" id="editCreditNote" name="add_credit_note" minlength="3" maxlength="500" placeholder="Required — e.g. Bank transfer, order #1234 email payment…">
                    </div>
                    <script>
                    (function(){
                        var amt=document.getElementById('editCreditAmt'),note=document.getElementById('editCreditNote'),star=document.getElementById('editCreditNoteRequired');
                        function sync(){var v=parseFloat(amt.value||'0');var has=v!==0&&!isNaN(v);note.required=has;star.style.display=has?'inline':'none';}
                        amt.addEventListener('input',sync);sync();
                    })();
                    </script>
                    <div class="field">
                        <label>Payment Terms</label>
                        <select name="payment_terms">
                            @foreach ([5, 7, 14] as $days)
                                <option value="{{ $days }}" @selected((string) old('payment_terms', $customer->payment_terms) === (string) $days)>{{ $days }} Days</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select name="is_active">
                            <option value="1" @selected((string) old('is_active', $customer->is_active) === '1')>Active</option>
                            <option value="0" @selected((string) old('is_active', $customer->is_active) === '0')>Blocked</option>
                        </select>
                    </div>
                    <div class="field"><label>Max Number of Stitches Override</label><input type="text" name="max_num_stiches" value="{{ old('max_num_stiches', $customer->max_num_stiches) }}" placeholder="Blank uses site pricing"></div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
                    <button type="submit">Save Customer</button>
                    <a class="badge" href="{{ url('/v/customer-detail.php?uid='.$customer->user_id.($source ? '&source='.rawurlencode($source) : '')) }}">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
