@extends('layouts.customer')

@section('title', 'My Profile - '.$siteContext->displayLabel())
@section('hero_title', 'My Profile')
@section('hero_text', 'Keep your contact details current so quotes, orders, invoices, and delivery updates reach the right person every time.')

@section('content')
    @php
        $totalUsable   = $accountSummary['deposit_balance'];
        $totalKstitches = (int) floor($totalUsable * 1000);
        $subPlan       = trim((string) ($customer->subscription_plan ?? ''));
        $subLabel      = match($subPlan) {
            'growth'     => 'Starter',
            'studio'     => 'Growth',
            'production' => 'Studio',
            'enterprise' => 'Production',
            'corporate'  => 'Enterprise',
            default      => '',
        };
    @endphp

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Account Details</h3>
                <p>Your credit balance, subscription plan, and account status.</p>
            </div>
        </div>

        {{-- Credit balance hero --}}
        <div class="dash-credit-hero">
            <div class="dash-credit-main">
                <div class="dash-credit-amount">{{ number_format($totalUsable, 2) }} <span class="dash-credit-unit">credits</span></div>
                <div class="dash-credit-stitches">≈ {{ number_format($totalKstitches) }} stitches available</div>
                <div class="dash-credit-rate">1 Credit = 1 USD = 1,000 stitches</div>
                @if ($subLabel)
                    <span class="dash-sub-badge">{{ $subLabel }} Subscriber</span>
                @endif
            </div>
            <div class="dash-credit-breakdown">
                <div class="dash-credit-row dash-credit-row--total">
                    <span>Credit Balance</span>
                    <strong>{{ number_format($totalUsable, 2) }} cr</strong>
                </div>
                @php
                    $paymentDue = max(0, round($accountSummary['billing_total'] - $totalUsable, 2));
                @endphp
                <div class="dash-credit-row" style="margin-top:6px; border-top:1px solid rgba(22,159,230,0.10); padding-top:8px;">
                    <span>Payment due</span>
                    <strong style="color:{{ $paymentDue > 0 ? '#e07b20' : '#3c9e6a' }}">
                        ${{ number_format($paymentDue, 2) }}
                    </strong>
                </div>
            </div>
        </div>

        @if ($subLabel)
            <div class="dash-sub-info">
                <span class="dash-sub-info-label">Subscription Plan</span>
                <span class="dash-sub-info-name">{{ $subLabel }}</span>
                @php
                    $subPerks = match($subPlan) {
                        'growth'     => ['24-hour standard turnaround'],
                        'studio'     => ['24-hour standard turnaround'],
                        'production' => ['12-hour priority turnaround', 'Flash Rush (4-hour) unlocked', 'Rates billed at next tier down'],
                        'enterprise' => ['12-hour priority turnaround', 'Flash Rush (4-hour) unlocked', 'Rates billed at next tier down'],
                        'corporate'  => ['8-hour super rush turnaround', 'Flash Rush (4-hour) unlocked', 'Super Rush billed at standard rate', 'Custom SLA available'],
                        default      => [],
                    };
                @endphp
                @if ($subPerks)
                    <ul class="dash-sub-perks">
                        @foreach ($subPerks as $perk)
                            <li>{{ $perk }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
    </section>
    <style>
        /* Credit hero block */
        .dash-credit-hero {
            display: flex;
            gap: 24px;
            align-items: flex-start;
            flex-wrap: wrap;
            background: linear-gradient(135deg, rgba(22,159,230,0.06) 0%, rgba(60,158,106,0.04) 100%);
            border: 1.5px solid rgba(22,159,230,0.14);
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 20px;
        }
        .dash-credit-main { flex: 1; min-width: 160px; }
        .dash-credit-amount {
            font-size: 2.4rem;
            font-weight: 800;
            color: #169fe6;
            line-height: 1;
        }
        .dash-credit-unit { font-size: 1rem; font-weight: 600; color: #7a8fa6; }
        .dash-credit-stitches { font-size: 0.84rem; color: #526071; margin-top: 5px; }
        .dash-credit-rate { font-size: 0.75rem; color: #9ab0c4; margin-top: 3px; }
        .dash-sub-badge {
            display: inline-block;
            margin-top: 10px;
            background: #169fe6;
            color: #fff;
            font-size: 0.70rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 3px 12px;
            border-radius: 999px;
        }
        .dash-credit-breakdown {
            flex: 1;
            min-width: 200px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .dash-credit-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.84rem;
            color: #526071;
        }
        .dash-credit-row strong { color: #182a3e; }
        .dash-credit-row--total {
            font-weight: 700;
            color: #182a3e;
            border-top: 1px solid rgba(22,159,230,0.14);
            padding-top: 6px;
            margin-top: 2px;
        }
        .dash-credit-row--total strong { color: #169fe6; }

        /* Subscription info */
        .dash-sub-info {
            background: rgba(22,159,230,0.04);
            border: 1px solid rgba(22,159,230,0.12);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .dash-sub-info-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: #8fa0b4;
        }
        .dash-sub-info-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #182a3e;
        }
        .dash-sub-perks {
            margin: 4px 0 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .dash-sub-perks li {
            font-size: 0.82rem;
            color: #526071;
            padding-left: 16px;
            position: relative;
        }
        .dash-sub-perks li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #3c9e6a;
            font-weight: 700;
        }
    </style>

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Personal Information</h3>
                <p>Email changes are handled by support so your account stays secure.</p>
            </div>
        </div>

        <form method="post" action="{{ url('/my-profile.php') }}" class="stack" data-form-validation novalidate>
            @csrf
            <div class="form-grid">
                <label>
                    <span class="field-label">Email Address</span>
                    <input type="email" value="{{ $customer->user_email }}" disabled>
                    <span class="field-help">For security, email changes are handled by support.</span>
                </label>
                <label>
                    <span class="field-label">User Name</span>
                    <input type="text" value="{{ $customer->user_name }}" disabled>
                    <span class="field-help">Your login name stays tied to this account.</span>
                </label>
                <label>
                    <span class="field-label">First Name <span class="field-meta required" aria-hidden="true">*</span></span>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" autocomplete="given-name" required maxlength="100">
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">Last Name <span class="field-meta required" aria-hidden="true">*</span></span>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" autocomplete="family-name" required maxlength="100">
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">Company</span>
                    <input type="text" name="company" value="{{ old('company', $customer->company) }}" autocomplete="organization" maxlength="150">
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">Company Type</span>
                    <select name="company_type">
                        <option value="">Please Select</option>
                        @foreach ($companyTypes as $type)
                            <option value="{{ $type }}" @selected(old('company_type', $customer->company_type) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                    <span class="field-error" data-field-error></span>
                </label>
                <label style="grid-column: 1 / -1;">
                    <span class="field-label">Address</span>
                    <textarea name="company_address" autocomplete="street-address" maxlength="500">{{ old('company_address', $customer->company_address) }}</textarea>
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">Zip Code</span>
                    <input type="text" name="zip_code" value="{{ old('zip_code', $customer->zip_code) }}" autocomplete="postal-code" maxlength="30">
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">City</span>
                    <input type="text" name="user_city" value="{{ old('user_city', $customer->user_city) }}" autocomplete="address-level2" maxlength="120">
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">Country <span class="field-meta required" aria-hidden="true">*</span></span>
                    <select name="user_country" autocomplete="country-name" required>
                        <option value="">Please Select</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country }}" @selected(old('user_country', $customer->user_country) === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">Phone <span class="field-meta required" aria-hidden="true">*</span></span>
                    <input type="text" name="user_phone" value="{{ old('user_phone', $customer->user_phone) }}" autocomplete="tel" inputmode="tel" required maxlength="50">
                    <span class="field-error" data-field-error></span>
                </label>
            </div>
            <div>
                <button type="submit">Save Profile</button>
            </div>
        </form>
    </section>

    <section class="content-card single-column">
        <div class="section-head">
            <div>
                <h3>Two-Factor Authentication</h3>
                <p>When enabled, you will be asked to enter a one-time code emailed to your registered address each time you sign in. This adds a second layer of protection to your account.</p>
            </div>
            @if ((int) ($customer->two_factor_enabled ?? 0) === 1)
                <span class="status success" style="align-self:flex-start;">Enabled</span>
            @else
                <span class="status warning" style="align-self:flex-start;">Disabled</span>
            @endif
        </div>

        @if (session('success') && str_contains(session('success'), 'two-factor'))
            <div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
        @endif

        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
            @if ((int) ($customer->two_factor_enabled ?? 0) === 1)
                <form method="post" action="{{ url('/my-profile/2fa') }}" onsubmit="return confirm('Are you sure you want to disable two-factor authentication? Your account will be less secure.');">
                    @csrf
                    <input type="hidden" name="action" value="disable">
                    <button type="submit" class="secondary">Disable Two-Factor Authentication</button>
                </form>
            @else
                <form method="post" action="{{ url('/my-profile/2fa') }}">
                    @csrf
                    <input type="hidden" name="action" value="enable">
                    <button type="submit">Enable Two-Factor Authentication</button>
                </form>
            @endif
        </div>
    </section>

    <section class="content-card single-column">
        <div class="section-head">
            <div>
                <h3>Change Password</h3>
                <p>Use your current password to set a new one for your account.</p>
            </div>
        </div>

        <form method="post" action="{{ url('/my-profile/password') }}" class="stack" data-form-validation novalidate>
            @csrf
            <div class="form-grid">
                <label>
                    <span class="field-label">Current Password <span class="field-meta required" aria-hidden="true">*</span></span>
                    <input type="password" name="current_password" autocomplete="current-password" required>
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">New Password <span class="field-meta required" aria-hidden="true">*</span></span>
                    <input type="password" name="new_password" autocomplete="new-password" minlength="6" required>
                    <span class="field-help">Use at least 6 characters for this account password.</span>
                    <span class="field-error" data-field-error></span>
                </label>
                <label>
                    <span class="field-label">Confirm New Password <span class="field-meta required" aria-hidden="true">*</span></span>
                    <input type="password" name="new_password_confirmation" autocomplete="new-password" minlength="6" required data-match="new_password" data-match-message="The confirm password must match the new password.">
                    <span class="field-help">&nbsp;</span>
                    <span class="field-error" data-field-error></span>
                </label>
            </div>
            <div>
                <button type="submit" class="secondary">Update Password</button>
            </div>
        </form>
    </section>
@endsection
