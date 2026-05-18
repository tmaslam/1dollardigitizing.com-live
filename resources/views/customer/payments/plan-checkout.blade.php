@extends('layouts.customer-guest')

@section('title', 'Complete Your Plan Purchase — '.$siteContext->displayLabel())

@section('content')
    <div class="container guest-shell">
        <section class="panel form-panel auth-panel plan-checkout-panel">

            @php
                $fromDashboard = request()->query('from') === 'dashboard';
                $type          = $plan['type'];
                $details       = $plan['plan'];
                $isCredit      = $type === 'credit';
                $isSub         = $type === 'subscription';
                $isCustom      = $type === 'custom';
                $planName      = $details['label'];
                $planPrice     = $details['price'];
            @endphp

            <div class="plan-checkout-header">
                @if ($fromDashboard)
                    <div class="plan-checkout-icon">💳</div>
                    <h2>{{ $isCustom ? 'Add Custom Funds' : 'Purchase a Plan' }}</h2>
                    <p class="muted">Contact us with your selection and we'll send a secure payment link within a few hours.</p>
                @else
                    <div class="plan-checkout-icon">🎉</div>
                    <h2>Email Verified — One Step Left</h2>
                    <p class="muted">Your account is ready. Complete the plan payment below to activate it and start placing orders.</p>
                @endif
            </div>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            {{-- Selected plan summary card --}}
            <div class="plan-summary-card">
                <div class="plan-summary-top">
                    <div>
                        @if ($isCustom)
                            <span class="plan-summary-type-badge">Custom Top-Up</span>
                            <h3 class="plan-summary-name">Account Fund Top-Up</h3>
                        @else
                            <span class="plan-summary-type-badge">{{ $isCredit ? 'Credit Pack' : 'Monthly Subscription' }}</span>
                            <h3 class="plan-summary-name">{{ $planName }}</h3>
                        @endif
                    </div>
                    <div class="plan-summary-price-block">
                        <span class="plan-summary-price">${{ $planPrice == floor($planPrice) ? number_format($planPrice) : number_format($planPrice, 2) }}</span>
                        @if ($isSub)<span class="plan-summary-price-unit">/month</span>@endif
                    </div>
                </div>

                <div class="plan-summary-details">
                    @if ($isCustom)
                        <div class="plan-detail-row">
                            <span>Top-up amount</span>
                            <strong>${{ number_format($planPrice, 2) }}</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Applied to</span>
                            <strong>Your account balance</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Usage</span>
                            <strong>Pay for any orders at $1 / 1K stitches</strong>
                        </div>
                    @elseif ($isCredit)
                        <div class="plan-detail-row">
                            <span>Stitch credits</span>
                            <strong>{{ $details['stitches'] }} stitches</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Full price</span>
                            <strong><s>${{ number_format($details['full_price']) }}</s></strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>You save</span>
                            <strong class="plan-saving">${{ number_format($details['saving'], 2) }} ({{ $details['discount'] }})</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Rate</span>
                            <strong>{{ $details['per_k'] }} per 1,000 stitches</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Expiry</span>
                            <strong>Credits never expire</strong>
                        </div>
                    @else
                        <div class="plan-detail-row">
                            <span>Monthly credits</span>
                            <strong>{{ number_format($details['credits']) }} credits ({{ number_format($details['credits'] * 1000) }} stitches)</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Turnaround</span>
                            <strong>{{ $details['turnaround'] }}</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Effective rate</span>
                            <strong>{{ $details['rate'] }} per 1,000 stitches</strong>
                        </div>
                        <div class="plan-detail-row">
                            <span>Billing</span>
                            <strong>Monthly — cancel anytime</strong>
                        </div>
                    @endif
                </div>

                <div class="plan-summary-total">
                    <span>Amount due now</span>
                    <strong>${{ $planPrice == floor($planPrice) ? number_format($planPrice) : number_format($planPrice, 2) }}{{ $isSub ? ' / month' : '' }}</strong>
                </div>
            </div>

            {{-- Payment methods --}}
            <div class="plan-pay-section">
                <h4 class="plan-pay-heading">How to Pay</h4>

                @if ($isCredit && !empty($details['payment_link']))
                    <p class="plan-pay-desc">Click the button below to pay securely with Stripe. We accept Visa, MasterCard, Amex, Discover, and PayPal.</p>

                    <div class="plan-pay-methods">
                        <a href="{{ url('/plan-checkout.php/pay') }}" class="plan-pay-method" style="background: linear-gradient(135deg, rgba(22,159,230,0.08) 0%, rgba(22,159,230,0.03) 100%); border-color: rgba(22,159,230,0.35);">
                            <span class="plan-pay-icon">💳</span>
                            <span>
                                <strong>Pay with Stripe</strong><br>
                                <small>Secure checkout — instant credit activation</small>
                            </span>
                        </a>
                        <a href="tel:+12063126446" class="plan-pay-method">
                            <span class="plan-pay-icon">📞</span>
                            <span>
                                <strong>Call Us</strong><br>
                                <small>{{ $siteContext->phoneNumber ?: '+1 (206) 312-6446' }} &nbsp;·&nbsp; Mon–Fri 9AM–6PM PST</small>
                            </span>
                        </a>
                    </div>
                @else
                    <p class="plan-pay-desc">Contact us with your selected plan and we'll send you a secure payment link. We accept Visa, MasterCard, Amex, Discover, and PayPal.</p>

                    <div class="plan-pay-methods">
                        <a href="tel:+12063126446" class="plan-pay-method">
                            <span class="plan-pay-icon">📞</span>
                            <span>
                                <strong>Call Us</strong><br>
                                <small>{{ $siteContext->phoneNumber ?: '+1 (206) 312-6446' }} &nbsp;·&nbsp; Mon–Fri 9AM–6PM PST</small>
                            </span>
                        </a>
                        <a href="{{ url('/contact-us.php') }}?plan={{ urlencode($planName) }}&amount={{ $planPrice }}" class="plan-pay-method">
                            <span class="plan-pay-icon">✉️</span>
                            <span>
                                <strong>Send a Message</strong><br>
                                <small>We'll reply with a payment link within a few hours</small>
                            </span>
                        </a>
                        @if ($siteContext->supportEmail)
                            <a href="mailto:{{ $siteContext->supportEmail }}?subject={{ urlencode('Plan Purchase: '.$planName) }}&body={{ urlencode('Hi, I\'d like to purchase the '.$planName.' plan ($'.$planPrice.').') }}" class="plan-pay-method">
                                <span class="plan-pay-icon">📧</span>
                                <span>
                                    <strong>Email Direct</strong><br>
                                    <small>{{ $siteContext->supportEmail }}</small>
                                </span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Change plan --}}
            <details class="plan-change-wrap">
                <summary>Want to choose a different plan?</summary>

                <div class="plan-change-body">
                    <p>Select a different option below — your choice will update automatically.</p>

                    <div class="plan-change-tabs">
                        <button type="button" class="plan-change-tab active" data-change-tab="credit">Credit Packs</button>
                        <button type="button" class="plan-change-tab" data-change-tab="subscription">Subscriptions</button>
                    </div>

                    <div class="plan-change-grid" id="changeTabCredit">
                        @foreach ($allPlans['credit'] as $pack)
                            <a href="{{ url('/plan-checkout.php') }}?switch={{ $pack['id'] }}" class="plan-change-card {{ isset($details['id']) && $details['id'] === $pack['id'] ? 'plan-change-card--active' : '' }}">
                                <strong>{{ $pack['label'] }}</strong>
                                <span>${{ $pack['price'] }} <s style="font-size:.75rem;color:#a0b0bf">${{ $pack['full_price'] }}</s></span>
                                <span style="font-size:.72rem;color:#3c9e6a">Save ${{ $pack['saving'] }}</span>
                            </a>
                        @endforeach
                    </div>

                    <div class="plan-change-grid" id="changeTabSubscription" style="display:none">
                        @foreach ($allPlans['subscription'] as $sub)
                            <a href="{{ url('/plan-checkout.php') }}?switch={{ $sub['id'] }}" class="plan-change-card {{ isset($details['id']) && $details['id'] === $sub['id'] ? 'plan-change-card--active' : '' }}">
                                <strong>{{ $sub['label'] }}</strong>
                                <span>${{ $sub['price'] }}/mo</span>
                                <span style="font-size:.72rem;color:#526071">{{ $sub['credits'] }} credits · {{ $sub['rate'] }}/1K</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </details>

            <div class="plan-skip-wrap">
                @if ($fromDashboard)
                    <p class="muted" style="text-align:center;"><a href="{{ url('/dashboard.php') }}" class="inline-link">← Back to Dashboard</a></p>
                @else
                    <p class="muted" style="text-align:center;">Your account will be activated once your plan payment is confirmed. If you prefer not to purchase a plan now, an admin can activate your account manually.</p>
                @endif
            </div>

        </section>
    </div>

    <style>
        .plan-checkout-panel { max-width: 600px; }

        .plan-checkout-header { text-align: center; margin-bottom: 24px; }
        .plan-checkout-icon   { font-size: 2.4rem; margin-bottom: 8px; }
        .plan-checkout-header h2 { margin: 0 0 6px; }

        .plan-summary-card {
            border: 1.5px solid rgba(22,159,230,0.18);
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 22px;
            box-shadow: 0 8px 24px rgba(12,48,89,0.08);
        }

        .plan-summary-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 18px 20px 14px;
            background: linear-gradient(135deg, rgba(22,159,230,0.06) 0%, rgba(255,255,255,0) 100%);
            border-bottom: 1px solid rgba(22,159,230,0.10);
        }

        .plan-summary-type-badge {
            display: inline-block;
            font-size: 0.70rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #0d6ea3;
            background: rgba(22,159,230,0.10);
            padding: 3px 10px;
            border-radius: 999px;
            margin-bottom: 6px;
        }

        .plan-summary-name {
            margin: 0;
            font-size: 1.2rem;
            color: #182a3e;
        }

        .plan-summary-price-block { text-align: right; }
        .plan-summary-price {
            font-size: 2rem;
            font-weight: 800;
            color: #169fe6;
            line-height: 1;
        }
        .plan-summary-price-unit { font-size: 0.82rem; color: #7a8fa6; display: block; text-align: right; }

        .plan-summary-details {
            padding: 14px 20px;
            display: grid;
            gap: 8px;
        }

        .plan-detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #526071;
        }
        .plan-detail-row strong { color: #182a3e; }
        .plan-saving { color: #3c9e6a !important; }

        .plan-summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            background: rgba(22,159,230,0.05);
            border-top: 1px solid rgba(22,159,230,0.10);
            font-size: 0.9rem;
            font-weight: 600;
            color: #182a3e;
        }
        .plan-summary-total strong { font-size: 1.15rem; color: #169fe6; }

        .plan-pay-section { margin-bottom: 24px; }
        .plan-pay-heading { margin: 0 0 6px; font-size: 1rem; color: #182a3e; }
        .plan-pay-desc { font-size: 0.84rem; color: #526071; margin: 0 0 14px; }

        .plan-pay-methods { display: grid; gap: 10px; }

        .plan-pay-method {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1.5px solid rgba(22,159,230,0.14);
            background: #fff;
            text-decoration: none;
            color: #182a3e;
            box-shadow: 0 4px 12px rgba(12,48,89,0.05);
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .plan-pay-method:hover { border-color: #169fe6; box-shadow: 0 6px 18px rgba(22,159,230,0.12); }

        .plan-pay-icon { font-size: 1.4rem; flex-shrink: 0; }
        .plan-pay-method strong { font-size: 0.9rem; }
        .plan-pay-method small { font-size: 0.78rem; color: #7a8fa6; }

        .plan-change-wrap {
            border: 1px solid rgba(22,159,230,0.12);
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .plan-change-wrap summary {
            padding: 12px 16px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            color: #169fe6;
            background: rgba(22,159,230,0.03);
            list-style: none;
        }
        .plan-change-wrap summary::-webkit-details-marker { display: none; }
        .plan-change-body { padding: 14px 16px 16px; }
        .plan-change-body p { font-size: 0.82rem; color: #526071; margin: 0 0 12px; }

        .plan-change-tabs { display: flex; gap: 8px; margin-bottom: 12px; }
        .plan-change-tab {
            padding: 6px 14px;
            border-radius: 999px;
            border: 1.5px solid rgba(22,159,230,0.20);
            background: #fff;
            font-size: 0.80rem;
            font-weight: 600;
            color: #526071;
            cursor: pointer;
        }
        .plan-change-tab.active { background: #169fe6; color: #fff; border-color: #169fe6; }

        .plan-change-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .plan-change-card {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1.5px solid rgba(22,159,230,0.12);
            background: #fff;
            text-decoration: none;
            font-size: 0.82rem;
            color: #182a3e;
            transition: border-color 0.15s;
        }
        .plan-change-card:hover { border-color: #169fe6; }
        .plan-change-card--active { border-color: #169fe6; background: rgba(22,159,230,0.05); }

        .plan-skip-wrap { text-align: center; }
        .plan-skip-link { font-size: 0.83rem; color: #7a8fa6; text-decoration: none; }
        .plan-skip-link:hover { color: #169fe6; }
    </style>

    <script>
        (function () {
            var changeTabs = document.querySelectorAll('.plan-change-tab');
            changeTabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    changeTabs.forEach(function (t) { t.classList.remove('active'); });
                    tab.classList.add('active');
                    var which = tab.getAttribute('data-change-tab');
                    document.getElementById('changeTabCredit').style.display       = (which === 'credit')       ? 'grid' : 'none';
                    document.getElementById('changeTabSubscription').style.display = (which === 'subscription') ? 'grid' : 'none';
                });
            });

            // Handle ?switch=plan-id for plan switching
            var params = new URLSearchParams(window.location.search);
            var switchId = params.get('switch');
            if (switchId && switchId.startsWith('sub-')) {
                var subTab = document.querySelector('[data-change-tab="subscription"]');
                if (subTab) subTab.click();
            }
        })();
    </script>
@endsection
