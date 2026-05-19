@extends('public.layout')

@section('title', 'Embroidery Digitizing Pricing from $1 | '.$siteContext->displayLabel())
@section('meta_description', 'Embroidery digitizing from $1/1,000 stitches. Standard (24h), Priority (12h), Super Rush (8h) options. $6 minimum, no hidden fees, free revisions.')

@section('content')
    @php
        $quoteCtaUrl = request()->session()->has('customer_user_id') ? url('/quote.php') : url('/sign-up.php');
        $plans = [
            [
                'title' => 'Standard',
                'turnaround' => '⏰ 24-Hour Turnaround',
                'amount' => '$1',
                'unit' => 'per 1,000 stitches',
                'minimums' => ['Minimum charge: $6.00', 'Vector: $6/hr'],
                'features' => ['Free quotes for all', 'Free edits included', '1 year design backup', 'No hidden fees', 'All machine formats', 'Email support'],
                'featured' => false,
            ],
            [
                'title' => 'Priority',
                'turnaround' => '⚡ 12-Hour Turnaround',
                'amount' => '$1.50',
                'unit' => 'per 1,000 stitches',
                'minimums' => ['Minimum charge: $9.00', 'Vector: $9/hr'],
                'features' => ['Free quotes for all', 'Free edits included', '1 year design backup', 'No hidden fees', 'All machine formats', 'Priority support'],
                'featured' => true,
            ],
            [
                'title' => 'Super Rush',
                'turnaround' => '🚀 8-Hour Turnaround',
                'amount' => '$2.00',
                'unit' => 'per 1,000 stitches',
                'minimums' => ['Minimum charge: $12.00', 'Vector: $12/hr'],
                'features' => ['Free quotes for all', 'Free edits included', '1 year design backup', 'No hidden fees', 'All machine formats', 'Priority phone support'],
                'featured' => false,
            ],
        ];
        $creditPacks = [
            [
                'name'       => 'Starter',
                'credits'    => 100,
                'stitches'   => '100,000',
                'designs'    => '~15 designs',
                'full_price' => 100,
                'sale_price' => 95,
                'saving'     => 5,
                'discount'   => '5%',
                'per_credit' => '$0.95',
                'badge'      => null,
                'featured'   => false,
                'color'      => 'cp-teal',
                'features'   => [
                    'Credits never expire',
                    'Standard 24-hr turnaround',
                    'Free edits on every order',
                    'All machine formats included',
                    'Unlimited free quote included',
                ],
            ],
            [
                'name'       => 'Basic',
                'credits'    => 200,
                'stitches'   => '200,000',
                'designs'    => '~30 designs',
                'full_price' => 200,
                'sale_price' => 186,
                'saving'     => 14,
                'discount'   => '7%',
                'per_credit' => '$0.93',
                'badge'      => null,
                'featured'   => false,
                'color'      => 'cp-blue',
                'features'   => [
                    'Credits never expire',
                    'Standard 24-hr turnaround',
                    'Free edits on every order',
                    'All machine formats included',
                    'Unlimited free quote included',
                ],
            ],
            [
                'name'       => 'Value',
                'credits'    => 300,
                'stitches'   => '300,000',
                'designs'    => '~45 designs',
                'full_price' => 300,
                'sale_price' => 270,
                'saving'     => 30,
                'discount'   => '10%',
                'per_credit' => '$0.90',
                'badge'      => 'Most Popular',
                'featured'   => true,
                'color'      => 'cp-indigo',
                'features'   => [
                    'Credits never expire',
                    'Standard 24-hr turnaround',
                    'Free edits on every order',
                    'All machine formats included',
                    'Unlimited free quote included',
                ],
            ],
            [
                'name'       => 'Studio',
                'credits'    => 400,
                'stitches'   => '400,000',
                'designs'    => '~60 designs',
                'full_price' => 400,
                'sale_price' => 352,
                'saving'     => 48,
                'discount'   => '12%',
                'per_credit' => '$0.88',
                'badge'      => null,
                'featured'   => false,
                'color'      => 'cp-purple',
                'features'   => [
                    'Credits never expire',
                    'Standard 24-hr turnaround',
                    'Free edits on every order',
                    'All machine formats included',
                    'Unlimited free quote included',
                    'Dedicated digitizer assigned',
                ],
            ],
            [
                'name'       => 'Production',
                'credits'    => 500,
                'stitches'   => '500,000',
                'designs'    => '~75 designs',
                'full_price' => 500,
                'sale_price' => 425,
                'saving'     => 75,
                'discount'   => '15%',
                'per_credit' => '$0.85',
                'badge'      => 'Best Value',
                'featured'   => false,
                'color'      => 'cp-gold',
                'features'   => [
                    'Credits never expire',
                    'Standard 24-hr turnaround',
                    'Free edits on every order',
                    'All machine formats included',
                    'Unlimited free quote included',
                    'Dedicated senior digitizer',
                ],
            ],
            [
                'name'       => 'Enterprise',
                'credits'    => 1000,
                'stitches'   => '1,000,000',
                'designs'    => '~150 designs',
                'full_price' => 1000,
                'sale_price' => 800,
                'saving'     => 200,
                'discount'   => '20%',
                'per_credit' => '$0.80',
                'badge'      => 'Max Savings',
                'featured'   => false,
                'color'      => 'cp-navy',
                'features'   => [
                    'Credits never expire',
                    'Standard 24-hr turnaround',
                    'Free edits on every order',
                    'All machine formats included',
                    'Unlimited free quote included',
                    'Dedicated senior digitizer',
                ],
            ],
        ];
        $subPlans = [
            [
                'name'        => 'Starter',
                'price'       => 90,
                'credits'     => 100,
                'stitches'    => '100,000',
                'rate'        => '$0.90',
                'turnaround'  => '24-Hour Standard',
                'turnaround_icon' => '⏰',
                'badge'       => null,
                'featured'    => false,
                'color'       => 'sub-teal',
                'features'    => [
                    '100 credits / month',
                    '24-hour standard turnaround',
                    'Free edits on all orders',
                    'Priority email support',
                    'All machine formats',
                ],
            ],
            [
                'name'        => 'Growth',
                'price'       => 170,
                'credits'     => 200,
                'stitches'    => '200,000',
                'rate'        => '$0.85',
                'turnaround'  => '24-Hour Standard',
                'turnaround_icon' => '⏰',
                'badge'       => null,
                'featured'    => false,
                'color'       => 'sub-blue',
                'features'    => [
                    '200 credits / month',
                    '24-hour standard turnaround',
                    'Free edits on all orders',
                    'Phone & email support',
                    'Dedicated digitizer',
                    'All machine formats',
                ],
            ],
            [
                'name'        => 'Studio',
                'price'       => 320,
                'credits'     => 400,
                'stitches'    => '400,000',
                'rate'        => '$0.80',
                'turnaround'  => '12-Hour Priority',
                'turnaround_icon' => '⚡',
                'badge'       => 'Most Popular',
                'featured'    => true,
                'color'       => 'sub-indigo',
                'features'    => [
                    '400 credits / month',
                    '12-hour priority turnaround',
                    'Free edits on all orders',
                    'Dedicated account manager',
                    'Rush queue priority',
                    'All machine formats',
                ],
            ],
            [
                'name'        => 'Production',
                'price'       => 700,
                'credits'     => 1000,
                'stitches'    => '1,000,000',
                'rate'        => '$0.70',
                'turnaround'  => '12-Hour Priority',
                'turnaround_icon' => '⚡',
                'badge'       => 'Best Value',
                'featured'    => false,
                'color'       => 'sub-purple',
                'features'    => [
                    '1,000 credits / month',
                    '12-hour priority turnaround',
                    'Free edits on all orders',
                    'Dedicated senior digitizer',
                    'Rush queue priority',
                    'All machine formats',
                ],
            ],
            [
                'name'        => 'Enterprise',
                'price'       => 1200,
                'credits'     => 2000,
                'stitches'    => '2,000,000',
                'rate'        => '$0.60',
                'turnaround'  => '8-Hour Super Rush',
                'turnaround_icon' => '🏆',
                'badge'       => 'Max Savings',
                'featured'    => false,
                'color'       => 'sub-gold',
                'features'    => [
                    '2,000 credits / month',
                    '8-hour super rush turnaround',
                    'Free edits on all orders',
                    'Dedicated senior digitizer',
                    'Top-priority rush queue',
                    'Custom SLA available',
                    'All machine formats',
                ],
            ],
        ];
        $extras = [
            ['icon' => '💰', 'title' => 'Extra Setup', 'summary' => 'Need a second format or a slight resize? Extra setups are $5 — not a full re-digitize charge.'],
            ['icon' => '📋', 'title' => 'Free Quotes', 'summary' => 'Send us the artwork and we\'ll price it before you commit. No obligation.'],
            ['icon' => '🔄', 'title' => 'Free Edits', 'summary' => 'If something\'s off because of how we built the file, we fix it. No charge.'],
            ['icon' => '💾', 'title' => '1 Year Backup', 'summary' => 'We keep your designs on file for a year. Reorders don\'t start from scratch.'],
            ['icon' => '✓', 'title' => 'No Hidden Fees', 'summary' => 'The price we quote is what you pay. No complexity surcharges added at the end.'],
            ['icon' => '🎨', 'title' => '3D Puff', 'summary' => 'Puff digitizing included at the same rate. No upcharge for the extra technique.'],
            ['icon' => '🔗', 'title' => 'Chain Stitch', 'summary' => '$1.50 per 1,000 stitches — slightly more than standard because of the specialized technique.'],
            ['icon' => '📊', 'title' => 'Complexity Fee', 'summary' => 'We don\'t charge one. Complicated designs take more time on our end, not more money on yours.'],
        ];
    @endphp

    <section class="page-header">
        <div class="container">
            <div>
                <h1>Digitizing <span>Pricing</span> — No Guesswork</h1>
                <p>$1 per 1,000 stitches. $6 minimum. Rush options if you're on a deadline. That's the whole pricing model — no hidden fees, no complexity charges added after you've already said yes.</p>
            </div>
        </div>
    </section>

    {{-- Commitment ladder --}}
    <div class="pricing-ladder-wrap">
        <div class="container">
            <div class="pricing-ladder">
                <a href="#standard-rates" class="pricing-ladder-step pricing-ladder-step--active">
                    <span class="pl-num">01</span>
                    <span class="pl-body">
                        <strong>Pay Per Order</strong>
                        <span>No commitment — just pay as you go</span>
                    </span>
                </a>
                <span class="pl-arrow">→</span>
                <a href="#credit-packs" class="pricing-ladder-step">
                    <span class="pl-num">02</span>
                    <span class="pl-body">
                        <strong>Credit Packs</strong>
                        <span>Buy in bulk, save up to 20%</span>
                    </span>
                </a>
                <span class="pl-arrow">→</span>
                <a href="#subscriptions" class="pricing-ladder-step">
                    <span class="pl-num">03</span>
                    <span class="pl-body">
                        <strong>Subscribe Monthly</strong>
                        <span>Best rate — credits included every cycle</span>
                    </span>
                </a>
            </div>
        </div>
    </div>

    {{-- Per-order speed tiers --}}
    <section class="section speed-tiers-section" id="standard-rates">
        <div class="container">
            <div class="section-header">
                <p class="pricing-tier-label">01 &mdash; No Commitment</p>
                <h2>Standard <span>Rates</span></h2>
                <p>No subscription required. Choose a turnaround tier and pay per 1,000 stitches. Same quality on every order.</p>
            </div>

            @php
                $tierKeys  = ['Standard' => 'standard', 'Priority' => 'priority', 'Super Rush' => 'rush'];
                $tierEmoji = ['Standard' => '⏰', 'Priority' => '⚡', 'Super Rush' => '🚀'];
            @endphp

            <div class="speed-tier-grid">
                @foreach ($plans as $plan)
                    @php $tk = $tierKeys[$plan['title']] ?? 'standard'; @endphp
                    <div class="speed-tier-card speed-tier-card--{{ $tk }}">
                        <div class="speed-tier-top">
                            <span class="speed-tier-icon">{{ $tierEmoji[$plan['title']] ?? '⏰' }}</span>
                            <div>
                                <div class="speed-tier-name">{{ $plan['title'] }}</div>
                                <div class="speed-tier-turnaround">{{ preg_replace('/^[^\s]+\s/', '', $plan['turnaround']) }}</div>
                            </div>
                        </div>

                        <div class="speed-tier-price-block">
                            <span class="speed-tier-amount">{{ $plan['amount'] }}</span>
                            <span class="speed-tier-unit">{{ $plan['unit'] }}</span>
                        </div>

                        <div class="speed-tier-minimums">
                            @foreach ($plan['minimums'] as $minimum)
                                <span>{{ $minimum }}</span>
                            @endforeach
                        </div>

                        <ul class="speed-tier-features">
                            @foreach ($plan['features'] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>

                        <a href="{{ $quoteCtaUrl }}" class="button {{ $plan['featured'] ? 'primary' : 'secondary' }} speed-tier-btn">Get Started</a>
                    </div>
                @endforeach
            </div>

            <p class="speed-note">Turnaround runs from when payment clears and artwork is received. Orders placed after 5 PM PST begin the next business day.</p>

            <div class="pricing-bridge">
                <span>Order regularly? Pre-buying credits saves you up to 20% on the same quality.</span>
                <a href="#credit-packs" class="pricing-bridge-link">See Credit Packs →</a>
            </div>
        </div>
    </section>

    <section class="section credits-section" id="credit-packs">
        <div class="container">
            <div class="section-header">
                <p class="pricing-tier-label">02 &mdash; Bulk Savings</p>
                <h2>Pre-Pay with <span>Credit Packs</span> — Save Up to 20%</h2>
                <p>Buy stitch credits in bulk and pay less per design. <strong>1 credit = 1,000 stitches = $1.00</strong> at standard rate. Load up once, use across any order, any time.</p>
            </div>

            <div class="credits-carousel-wrap">
                <button class="credits-nav credits-prev" aria-label="Previous" onclick="creditsScroll(-1)">&#8592;</button>

                <div class="credits-carousel" id="creditsCarousel">
                    @foreach ($creditPacks as $pack)
                        <div class="credit-card credit-card--{{ $pack['color'] }} {{ $pack['featured'] ? 'credit-card--featured' : '' }}">
                            @if ($pack['badge'])
                                <span class="credit-badge">{{ $pack['badge'] }}</span>
                            @endif

                            <div class="credit-pack-name">{{ $pack['name'] }}</div>

                            <div class="credit-credits">
                                <span class="credit-number">{{ $pack['credits'] }}</span>
                                <span class="credit-label">Credits</span>
                            </div>

                            <div class="credit-stitches">{{ $pack['designs'] }} &nbsp;·&nbsp; {{ $pack['stitches'] }} stitches</div>

                            <div class="credit-pricing">
                                <span class="credit-was">${{ number_format($pack['full_price']) }}</span>
                                <span class="credit-now">${{ $pack['sale_price'] == floor($pack['sale_price']) ? number_format($pack['sale_price']) : number_format($pack['sale_price'], 2) }}</span>
                            </div>

                            <div class="credit-saving">
                                <span class="credit-discount-pill">{{ $pack['discount'] }} OFF</span>
                                <span class="credit-save-text">Save ${{ $pack['saving'] == floor($pack['saving']) ? number_format($pack['saving']) : number_format($pack['saving'], 2) }}</span>
                            </div>

                            <div class="credit-rate">
                                <span>{{ $pack['per_credit'] }}</span> per 1,000 stitches
                            </div>

                            <ul class="credit-features">
                                @foreach ($pack['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>

                            <a href="{{ $quoteCtaUrl }}" class="button {{ $pack['featured'] ? 'primary' : 'secondary' }} credit-cta">Buy {{ $pack['name'] }} Pack</a>
                        </div>
                    @endforeach
                </div>

                <button class="credits-nav credits-next" aria-label="Next" onclick="creditsScroll(1)">&#8594;</button>
            </div>

            <div class="credits-dots" id="creditsDots">
                @foreach ($creditPacks as $i => $pack)
                    <button class="credits-dot {{ $i === 0 ? 'active' : '' }}" onclick="creditsGoTo({{ $i }})" aria-label="Go to pack {{ $i + 1 }}"></button>
                @endforeach
            </div>

            <p class="credits-note">Credits are applied to your account balance and deducted per order. Standard 24-hr turnaround applies to all credit orders — upgrade to Priority or Super Rush at standard per-order rates. Credits never expire. <a href="{{ url('/contact-us.php') }}">Contact us</a> to purchase a pack.</p>

            <div class="pricing-bridge">
                <span>Using this service every month? A subscription gives you a lower rate and priority turnaround built in.</span>
                <a href="#subscriptions" class="pricing-bridge-link">See Subscription Plans →</a>
            </div>
        </div>
    </section>

    <section class="section sub-section" id="subscriptions">
        <div class="container">
            <div class="section-header">
                <p class="pricing-tier-label">03 &mdash; Best Monthly Value</p>
                <h2>Monthly <span>Subscription Plans</span> — Commit Less, Get More</h2>
                <p>Pay monthly and get a fixed credit allowance every billing cycle — at a lower rate than pay-per-order. Credits roll over, turnaround upgrades are included, and you can cancel anytime.</p>
            </div>

            <div class="credits-carousel-wrap">
                <button class="credits-nav sub-nav" aria-label="Previous" onclick="subScroll(-1)">&#8592;</button>

                <div class="credits-carousel" id="subCarousel">
                    @foreach ($subPlans as $plan)
                        <div class="sub-card sub-card--{{ $plan['color'] }} {{ $plan['featured'] ? 'sub-card--featured' : '' }}">
                            @if ($plan['badge'])
                                <span class="sub-badge sub-badge--{{ $plan['color'] }}">{{ $plan['badge'] }}</span>
                            @endif

                            <div class="sub-header">
                                <span class="sub-name">{{ $plan['name'] }}</span>
                                <span class="sub-monthly-label">/ month</span>
                            </div>

                            <div class="sub-price-row">
                                <span class="sub-price">${{ $plan['price'] }}</span>
                                <span class="sub-price-unit">/ mo</span>
                            </div>

                            <div class="sub-turnaround-badge sub-turnaround--{{ $plan['color'] }}">
                                {{ $plan['turnaround_icon'] }} {{ $plan['turnaround'] }}
                            </div>

                            <div class="sub-rate-row">
                                <span class="sub-rate-val">{{ $plan['rate'] }}</span>
                                <span class="sub-rate-label">per 1,000 stitches</span>
                            </div>

                            <ul class="sub-features">
                                @foreach ($plan['features'] as $feat)
                                    <li>{{ $feat }}</li>
                                @endforeach
                            </ul>

                            <a href="{{ $quoteCtaUrl }}" class="button {{ $plan['featured'] ? 'primary' : 'secondary' }} credit-cta">
                                Start {{ $plan['name'] }}
                            </a>
                        </div>
                    @endforeach
                </div>

                <button class="credits-nav sub-nav" aria-label="Next" onclick="subScroll(1)">&#8594;</button>
            </div>

            <div class="credits-dots" id="subDots">
                @foreach ($subPlans as $i => $plan)
                    <button class="credits-dot sub-dot {{ $i === 0 ? 'active' : '' }}" onclick="subGoTo({{ $i }})" aria-label="Go to plan {{ $i + 1 }}"></button>
                @endforeach
            </div>

            <p class="credits-note">Billed monthly. Cancel anytime — no long-term commitment.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>What Else Is <span>Included</span></h2>
                <p>A few things worth knowing before you order.</p>
            </div>
            <div class="extras-grid">
                @foreach ($extras as $extra)
                    <div class="extra-card">
                        <div class="extra-icon">{{ $extra['icon'] }}</div>
                        <h4>{{ $extra['title'] }}</h4>
                        <p>{{ $extra['summary'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container public-center-wrap">
            <div class="template-cta-card">
                <h2>Not Sure What You're Looking At Stitch-Count-Wise?</h2>
                <p>Send us the artwork and we'll tell you the price before anything is owed. Takes us a few hours, costs you nothing.</p>
                <div class="theme-header-actions">
                    <a href="{{ url('/book-a-meeting.php') }}" class="button secondary">Book a Meeting</a>
                </div>
            </div>
        </div>
    </section>
    <style>
        /* ── Commitment Ladder Strip ── */
        .pricing-ladder-wrap {
            background: rgba(22,159,230,0.04);
            border-top: 1px solid rgba(22,159,230,0.10);
            border-bottom: 1px solid rgba(22,159,230,0.10);
            padding: 14px 0;
        }

        .pricing-ladder {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pricing-ladder-step {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 1.5px solid rgba(22,159,230,0.14);
            background: #fff;
            text-decoration: none;
            flex: 1;
            min-width: 180px;
            transition: border-color .15s, box-shadow .15s;
        }
        .pricing-ladder-step:hover {
            border-color: #169fe6;
            box-shadow: 0 4px 12px rgba(22,159,230,0.10);
        }
        .pricing-ladder-step--active {
            border-color: #169fe6;
            background: rgba(22,159,230,0.05);
        }

        .pl-num {
            font-size: 1.3rem;
            font-weight: 900;
            color: #c8dff0;
            line-height: 1;
            flex-shrink: 0;
        }
        .pricing-ladder-step--active .pl-num { color: #169fe6; }

        .pl-body { display: flex; flex-direction: column; gap: 2px; }
        .pl-body strong { font-size: 0.88rem; color: #182a3e; }
        .pl-body span   { font-size: 0.75rem; color: #8fa0b4; }

        .pl-arrow {
            font-size: 1rem;
            color: #c8dff0;
            flex-shrink: 0;
            padding: 0 2px;
        }

        /* ── Tier label above each section h2 ── */
        .pricing-tier-label {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #169fe6;
            margin: 0 0 8px;
        }

        /* ── Bridge nudge between sections ── */
        .pricing-bridge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 32px;
            padding: 14px 20px;
            border-radius: 12px;
            background: rgba(22,159,230,0.04);
            border: 1px dashed rgba(22,159,230,0.22);
            font-size: 0.84rem;
            color: #7a8fa6;
            text-align: center;
        }

        .pricing-bridge-link {
            font-size: 0.84rem;
            font-weight: 700;
            color: #169fe6;
            text-decoration: none;
            white-space: nowrap;
        }
        .pricing-bridge-link:hover { color: #0d6ea3; }

        /* ── Per-Order Speed Tiers ── */
        .speed-tiers-section {
            background: linear-gradient(180deg, rgba(12,48,89,0.03) 0%, rgba(255,255,255,0) 100%);
        }

        .speed-tier-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            align-items: start;
        }

        .speed-tier-card {
            background: #fff;
            border: 1.5px solid rgba(22,159,230,0.13);
            border-radius: 20px;
            padding: 32px 24px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(12,48,89,0.07);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .speed-tier-card::before {
            content: '';
            display: block;
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: var(--tier-accent, #169fe6);
            border-radius: 20px 20px 0 0;
        }

        .speed-tier-card:hover {
            box-shadow: 0 18px 44px rgba(12,48,89,0.13);
            transform: translateY(-3px);
        }

        .speed-tier-card--standard  { --tier-accent: #3c9e6a; border-color: rgba(60,158,106,0.16); }
        .speed-tier-card--priority  { --tier-accent: #169fe6; border-color: rgba(22,159,230,0.22); }
        .speed-tier-card--rush      { --tier-accent: #e07b20; border-color: rgba(224,123,32,0.16); }

        .speed-tier-card--featured {
            background: linear-gradient(160deg, rgba(22,159,230,0.05) 0%, #fff 60%);
            box-shadow: 0 18px 48px rgba(22,159,230,0.18);
            transform: translateY(-8px);
        }

        .speed-tier-card--featured:hover { transform: translateY(-11px); }

        .speed-tier-badge {
            position: absolute;
            top: 14px;
            right: 16px;
            background: var(--tier-accent, #169fe6);
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            padding: 3px 12px;
            border-radius: 999px;
        }

        .speed-tier-top {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .speed-tier-icon { font-size: 2.2rem; line-height: 1; flex-shrink: 0; }

        .speed-tier-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #182a3e;
            line-height: 1.1;
        }

        .speed-tier-turnaround {
            font-size: 0.80rem;
            font-weight: 600;
            color: var(--tier-accent, #526071);
            margin-top: 2px;
        }

        .speed-tier-price-block {
            display: flex;
            align-items: baseline;
            gap: 7px;
            padding: 14px 16px;
            background: rgba(0,0,0,0.025);
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .speed-tier-amount {
            font-size: 2.6rem;
            font-weight: 800;
            color: var(--tier-accent, #169fe6);
            line-height: 1;
        }

        .speed-tier-unit {
            font-size: 0.80rem;
            color: #7a8fa6;
            line-height: 1.3;
        }

        .speed-tier-minimums {
            display: flex;
            flex-direction: column;
            gap: 3px;
            font-size: 0.79rem;
            color: #526071;
            padding: 8px 12px;
            background: rgba(0,0,0,0.015);
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .speed-tier-features {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 7px;
            flex: 1;
        }

        .speed-tier-features li {
            font-size: 0.84rem;
            color: #526071;
            padding-left: 20px;
            position: relative;
        }

        .speed-tier-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--tier-accent, #169fe6);
            font-weight: 700;
        }

        .speed-tier-btn { width: 100%; text-align: center; }

        .speed-note {
            text-align: center;
            font-size: 0.82rem;
            color: #7a8fa6;
            margin-top: 22px;
        }

        @media (max-width: 900px) {
            .speed-tier-grid { grid-template-columns: 1fr; gap: 16px; }
            .speed-tier-card--featured { transform: none; }
            .speed-tier-card--featured:hover { transform: translateY(-3px); }
        }

        /* ── Credit Pack Carousel ── */
        .credits-section {
            background: linear-gradient(180deg, rgba(22,159,230,0.04) 0%, rgba(255,255,255,0) 100%);
        }

        .credits-carousel-wrap {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .credits-carousel {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 12px 4px 16px;
            flex: 1;
        }

        .credits-carousel::-webkit-scrollbar { display: none; }

        .credit-card {
            flex: 0 0 calc(25% - 15px);
            min-width: 230px;
            scroll-snap-align: start;
            background: #fff;
            border: 1.5px solid rgba(22,159,230,0.14);
            border-radius: 22px;
            padding: 28px 22px 22px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            box-shadow: 0 16px 36px rgba(12,48,89,0.07);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .credit-card:hover {
            box-shadow: 0 22px 48px rgba(12,48,89,0.13);
            transform: translateY(-2px);
        }

        /* Color variants */
        .credit-card--cp-teal   { --cp-accent: #3c9e6a; border-color: rgba(60,158,106,0.18); }
        .credit-card--cp-blue   { --cp-accent: #169fe6; border-color: rgba(22,159,230,0.22); }
        .credit-card--cp-indigo { --cp-accent: #6b4fd8; border-color: rgba(107,79,216,0.18); }
        .credit-card--cp-purple { --cp-accent: #9333ea; border-color: rgba(147,51,234,0.18); }
        .credit-card--cp-gold   { --cp-accent: #b45309; border-color: rgba(180,83,9,0.20); background: linear-gradient(155deg, rgba(251,191,36,0.06) 0%, #fff 55%); }
        .credit-card--cp-navy   { --cp-accent: #1e3a5f; border-color: rgba(30,58,95,0.22); background: linear-gradient(155deg, rgba(30,58,95,0.07) 0%, #fff 55%); }

        .credit-card--featured {
            border-color: var(--cp-accent, #169fe6) !important;
            background: linear-gradient(160deg, rgba(22,159,230,0.06) 0%, #fff 60%);
            box-shadow: 0 20px 44px rgba(22,159,230,0.18);
        }

        .credit-pack-name {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.10em;
            color: var(--cp-accent, #169fe6);
        }

        .credit-turnaround-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(0,0,0,0.03);
            color: var(--cp-accent, #169fe6);
            border: 1px solid rgba(0,0,0,0.07);
            width: fit-content;
        }

        .credit-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--cp-accent, #169fe6);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .credit-credits {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .credit-number {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--cp-accent, #182a3e);
            line-height: 1;
        }

        .credit-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #526071;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .credit-stitches {
            font-size: 0.82rem;
            color: #7a8fa6;
            margin-top: -8px;
        }

        .credit-pricing {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .credit-was {
            font-size: 1.0rem;
            color: #a0b0bf;
            text-decoration: line-through;
        }

        .credit-now {
            font-size: 2.0rem;
            font-weight: 800;
            color: var(--cp-accent, #169fe6);
            line-height: 1;
        }

        .credit-saving {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .credit-discount-pill {
            background: #e8f7ff;
            color: #0d6ea3;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            border: 1px solid rgba(22,159,230,0.20);
        }

        .credit-card--featured .credit-discount-pill {
            background: var(--cp-accent, #169fe6);
            color: #fff;
            border-color: transparent;
        }

        .credit-save-text {
            font-size: 0.82rem;
            color: #3c9e6a;
            font-weight: 600;
        }

        .credit-rate {
            font-size: 0.82rem;
            color: #526071;
            padding: 8px 12px;
            background: rgba(22,159,230,0.05);
            border-radius: 8px;
            border: 1px solid rgba(22,159,230,0.10);
        }

        .credit-rate span {
            font-weight: 700;
            color: #182a3e;
        }

        .credit-features {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .credit-features li {
            font-size: 0.83rem;
            color: #526071;
            padding-left: 18px;
            position: relative;
        }

        .credit-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--cp-accent, #169fe6);
            font-weight: 700;
        }

        .credit-cta {
            width: 100%;
            text-align: center;
            margin-top: 4px;
        }

        /* Nav buttons */
        .credits-nav {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid rgba(22,159,230,0.20);
            background: #fff;
            color: #169fe6;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(12,48,89,0.08);
            transition: background 0.15s, color 0.15s;
            z-index: 2;
        }

        .credits-nav:hover {
            background: #169fe6;
            color: #fff;
        }

        /* Dots */
        .credits-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 16px;
        }

        .credits-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: none;
            background: rgba(22,159,230,0.20);
            cursor: pointer;
            padding: 0;
            transition: background 0.2s, width 0.2s;
        }

        .credits-dot.active {
            background: #169fe6;
            width: 22px;
            border-radius: 4px;
        }

        .credits-note {
            text-align: center;
            font-size: 0.83rem;
            color: #7a8fa6;
            margin-top: 18px;
        }

        .credits-note a {
            color: #169fe6;
            text-decoration: none;
        }

        @media (max-width: 1100px) {
            .credit-card { flex: 0 0 calc(50% - 10px); }
        }

        @media (max-width: 640px) {
            .credit-card { flex: 0 0 85%; min-width: auto; }
            .credits-nav { display: none; }
        }

        /* ── Subscription Plans Carousel ── */
        .sub-section {
            background: linear-gradient(180deg, rgba(99,60,210,0.03) 0%, rgba(255,255,255,0) 100%);
        }

        .sub-nav {
            border-color: rgba(99,60,210,0.20);
            color: #6b4fd8;
        }
        .sub-nav:hover { background: #6b4fd8; color: #fff; }

        .sub-card {
            flex: 0 0 calc(25% - 15px);
            min-width: 235px;
            scroll-snap-align: start;
            background: #fff;
            border: 1.5px solid rgba(99,60,210,0.12);
            border-radius: 22px;
            padding: 28px 22px 22px;
            display: flex;
            flex-direction: column;
            gap: 11px;
            position: relative;
            box-shadow: 0 14px 34px rgba(40,20,100,0.07);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .sub-card:hover {
            box-shadow: 0 20px 48px rgba(40,20,100,0.13);
            transform: translateY(-2px);
        }

        .sub-card--featured {
            border-color: #6b4fd8;
            background: linear-gradient(155deg, rgba(99,60,210,0.06) 0%, #fff 60%);
            box-shadow: 0 20px 44px rgba(99,60,210,0.17);
        }

        /* Colour accents per plan */
        .sub-card--sub-teal  { --sub-accent: #0ea5b0; }
        .sub-card--sub-blue  { --sub-accent: #169fe6; }
        .sub-card--sub-indigo{ --sub-accent: #6b4fd8; }
        .sub-card--sub-purple{ --sub-accent: #9333ea; }
        .sub-card--sub-gold  { --sub-accent: #b45309; border-color: rgba(180,83,9,0.20); background: linear-gradient(155deg, rgba(251,191,36,0.07) 0%, #fff 55%); }
        .sub-card--sub-navy  { --sub-accent: #1e3a5f; border-color: rgba(30,58,95,0.22); background: linear-gradient(155deg, rgba(30,58,95,0.07) 0%, #fff 55%); }

        .sub-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--sub-accent, #6b4fd8);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .sub-header {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .sub-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: #182a3e;
        }

        .sub-monthly-label {
            font-size: 0.78rem;
            color: #7a8fa6;
            font-weight: 500;
        }

        .sub-price-row {
            display: flex;
            align-items: baseline;
            gap: 4px;
            margin-top: -4px;
        }

        .sub-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--sub-accent, #6b4fd8);
            line-height: 1;
        }

        .sub-price-unit {
            font-size: 0.85rem;
            color: #7a8fa6;
        }

        .sub-turnaround-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(0,0,0,0.04);
            color: var(--sub-accent, #6b4fd8);
            border: 1px solid rgba(0,0,0,0.07);
            width: fit-content;
        }

        .sub-rate-row {
            display: flex;
            flex-direction: column;
            gap: 1px;
            padding: 8px 12px;
            background: rgba(0,0,0,0.02);
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,0.06);
        }

        .sub-rate-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: #182a3e;
        }

        .sub-rate-label {
            font-size: 0.76rem;
            color: #7a8fa6;
        }

        .sub-features {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .sub-features li {
            font-size: 0.82rem;
            color: #526071;
            padding-left: 18px;
            position: relative;
        }

        .sub-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--sub-accent, #6b4fd8);
            font-weight: 700;
        }

        .sub-dot.active {
            background: #6b4fd8;
        }

        @media (max-width: 1100px) {
            .sub-card { flex: 0 0 calc(50% - 10px); }
        }

        @media (max-width: 640px) {
            .sub-card { flex: 0 0 85%; min-width: auto; }
            .sub-nav  { display: none; }
        }
    </style>

    <script>
        function makeCarousel(carouselId, dotSelector, cardSelector, scrollFnName, goToFnName) {
            var carousel = document.getElementById(carouselId);
            var dots     = document.querySelectorAll(dotSelector);
            var cards    = carousel ? carousel.querySelectorAll(cardSelector) : [];
            var current  = 0;

            function getVisible() {
                var w = window.innerWidth;
                if (w >= 1100) return 4;
                if (w >= 640)  return 2;
                return 1;
            }

            function goTo(index) {
                if (!carousel || cards.length === 0) return;
                var max = Math.max(0, cards.length - getVisible());
                current = Math.max(0, Math.min(index, max));
                carousel.scrollTo({ left: cards[current].offsetLeft - carousel.offsetLeft, behavior: 'smooth' });
                dots.forEach(function (d, i) { d.classList.toggle('active', i === current); });
            }

            window[scrollFnName] = function (dir) { goTo(current + dir); };
            window[goToFnName]   = function (i)   { goTo(i); };

            if (carousel) {
                carousel.addEventListener('scroll', function () {
                    if (cards.length === 0) return;
                    var scrollLeft = carousel.scrollLeft;
                    var nearest = 0, minDist = Infinity;
                    cards.forEach(function (card, i) {
                        var dist = Math.abs(card.offsetLeft - carousel.offsetLeft - scrollLeft);
                        if (dist < minDist) { minDist = dist; nearest = i; }
                    });
                    if (nearest !== current) {
                        current = nearest;
                        dots.forEach(function (d, i) { d.classList.toggle('active', i === current); });
                    }
                }, { passive: true });
            }
        }

        makeCarousel('creditsCarousel', '.credits-dot:not(.sub-dot)', '.credit-card', 'creditsScroll', 'creditsGoTo');
        makeCarousel('subCarousel',     '.sub-dot',                   '.sub-card',    'subScroll',     'subGoTo');
    </script>

@endsection

@section('structured_data')
@php
$aggregateOfferSchema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => 'Embroidery Digitizing Service',
    'description' => 'Professional embroidery digitizing from $1 per 1,000 stitches. Standard, Priority, and Super Rush turnaround options.',
    'brand'       => ['@type' => 'Brand', 'name' => '1 Dollar Digitizing'],
    'offers'      => [
        '@type'       => 'AggregateOffer',
        'lowPrice'    => '1.00',
        'highPrice'   => '2.00',
        'priceCurrency' => 'USD',
        'offerCount'  => count($plans),
        'offers'      => array_values(array_map(function ($plan) {
            return [
                '@type'         => 'Offer',
                'name'          => $plan['title'],
                'description'   => strip_tags($plan['turnaround']),
                'price'         => ltrim($plan['amount'], '$'),
                'priceCurrency' => 'USD',
                'priceSpecification' => [
                    '@type'             => 'UnitPriceSpecification',
                    'price'             => ltrim($plan['amount'], '$'),
                    'priceCurrency'     => 'USD',
                    'referenceQuantity' => ['@type' => 'QuantitativeValue', 'value' => '1000', 'unitText' => 'stitches'],
                ],
            ];
        }, $plans)),
    ],
];
echo json_encode($aggregateOfferSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
@endsection
