@extends('layouts.customer-guest')

@section('title', $siteContext->displayLabel().' Sign Up')

@section('content')
    <div class="container guest-shell">
        <section class="panel form-panel auth-panel">
            <h2>Member Sign Up</h2>
            <p class="muted">Complete the signup form below to create your customer account.</p>

            @if ($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ url('/sign-up.php') }}" data-validate-form novalidate>
                @csrf
                <section class="form-section">
                    <div class="section-heading">
                        <h3>Your Details</h3>
                        <p>Start with the essentials so we can create and verify your account.</p>
                    </div>

                    <div class="grid">
                        <label class="form-field" data-form-field>
                            <span class="field-label">First Name <span class="field-meta required" aria-hidden="true">*</span></span>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" required>
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                        <label class="form-field" data-form-field>
                            <span class="field-label">Last Name <span class="field-meta required" aria-hidden="true">*</span></span>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" required>
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                        <label class="form-field" data-form-field>
                            <span class="field-label">Email Address <span class="field-meta required" aria-hidden="true">*</span></span>
                            <input type="email" name="useremail" value="{{ old('useremail') }}" autocomplete="email" required>
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                        <label class="form-field" data-form-field>
                            <span class="field-label">Confirm Email Address <span class="field-meta required" aria-hidden="true">*</span></span>
                            <input type="email" name="confirmuseremail" value="{{ old('confirmuseremail') }}" autocomplete="off" required data-match="useremail" data-match-message="The confirm email address must match the email address.">
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                        <label class="form-field" data-form-field>
                            <span class="field-label">Password <span class="field-meta required" aria-hidden="true">*</span></span>
                            <input type="password" name="user_psw" minlength="6" autocomplete="new-password" required>
                            <span class="field-help">Use at least 6 characters.</span>
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                        <label class="form-field" data-form-field>
                            <span class="field-label">Confirm Password <span class="field-meta required" aria-hidden="true">*</span></span>
                            <input type="password" name="confirm_psw" minlength="6" autocomplete="new-password" required data-match="user_psw" data-match-message="The confirm password must match the password.">
                            <span class="field-help" aria-hidden="true">&nbsp;</span>
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                        <div class="form-field" data-form-field style="grid-column: 1 / -1">
                            <div style="display: flex; gap: 14px; align-items: flex-start;">
                                <div style="flex: 1; display: grid; gap: 8px;">
                                    <span class="field-label">Telephone <span class="field-meta required" aria-hidden="true">*</span></span>
                                    <input type="text" name="telephone_num" value="{{ old('telephone_num') }}" autocomplete="tel" inputmode="tel" required>
                                    <span class="field-error" data-field-error aria-live="polite"></span>
                                </div>
                                <div style="flex: 1; position: relative; display: grid; gap: 8px;">
                                    <span class="field-label">Country <span class="field-meta required" aria-hidden="true">*</span></span>
                                    <input type="search" name="selCountry" value="{{ old('selCountry', 'United States') }}" placeholder="Start typing or choose from the full list" autocomplete="country-name" required data-country-input data-country-strict data-country-options='@json($countries)'>
                                    <div class="country-results" data-country-results aria-label="Matching countries"></div>
                                    <span class="field-error" data-field-error aria-live="polite"></span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="package_type" value="BASIC">
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-heading">
                        <h3>Business Details</h3>
                    </div>

                    <div class="grid">
                        <label class="form-field" data-form-field>
                            <span class="field-label">Company Name</span>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" autocomplete="organization">
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                        <label class="form-field" data-form-field>
                            <span class="field-label">Company Type</span>
                            <select name="selCompanyTypes">
                                <option value="">Company Type</option>
                                @foreach ($companyTypes as $type)
                                    <option value="{{ $type }}" @selected(old('selCompanyTypes') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </label>
                    </div>

                    <label class="form-field" data-form-field>
                        <span class="field-label">Company Address</span>
                        <textarea name="company_address" autocomplete="street-address" rows="1" style="resize:vertical;min-height:38px;">{{ old('company_address') }}</textarea>
                        <span class="field-error" data-field-error aria-live="polite"></span>
                    </label>
                </section>

                <section class="form-section reg-plan-section">
                    <div class="section-heading">
                        <h3>Add a Plan <span class="reg-plan-optional">— Optional</span></h3>
                        <p>Start with discounted stitch credits or a monthly subscription. Skip it now and purchase anytime from your dashboard.</p>
                    </div>

                    {{-- Tab toggle --}}
                    <div class="reg-plan-tabs" role="group" aria-label="Plan type">
                        <button type="button" class="reg-plan-tab active" data-tab="none">Skip for Now</button>
                        <button type="button" class="reg-plan-tab" data-tab="credit">Credit Pack</button>
                        <button type="button" class="reg-plan-tab" data-tab="subscription">Subscription</button>
                    </div>

                    {{-- Hidden inputs sent with form --}}
                    <input type="hidden" name="signup_plan_type" id="regPlanType" value="none">
                    <input type="hidden" name="signup_plan_id"   id="regPlanId"   value="none">

                    {{-- Credit pack cards --}}
                    <div class="reg-plan-cards" id="regTabCredit" style="display:none">
                        @foreach ($signupPlans['credit'] as $pack)
                            <label class="reg-plan-card" data-plan-id="{{ $pack['id'] }}">
                                <input type="radio" name="_plan_credit_pick" value="{{ $pack['id'] }}" style="position:absolute;opacity:0;pointer-events:none">
                                <span class="reg-plan-card-discount">{{ $pack['discount'] }}</span>
                                <span class="reg-plan-card-name">{{ $pack['label'] }}</span>
                                <span class="reg-plan-card-stitches">{{ $pack['stitches'] }} stitches</span>
                                <span class="reg-plan-card-price">
                                    <s class="reg-plan-was">${{ number_format($pack['full_price']) }}</s>
                                    <strong>${{ $pack['price'] == floor($pack['price']) ? number_format($pack['price']) : number_format($pack['price'], 2) }}</strong>
                                </span>
                                <span class="reg-plan-card-rate">{{ $pack['per_k'] }} / 1K stitches</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Subscription plan cards --}}
                    <div class="reg-plan-cards" id="regTabSubscription" style="display:none">
                        @foreach ($signupPlans['subscription'] as $plan)
                            <label class="reg-plan-card" data-plan-id="{{ $plan['id'] }}">
                                <input type="radio" name="_plan_sub_pick" value="{{ $plan['id'] }}" style="position:absolute;opacity:0;pointer-events:none">
                                <span class="reg-plan-card-name">{{ $plan['label'] }}</span>
                                <span class="reg-plan-card-stitches">{{ number_format($plan['credits']) }} credits / month</span>
                                <span class="reg-plan-card-price">
                                    <strong>${{ $plan['price'] }}</strong><span class="reg-plan-unit">/mo</span>
                                </span>
                                <span class="reg-plan-card-rate">{{ $plan['turnaround'] }}</span>
                                <span class="reg-plan-card-rate" style="color:#3c9e6a;font-weight:600">{{ $plan['rate'] }} / 1K stitches</span>
                            </label>
                        @endforeach
                    </div>

                    <p id="regPlanNoneNote" class="reg-plan-skip-note" style="display:block">No plan selected — After email verification, your account will remain pending until it is approved by admin.</p>
                </section>

                <section class="form-section">
                    <label class="terms-row" data-form-field>
                        <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                        <span class="terms-copy">
                            <span class="terms-line"><span class="field-meta required" aria-hidden="true">*</span><span>I have read the <a href="{{ url('/terms.php') }}" target="_blank" rel="noopener">Terms &amp; Conditions</a> thoroughly, and I agree.</span></span>
                            <span class="field-error" data-field-error aria-live="polite"></span>
                        </span>
                    </label>
                </section>

                @include('shared.turnstile')

                <div class="actions">
                    <button type="submit">Create Account</button>
                    <a class="button secondary" href="{{ url('/login.php') }}">Already Have An Account?</a>
                </div>
            </form>
        </section>
    </div>
    <style>
        .reg-plan-optional { font-size: 0.85rem; font-weight: 400; color: #7a8fa6; }

        .reg-plan-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .reg-plan-tab {
            padding: 8px 18px;
            border-radius: 999px;
            border: 1.5px solid rgba(22,159,230,0.22);
            background: #fff;
            color: #526071;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }

        .reg-plan-tab.active {
            background: #169fe6;
            color: #fff;
            border-color: #169fe6;
        }

        .reg-plan-cards {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 4px 2px 12px;
            scrollbar-width: thin;
            scrollbar-color: rgba(22,159,230,0.25) transparent;
        }

        .reg-plan-card {
            flex: 0 0 160px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 14px 14px 12px;
            border-radius: 14px;
            border: 1.5px solid rgba(22,159,230,0.14);
            background: #fff;
            cursor: pointer;
            position: relative;
            box-shadow: 0 4px 14px rgba(12,48,89,0.06);
            transition: border-color 0.15s, box-shadow 0.15s;
            user-select: none;
        }

        .reg-plan-card:hover { border-color: rgba(22,159,230,0.40); }

        .reg-plan-card.selected {
            border-color: #169fe6;
            box-shadow: 0 0 0 3px rgba(22,159,230,0.15);
            background: linear-gradient(145deg, rgba(22,159,230,0.05) 0%, #fff 70%);
        }

        .reg-plan-card-discount {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #fff;
            background: #169fe6;
            padding: 2px 8px;
            border-radius: 999px;
            width: fit-content;
        }

        .reg-plan-card-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: #182a3e;
        }

        .reg-plan-card-stitches {
            font-size: 0.74rem;
            color: #7a8fa6;
        }

        .reg-plan-card-price {
            display: flex;
            align-items: baseline;
            gap: 5px;
            margin-top: 2px;
        }

        .reg-plan-card-price strong {
            font-size: 1.25rem;
            font-weight: 800;
            color: #169fe6;
        }

        .reg-plan-was {
            font-size: 0.78rem;
            color: #a0b0bf;
        }

        .reg-plan-unit {
            font-size: 0.75rem;
            color: #7a8fa6;
        }

        .reg-plan-card-rate {
            font-size: 0.73rem;
            color: #526071;
        }

        .reg-plan-skip-note {
            font-size: 0.82rem;
            color: #7a8fa6;
            margin-top: 4px;
        }
    </style>

    <script>
        (function () {
            var tabs      = document.querySelectorAll('.reg-plan-tab');
            var typeInput = document.getElementById('regPlanType');
            var idInput   = document.getElementById('regPlanId');
            var noneNote  = document.getElementById('regPlanNoneNote');
            var sections  = {
                credit:       document.getElementById('regTabCredit'),
                subscription: document.getElementById('regTabSubscription'),
            };

            function switchTab(tab) {
                tabs.forEach(function (t) { t.classList.toggle('active', t === tab); });
                var type = tab.getAttribute('data-tab');
                typeInput.value = type;

                Object.keys(sections).forEach(function (k) {
                    sections[k].style.display = (k === type) ? 'flex' : 'none';
                });

                noneNote.style.display = (type === 'none') ? '' : 'none';

                if (type === 'none') {
                    idInput.value = 'none';
                    clearSelections();
                }
            }

            function clearSelections() {
                document.querySelectorAll('.reg-plan-card').forEach(function (c) {
                    c.classList.remove('selected');
                });
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () { switchTab(tab); });
            });

            document.querySelectorAll('.reg-plan-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    var planType = typeInput.value;
                    var planId   = card.getAttribute('data-plan-id');

                    // Deselect others in the same group
                    var group = card.closest('.reg-plan-cards');
                    if (group) {
                        group.querySelectorAll('.reg-plan-card').forEach(function (c) {
                            c.classList.remove('selected');
                        });
                    }

                    card.classList.add('selected');
                    idInput.value   = planId;
                    typeInput.value = planType;

                    // Check the hidden radio for form compatibility
                    var radio = card.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;
                });
            });
        })();
    </script>

@endsection
