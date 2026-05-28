@extends('layouts.customer')

@section('title', 'Dashboard - '.$siteContext->displayLabel())
@section('hero_class', 'hero-compact dashboard-hero')
@section('hero_title', 'Dashboard')
@section('hero_text', 'Track your orders, quotes, billing, downloads, and account details in one streamlined workspace.')

@section('content')
    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Dashboard</h3>
                <p>View your orders, quotes, billing, and account details in one place.</p>
            </div>
        </div>

        @php
            $totalUsable   = $metrics['deposit_balance'];
            $totalKstitches = (int) floor($totalUsable * 1000);
            $subPlan       = trim((string) ($customer->subscription_plan ?? ''));
            $subLabel      = match($subPlan) {
                'growth'     => 'Starter',
                'studio'     => 'Growth',
                'production' => 'Studio',
                'enterprise' => 'Production',
                'corporate'  => 'Enterprise',
                default      => null,
            };
        @endphp

        <div class="portal-stat-grid" style="margin-top:18px;">
            <a class="metric-link" href="{{ url('/view-orders.php') }}">
                <article class="portal-stat">
                    <span>My Orders</span>
                    <strong>{{ $metrics['orders'] }}</strong>
                </article>
            </a>
            <a class="metric-link" href="{{ url('/view-quotes.php') }}">
                <article class="portal-stat">
                    <span>My Quotes</span>
                    <strong>{{ $metrics['quotes'] }}</strong>
                </article>
            </a>
            <a class="metric-link" href="{{ url('/view-billing.php') }}">
                <article class="portal-stat">
                    <span>Payment Due</span>
                    <strong>${{ number_format($metrics['billing_total'], 2) }}</strong>
                </article>
            </a>
            <a class="metric-link portal-stat--credit" href="{{ url('/credit-activity.php') }}">
                <article class="portal-stat">
                    <span>Credit Balance</span>
                    <strong>{{ number_format($totalUsable, 2) }} cr</strong>
                    <small>≈ {{ number_format($totalKstitches) }} stitches</small>
                </article>
            </a>
        </div>
    </section>

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Quick Actions</h3>
                <p>Jump into the task you need most without hunting through the portal.</p>
            </div>
        </div>

        <div class="action-grid">
            <a class="action-card" href="{{ url('/new-order.php') }}">
                <span>Digitizing</span>
                <strong>Place New Order</strong>
                <p>Upload artwork and start a standard digitizing request.</p>
            </a>
            <a class="action-card" href="{{ url('/quote.php') }}">
                <span>Quote</span>
                <strong>Digitizing Quote</strong>
                <p>Ask for digitizing pricing first before placing a new order.</p>
            </a>
            <a class="action-card" href="{{ url('/vector-order.php') }}">
                <span>Vector</span>
                <strong>Place Vector Order</strong>
                <p>Start a vector-only job with the existing vector order flow.</p>
            </a>
            <a class="action-card" href="{{ url('/vector-quote.php') }}">
                <span>Vector Quote</span>
                <strong>Request Vector Quote</strong>
                <p>Ask for vector pricing first before placing a vector order.</p>
            </a>
        </div>
    </section>

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Recent Activity</h3>
                <p>Pick up where you left off without scanning every list page.</p>
            </div>
        </div>

        <div class="workspace-grid">
            <div class="activity-card">
                <span class="activity-kicker">Latest Orders</span>
                <div class="activity-list" style="margin-top:12px;">
                    @if ($recentOrders->isEmpty())
                        <div class="empty-state">No active orders are open right now.</div>
                    @else
                        @foreach ($recentOrders as $order)
                            <div class="activity-item">
                                <div class="activity-meta">
                                    <strong><a class="inline-link" href="{{ url('/view-order-detail.php?order_id=' . $order->order_id . '&origin=orders') }}">{{ $order->design_name ?: 'Order #'.$order->order_id }}</a></strong>
                                    <span class="status {{ \App\Support\CustomerWorkflowStatus::tone($order) }}">{{ \App\Support\CustomerWorkflowStatus::label($order) }}</span>
                                </div>
                                <div class="file-actions">
                                    <a class="button secondary" href="{{ url('/view-order-detail.php?order_id=' . $order->order_id . '&origin=orders') }}">Open Order</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="activity-card">
                <span class="activity-kicker">Quotes & Billing</span>
                <div class="activity-list" style="margin-top:12px;">
                    @if ($recentQuotes->isEmpty())
                        <div class="activity-item">
                            <strong>No open quotes</strong>
                            <p>You can request pricing first whenever you need a review before ordering.</p>
                        </div>
                    @else
                        @foreach ($recentQuotes as $quote)
                            <div class="activity-item">
                                <div class="activity-meta">
                                    <strong><a class="inline-link" href="{{ url('/view-quote-detail.php?order_id=' . $quote->order_id . '&origin=quotes') }}">{{ $quote->design_name ?: 'Quote #'.$quote->order_id }}</a></strong>
                                    <span class="status {{ \App\Support\CustomerWorkflowStatus::tone($quote, true) }}">{{ \App\Support\CustomerWorkflowStatus::label($quote, true) }}</span>
                                </div>
                                <div class="file-actions">
                                    <a class="button secondary" href="{{ url('/view-quote-detail.php?order_id=' . $quote->order_id . '&origin=quotes') }}">Open Quote</a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if ($recentBilling->isNotEmpty())
                        <div class="activity-item">
                            <div class="activity-meta">
                                <strong>${{ number_format($metrics['billing_total'], 2) }} outstanding</strong>
                                <span class="status warning">{{ $metrics['billing_count'] }} due</span>
                            </div>
                            <div class="file-actions">
                                <a class="button secondary" href="{{ url('/view-billing.php') }}">Open Billing</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @php
        $dashPlans = \App\Http\Controllers\CustomerRegistrationController::signupPlans();
    @endphp

    <section class="content-card dp-plans-card" id="credits-plans">
        <div class="section-head dp-collapse-toggle" id="dpToggle" role="button" tabindex="0" aria-expanded="false" aria-controls="dpPlansBody">
            <div>
                <h3 style="color:#9a7a10;">Credits &amp; Plans</h3>
                <p>Buy a credit pack, subscribe monthly, or top up your account with a custom amount.</p>
            </div>
            <span class="dp-chevron" aria-hidden="true">&#8964;</span>
        </div>

        <div id="dpPlansBody" style="display:none">

        {{-- Tab switcher --}}
        <div class="dp-tabs">
            <button type="button" class="dp-tab active" data-dp-tab="custom">Custom Amount</button>
            <button type="button" class="dp-tab" data-dp-tab="credit">Credit Packs</button>
            <button type="button" class="dp-tab" data-dp-tab="subscription">Subscriptions</button>
        </div>

        <form method="post" action="{{ url('/select-plan.php') }}" id="dpPlanForm">
            @csrf
            <input type="hidden" name="plan_type" id="dpPlanType" value="custom">
            <input type="hidden" name="plan_id"   id="dpPlanId"   value="">

            {{-- Credit pack cards --}}
            <div id="dpTabCredit" class="dp-cards-wrap" style="display:none">
                @foreach ($dashPlans['credit'] as $pack)
                    <label class="dp-card" data-dp-id="{{ $pack['id'] }}" data-dp-tab-group="credit">
                        <input type="radio" name="_dp_credit" value="{{ $pack['id'] }}" style="position:absolute;opacity:0;pointer-events:none">
                        <span class="dp-card-badge">{{ $pack['discount'] }}</span>
                        <span class="dp-card-name">{{ $pack['label'] }}</span>
                        <span class="dp-card-sub">{{ $pack['stitches'] }} stitches</span>
                        <span class="dp-card-price">
                            <s class="dp-was">${{ number_format($pack['full_price']) }}</s>
                            <strong>${{ $pack['price'] == floor($pack['price']) ? number_format($pack['price']) : number_format($pack['price'], 2) }}</strong>
                        </span>
                        <span class="dp-card-rate">{{ $pack['per_k'] }} / 1K stitches</span>
                    </label>
                @endforeach
            </div>

            {{-- Subscription cards --}}
            <div id="dpTabSubscription" class="dp-cards-wrap" style="display:none">
                @foreach ($dashPlans['subscription'] as $plan)
                    @php
                        $isActiveSub = $subPlan && $plan['id'] === 'sub-' . $subPlan;
                    @endphp
                    <label class="dp-card {{ $isActiveSub ? 'dp-card--active' : '' }}" data-dp-id="{{ $plan['id'] }}" data-dp-tab-group="subscription" {!! $isActiveSub ? 'style="pointer-events:none"' : '' !!}>
                        <input type="radio" name="_dp_sub" value="{{ $plan['id'] }}" style="position:absolute;opacity:0;pointer-events:none" @disabled($isActiveSub)>
                        @if ($isActiveSub)
                            <span class="dp-card-badge dp-card-badge--active">Active</span>
                        @endif
                        <span class="dp-card-name">{{ $plan['label'] }}</span>
                        <span class="dp-card-sub">{{ number_format($plan['credits']) }} credits / mo</span>
                        <span class="dp-card-price">
                            <strong>${{ $plan['price'] }}</strong><span class="dp-unit">/mo</span>
                        </span>
                        <span class="dp-card-rate">{{ $plan['turnaround'] }}</span>
                        <span class="dp-card-rate" style="color:#3c9e6a;font-weight:600">{{ $plan['rate'] }} / 1K stitches</span>
                    </label>
                @endforeach
            </div>

            {{-- Custom payment --}}
            <div id="dpTabCustom" class="dp-cards-wrap" style="display:block">
                <label class="dp-card" data-dp-id="custom" data-dp-tab-group="custom" style="gap:10px;">
                    <input type="radio" name="_dp_custom" value="custom" style="position:absolute;opacity:0;pointer-events:none">
                    <span class="dp-card-name">Custom Payment</span>
                    <span class="dp-card-sub">Top up any amount — $1 / 1K stitches</span>
                    <div id="dpCustomAmountWrap" style="display:flex;align-items:center;gap:6px;margin-top:4px;width:100%;">
                        <span style="font-size:1.15rem;font-weight:700;color:#333;">$</span>
                        <input
                            type="number"
                            id="dpCustomAmount"
                            name="custom_amount"
                            min="10"
                            max="50000"
                            step="1"
                            placeholder="Enter amount"
                            onclick="event.stopPropagation()"
                            style="flex:1;font-size:1.1rem;font-weight:600;border:1.5px solid #d0d5dd;border-radius:8px;padding:8px 12px;outline:none;max-width:200px;"
                        >
                    </div>
                    <span class="dp-card-rate" style="margin-top:2px;">Minimum $10</span>
                </label>
            </div>


            <div class="dp-action-row">
                <button type="submit" class="button dp-proceed-btn" id="dpProceedBtn" disabled>
                    Proceed to Payment →
                </button>
                <span class="dp-action-note" id="dpActionNote">Select a plan above to continue</span>
            </div>
        </form>
        </div>{{-- /dpPlansBody --}}
    </section>

    <section class="content-card">
        <div class="section-head">
            <div>
                <h3>Account Details</h3>
                <p>Your credit balance, subscription plan, and account status.</p>
            </div>
        </div>

        @if (!empty($placement['warning']))
            <div class="alert {{ $placement['can_place'] ? 'alert-success' : 'alert-error' }}" style="margin-bottom:16px;">
                {{ $placement['warning'] }}
                @if (! $placement['can_place'])
                    <div style="margin-top:10px;">
                        <a href="{{ url('/dashboard.php#credits-plans') }}" style="display:inline-flex;align-items:center;padding:8px 18px;border-radius:999px;background:rgba(212,175,55,0.12);color:#9a7a10;border:1px solid rgba(212,175,55,0.35);font-weight:700;font-size:0.84rem;text-decoration:none;">Buy Credits</a>
                    </div>
                @endif
            </div>
        @endif

        @if (session('subscription_request_success'))
            <div class="alert alert-success" style="margin-bottom:16px;">{{ session('subscription_request_success') }}</div>
        @endif

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
                    $paymentDue = max(0, round($metrics['billing_total'] - $totalUsable, 2));
                    $subRenewsAt = $customer->subscription_renews_at ?? null;
                @endphp
                <div class="dash-credit-row" style="margin-top:6px; border-top:1px solid rgba(22,159,230,0.10); padding-top:8px;">
                    <span>Payment due</span>
                    <strong style="color:{{ $paymentDue > 0 ? '#e07b20' : '#3c9e6a' }}">
                        ${{ number_format($paymentDue, 2) }}
                    </strong>
                </div>
                @if ($subLabel)
                    <div class="dash-credit-row dash-credit-row--sub" style="margin-top:6px; border-top:1px solid rgba(22,159,230,0.10); padding-top:8px;">
                        <span>Subscription</span>
                        <strong>{{ $subLabel }}</strong>
                    </div>
                    @if ($subRenewsAt)
                        <div class="dash-credit-row dash-credit-row--sub">
                            <span>Next recurring payment</span>
                            <strong>{{ \Carbon\Carbon::parse($subRenewsAt)->format('M d, Y') }}</strong>
                        </div>
                    @endif
                    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;padding-top:10px;border-top:1px solid rgba(22,159,230,0.10);">
                        <form method="post" action="{{ url('/subscription/pause-request') }}" onsubmit="return confirm('Are you sure you want to pause your subscription? Our team will contact you to process the pause.');">
                            @csrf
                            <button type="submit" style="padding:3px 10px;border-radius:999px;font-size:0.68rem;font-weight:700;background:linear-gradient(135deg,#d97706,#b45309);color:#fff;border:none;cursor:pointer;line-height:1.5;">Pause</button>
                        </form>
                        <form method="post" action="{{ url('/subscription/cancel-request') }}" onsubmit="return confirm('Are you sure you want to cancel your subscription? This action will notify our team to process your cancellation.');">
                            @csrf
                            <button type="submit" style="padding:3px 10px;border-radius:999px;font-size:0.68rem;font-weight:700;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;border:none;cursor:pointer;line-height:1.5;">Cancel</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>


    </section>
    <style>
        /* Equal-height stat cards */
        .portal-stat-grid { align-items: stretch; }
        .portal-stat-grid .metric-link,
        .portal-stat-grid .portal-stat--credit { display: flex; flex-direction: column; height: 100%; }
        .portal-stat-grid .portal-stat { flex: 1; display: flex; flex-direction: column; justify-content: center; }

        /* Credit stat card */
        .portal-stat--credit {
            cursor: pointer;
            border-radius: inherit;
        }
        .portal-stat--credit .portal-stat {
            background: linear-gradient(135deg, rgba(22,159,230,0.07) 0%, rgba(60,158,106,0.05) 100%);
            border-color: rgba(22,159,230,0.22);
        }
        .portal-stat--credit .portal-stat strong { color: #169fe6; }
        .portal-stat--credit .portal-stat small {
            display: block;
            font-size: 0.73rem;
            color: #7a8fa6;
            font-weight: 500;
            margin-top: 2px;
        }

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
        .dash-credit-main {
            flex: 1;
            min-width: 160px;
        }
        .dash-credit-amount {
            font-size: 2.4rem;
            font-weight: 800;
            color: #169fe6;
            line-height: 1;
        }
        .dash-credit-unit {
            font-size: 1rem;
            font-weight: 600;
            color: #7a8fa6;
        }
        .dash-credit-stitches {
            font-size: 0.84rem;
            color: #526071;
            margin-top: 5px;
        }
        .dash-credit-rate {
            font-size: 0.75rem;
            color: #9ab0c4;
            margin-top: 3px;
        }
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
        .dash-credit-row--sub strong { color: #7a5ec5; }

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

        .dp-collapse-toggle {
            width: 100%; background: none; border: none; padding: 0;
            cursor: pointer; display: flex;
            align-items: flex-start; justify-content: space-between; gap: 12px;
        }
        .dp-collapse-toggle:focus-visible { outline: 2px solid #169fe6; border-radius: 6px; }
        .dp-chevron {
            font-size: 1.3rem; color: #169fe6; flex-shrink: 0;
            margin-top: 4px; transition: transform 0.2s;
        }
        .dp-collapse-toggle[aria-expanded="true"] .dp-chevron { transform: rotate(180deg); }
        #dpPlansBody { margin-top: 18px; }

        /* Tab switcher */
        .dp-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
        .dp-tab {
            padding: 7px 18px;
            border-radius: 999px;
            border: 1.5px solid rgba(22,159,230,0.22);
            background: #fff;
            color: #526071;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, color .15s, border-color .15s;
        }
        .dp-tab.active { background: #169fe6; color: #fff; border-color: #169fe6; }

        /* Cards scroll row */
        .dp-cards-wrap {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 4px 2px 14px;
            scrollbar-width: thin;
            scrollbar-color: rgba(22,159,230,0.25) transparent;
        }

        /* Individual plan card */
        .dp-card {
            flex: 0 0 152px;
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
            transition: border-color .15s, box-shadow .15s;
            user-select: none;
        }
        .dp-card:hover { border-color: rgba(22,159,230,0.38); }
        .dp-card.selected {
            border-color: #169fe6;
            box-shadow: 0 0 0 3px rgba(22,159,230,0.14);
            background: linear-gradient(145deg, rgba(22,159,230,0.05) 0%, #fff 70%);
        }
        .dp-card--active {
            background: linear-gradient(145deg, rgba(22,159,230,0.12) 0%, rgba(60,158,106,0.08) 100%);
            border-color: #169fe6;
            box-shadow: 0 0 0 3px rgba(22,159,230,0.18);
            pointer-events: none;
            cursor: default;
        }
        .dp-card-badge--active {
            background: #3c9e6a;
        }

        .dp-card-badge {
            display: inline-block;
            font-size: 0.67rem;
            font-weight: 700;
            color: #fff;
            background: #169fe6;
            padding: 2px 8px;
            border-radius: 999px;
            width: fit-content;
        }
        .dp-card-name { font-size: 0.92rem; font-weight: 700; color: #182a3e; }
        .dp-card-sub  { font-size: 0.73rem; color: #7a8fa6; }
        .dp-card-price {
            display: flex; align-items: baseline; gap: 5px; margin-top: 3px;
        }
        .dp-card-price strong { font-size: 1.22rem; font-weight: 800; color: #169fe6; }
        .dp-was   { font-size: 0.75rem; color: #a0b0bf; }
        .dp-unit  { font-size: 0.73rem; color: #7a8fa6; }
        .dp-card-rate { font-size: 0.71rem; color: #526071; }

        /* Custom amount */
        .dp-custom-wrap { max-width: 320px; }
        .dp-custom-box {
            display: flex;
            align-items: center;
            border: 1.5px solid rgba(22,159,230,0.25);
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }
        .dp-custom-dollar {
            padding: 0 14px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #169fe6;
            background: rgba(22,159,230,0.06);
            border-right: 1.5px solid rgba(22,159,230,0.18);
            height: 46px;
            display: flex;
            align-items: center;
        }
        .dp-custom-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 0 14px;
            font-size: 1.05rem;
            font-weight: 600;
            color: #182a3e;
            height: 46px;
            background: transparent;
        }
        .dp-custom-input:focus { box-shadow: none; }
        .dp-custom-hint { font-size: 0.78rem; color: #9ab0c4; margin: 8px 0 0; }

        /* Action row */
        .dp-action-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 18px;
            flex-wrap: wrap;
        }
        .dp-proceed-btn { background: linear-gradient(135deg, #169fe6, #0d6ea3); }
        .dp-proceed-btn:disabled { opacity: .45; cursor: not-allowed; pointer-events: none; }
        .dp-action-note { font-size: 0.82rem; color: #9ab0c4; }
    </style>

    <script>
        (function () {
            var toggle = document.getElementById('dpToggle');
            var body   = document.getElementById('dpPlansBody');
            function dpExpand() {
                var open = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
                body.style.display = open ? 'none' : 'block';
            }
            toggle.addEventListener('click', dpExpand);
            toggle.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); dpExpand(); }
            });
            function openCreditsSection() {
                toggle.setAttribute('aria-expanded', 'true');
                body.style.display = 'block';
                document.getElementById('credits-plans').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            if (window.location.hash === '#credits-plans') { openCreditsSection(); }
            window.addEventListener('hashchange', function () {
                if (window.location.hash === '#credits-plans') { openCreditsSection(); }
            });
        })();

        (function () {
            var tabs       = document.querySelectorAll('.dp-tab');
            var typeInput  = document.getElementById('dpPlanType');
            var idInput    = document.getElementById('dpPlanId');
            var proceedBtn = document.getElementById('dpProceedBtn');
            var actionNote = document.getElementById('dpActionNote');
            var customWrap = document.getElementById('dpCustomAmountWrap');

            var sections = {
                credit:       document.getElementById('dpTabCredit'),
                subscription: document.getElementById('dpTabSubscription'),
                custom:       document.getElementById('dpTabCustom'),
            };

            function setReady(ready, note) {
                proceedBtn.disabled = !ready;
                actionNote.textContent = note || '';
            }

            function clearCards() {
                document.querySelectorAll('.dp-card').forEach(function (c) { c.classList.remove('selected'); });
                idInput.value = '';
            }

            function switchTab(tabEl) {
                var type = tabEl.getAttribute('data-dp-tab');
                tabs.forEach(function (t) { t.classList.toggle('active', t === tabEl); });
                Object.keys(sections).forEach(function (k) {
                    sections[k].style.display = (k === type) ? (k === 'custom' ? 'block' : 'flex') : 'none';
                });
                typeInput.value = type;
                if (customWrap) customWrap.style.display = (type === 'custom') ? 'block' : 'none';
                clearCards();
                if (type === 'custom') {
                    var customCard = document.querySelector('#dpTabCustom .dp-card');
                    if (customCard) {
                        customCard.classList.add('selected');
                        var radio = customCard.querySelector('input[type="radio"]');
                        if (radio) radio.checked = true;
                        idInput.value = customCard.getAttribute('data-dp-id');
                    }
                    var amtVal = parseFloat(document.getElementById('dpCustomAmount').value);
                    if (amtVal >= 10) {
                        setReady(true, 'Ready to proceed');
                    } else {
                        setReady(false, 'Enter an amount to continue');
                    }
                } else {
                    setReady(false, 'Select a plan above to continue');
                }
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () { switchTab(tab); });
            });

            // Card selection (credit, subscription, and custom)
            document.querySelectorAll('.dp-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    var group = card.closest('.dp-cards-wrap');
                    if (group) group.querySelectorAll('.dp-card').forEach(function (c) { c.classList.remove('selected'); });
                    card.classList.add('selected');
                    idInput.value = card.getAttribute('data-dp-id');
                    var radio = card.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;
                    if (typeInput.value === 'custom') {
                        var amtVal = parseFloat(document.getElementById('dpCustomAmount').value);
                        if (amtVal >= 10) {
                            setReady(true, 'Ready to proceed');
                        } else {
                            setReady(false, 'Enter an amount to continue');
                        }
                    } else {
                        setReady(true, 'Ready to proceed');
                    }
                });
            });

            var customAmountInput = document.getElementById('dpCustomAmount');
            if (customAmountInput) {
                customAmountInput.addEventListener('input', function () {
                    if (typeInput.value !== 'custom') return;
                    var val = parseFloat(this.value);
                    if (val >= 1) {
                        setReady(true, 'Ready to proceed');
                    } else {
                        setReady(false, val > 0 ? 'Minimum amount is $10' : 'Enter an amount to continue');
                    }
                });
            }

        })();
    </script>

@endsection
