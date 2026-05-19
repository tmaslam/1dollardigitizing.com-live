@extends('public.layout')

@section('title', 'How Embroidery Digitizing Works | '.$siteContext->displayLabel())
@section('meta_description', 'Create a free account — no card required. Submit artwork, we build and quality-test your file, you approve it. Credits only deduct on your final approval.')

@section('content')
    @php
        $steps = [
            [
                'number' => '01',
                'icon'   => '🔐',
                'title'  => 'Sign Up or Sign In',
                'body'   => 'Create your free account or log in to your customer dashboard. Takes under a minute — no credit card required just to get started.',
                'tag'    => 'Account',
            ],
            [
                'number' => '02',
                'icon'   => '💳',
                'title'  => 'Add Credits or Subscribe (Optional)',
                'body'   => 'Add credits to your wallet when you are ready — not required at sign-up. You can explore the dashboard first and top up whenever you want to place an order. Credits never expire.',
                'tag'    => 'Payment',
            ],
            [
                'number' => '03',
                'icon'   => '🎨',
                'title'  => 'Submit Your Artwork',
                'body'   => 'Upload your logo or design through your dashboard. Choose <strong>Quote</strong> if you want a stitch count estimate first, or <strong>Order</strong> to go straight into production. JPG, PNG, PDF, AI, EPS — all accepted.',
                'tag'    => 'Submission',
            ],
            [
                'number' => '04',
                'icon'   => '🧮',
                'title'  => 'Estimate or Direct Processing',
                'body'   => 'If you submitted a <strong>Quote</strong>, we calculate the stitch count and send you the estimate for review before we begin. If you submitted an <strong>Order</strong>, we go straight into digitizing — no waiting.',
                'tag'    => 'Processing',
            ],
            [
                'number' => '05',
                'icon'   => '✅',
                'title'  => 'Quality Build & Test',
                'body'   => 'An experienced digitizer builds your file by hand — stitch paths, underlay, density — all tuned for your machine and fabric type. We run a quality check before it leaves our team, then deliver it to your dashboard.',
                'tag'    => 'Production',
            ],
            [
                'number' => '06',
                'icon'   => '🏁',
                'title'  => 'You Test & Approve',
                'body'   => 'Download your file, test it on your machine. When you are satisfied, mark the order as approved in your dashboard — credits are deducted at that point. Need changes? Free priority edits, no questions asked. Credits only deduct on your final approval.',
                'tag'    => 'Approval',
            ],
        ];

        $details = [
            [
                'title' => 'Create Your Account — Free, No Card Required',
                'body'  => 'Sign up and your customer dashboard is ready immediately. No credit card, no subscription, no commitment required just to create an account. Browse the dashboard, explore the tools, and get familiar before you spend anything. Your account stays free — you only spend credits when you approve finished work.',
            ],
            [
                'title' => 'Add Credits or Subscribe — When You Are Ready',
                'body'  => 'Buying credits or a subscription is completely optional at sign-up. You can create your account, look around, and add credits whenever you decide to place your first order. Credits work like a prepaid wallet — one credit equals one dollar of digitizing work, they never expire, and they carry over indefinitely. A subscription plan is available if you prefer a fixed monthly allocation. Either way, there is no pressure to buy anything on day one.',
            ],
            [
                'title' => 'Submit as a Quote or a Direct Order',
                'body'  => '<strong>Quote:</strong> Submit your artwork and we calculate the stitch count and send back the estimated price before any work begins. You review the estimate, and if you approve it, we move into production. <br><br><strong>Order:</strong> If you already know the scope or want to skip the estimate step, submit directly as an order and we start building your file immediately.',
            ],
            [
                'title' => 'Stitch Count Estimate (Quote Submissions Only)',
                'body'  => 'For quote submissions, we manually assess your artwork — complexity, detail level, number of colors — and send you an accurate stitch count estimate with the corresponding credit cost. There is no guesswork. You see the exact price before you commit. For direct orders, we skip this step and move straight to production.',
            ],
            [
                'title' => 'We Build and Quality-Test Your File',
                'body'  => 'A real digitizer builds your file. Stitch sequence is optimized for production efficiency, underlay is set for your fabric, density is tuned to avoid blowout and thread breaks. Before it reaches you, the file goes through an internal quality check. We do not release files we would not run ourselves.',
            ],
            [
                'title' => 'You Test It — Credits Deduct Only on Your Approval',
                'body'  => 'Download your file from the dashboard and run it on your machine. If everything looks right, mark the order as approved. <strong>Your credits are only deducted at this point — not before.</strong> If you need changes — thread breaks, edge issues, density problems, anything on our end — request a revision. Edits are done free of charge, flagged as priority in our queue, and turned around fast. Your credits are never deducted until you are satisfied with the result.',
            ],
        ];

        $highlights = [
            [
                'icon'  => '💰',
                'title' => 'Pay Only When You Approve',
                'body'  => 'Credits are deducted only after you review the finished file and mark it approved. You are never charged for work you have not accepted.',
            ],
            [
                'icon'  => '🔄',
                'title' => 'Free Edits — Always Priority',
                'body'  => 'If the file needs changes due to anything on our end, revisions are free and moved to the front of the queue. No tickets, no debates.',
            ],
            [
                'icon'  => '⚡',
                'title' => 'Quote or Order — Your Choice',
                'body'  => 'Get a stitch count estimate before committing, or skip straight to production if you already know what you need. Both paths lead to the same quality.',
            ],
            [
                'icon'  => '💾',
                'title' => 'Credits Never Expire',
                'body'  => 'Unused credits stay in your wallet indefinitely. Buy in bulk when it suits you — they will be there when you need them.',
            ],
            [
                'icon'  => '📂',
                'title' => 'Everything in Your Dashboard',
                'body'  => 'Submit artwork, track order status, download files, request edits, and manage your credit balance — all in one place, any time.',
            ],
            [
                'icon'  => '🧵',
                'title' => 'Hand-Built, Not Auto-Generated',
                'body'  => 'Every file is built by an experienced digitizer. No auto-digitizing software, no shortcuts. Files that run correctly on the machine the first time.',
            ],
        ];
    @endphp

    <section class="page-header work-process-hero-flat">
        <div class="container">
            <div class="work-process-page-header">
                <span class="section-label" style="display:inline-block;width:fit-content;">How It Works</span>
                <h1>From Artwork to <span>Approved File</span></h1>
                <p>Create a free account — no card needed. Add credits when you are ready, submit your design, we build and quality-test the file, you approve it. Credits are only deducted on your approval. Free edits until you are satisfied.</p>
                <div class="theme-header-actions" style="justify-content:center;margin-top:1.5rem;">
                    <a class="button primary" href="{{ url('/sign-up.php') }}">Create Free Account</a>
                    <a class="button secondary" href="{{ url('/price-plan.php') }}">View Credit Pricing</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Step overview --}}
    <section class="section">
        <div class="container">
            <div class="section-header" style="text-align:center;margin-bottom:2.5rem;">
                <h2>The Complete <span>Process</span></h2>
                <p>Six steps from sign-up to a production-ready file — with no surprises at any stage.</p>
            </div>
            <div class="process-steps-grid">
                @foreach ($steps as $step)
                    <div class="process-step-card">
                        <div class="process-step-top">
                            <span class="process-step-number">{{ $step['number'] }}</span>
                            <span class="process-step-tag">{{ $step['tag'] }}</span>
                        </div>
                        <div class="process-step-icon">{{ $step['icon'] }}</div>
                        <h3 class="process-step-title">{{ $step['title'] }}</h3>
                        <p class="process-step-body">{!! $step['body'] !!}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Detailed breakdown --}}
    <section class="section section-surface">
        <div class="container">
            <div class="section-header" style="text-align:center;margin-bottom:2.5rem;">
                <h2>Each Step, <span>In Full Detail</span></h2>
                <p>No surprises. Here is exactly what happens at every stage of your order.</p>
            </div>
            <div class="process-detail-list">
                @foreach ($details as $index => $item)
                    <div class="process-detail-item">
                        <div class="process-detail-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="process-detail-content">
                            <h3>{{ $item['title'] }}</h3>
                            <p>{!! $item['body'] !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Highlights --}}
    <section class="section">
        <div class="container">
            <div class="section-header" style="text-align:center;margin-bottom:2.5rem;">
                <h2>What Makes This <span>Process Different</span></h2>
            </div>
            <div class="features-grid">
                @foreach ($highlights as $h)
                    <article class="feature-item">
                        <div class="feature-icon">{{ $h['icon'] }}</div>
                        <h3>{{ $h['title'] }}</h3>
                        <p>{{ $h['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Credit approval callout --}}
    <section class="section section-surface">
        <div class="container">
            <div class="process-approval-callout">
                <div class="process-approval-icon">🔒</div>
                <h2>Your Credits Are <span>Never at Risk</span></h2>
                <p>Credits are only deducted when <strong>you</strong> mark the order as approved. Until that moment — no matter how many revisions we go through — your balance stays intact. If we cannot deliver a file you are satisfied with, you owe nothing for that order.</p>
                <div class="theme-header-actions" style="justify-content:center;margin-top:1.75rem;">
                    <a class="button primary" href="{{ url('/sign-up.php') }}">Get Started Free</a>
                    <a class="button secondary" href="{{ url('/contact-us.php') }}">Talk to Our Team</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* ── Process steps grid ── */
        .process-steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .process-step-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 16px;
            padding: 1.75rem 1.5rem 1.5rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .process-step-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #169fe6, #0d6ea3);
        }

        .process-step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(22, 159, 230, 0.12);
        }

        .process-step-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .process-step-number {
            font-size: 0.72rem;
            font-weight: 800;
            color: #169fe6;
            font-family: 'Manrope', sans-serif;
            letter-spacing: 0.08em;
        }

        .process-step-tag {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            background: rgba(22, 159, 230, 0.08);
            color: #0d6ea3;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .process-step-icon {
            font-size: 1.8rem;
            line-height: 1;
            margin: 0.25rem 0;
        }

        .process-step-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.3;
        }

        .process-step-body {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
            flex: 1;
        }

        /* ── Detailed breakdown list ── */
        .process-detail-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .process-detail-item {
            display: grid;
            grid-template-columns: 64px 1fr;
            gap: 0 2rem;
            padding: 2rem 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
            align-items: start;
        }

        .process-detail-item:last-child {
            border-bottom: 0;
        }

        .process-detail-index {
            font-size: 2.5rem;
            font-weight: 800;
            color: rgba(22, 159, 230, 0.18);
            font-family: 'Manrope', sans-serif;
            letter-spacing: -0.04em;
            line-height: 1;
            padding-top: 0.2rem;
            text-align: center;
        }

        .process-detail-content h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.6rem;
        }

        .process-detail-content p {
            font-size: 0.95rem;
            color: #4b5563;
            line-height: 1.8;
            margin: 0;
        }

        /* ── Approval callout ── */
        .process-approval-callout {
            max-width: 720px;
            margin: 0 auto;
            text-align: center;
        }

        .process-approval-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .process-approval-callout h2 {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            margin-bottom: 1rem;
        }

        .process-approval-callout p {
            font-size: 1.05rem;
            color: #4b5563;
            line-height: 1.82;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .process-steps-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .process-steps-grid {
                grid-template-columns: 1fr;
            }

            .process-detail-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .process-detail-index {
                font-size: 1.5rem;
                text-align: left;
            }
        }
    </style>
@endsection

@section('structured_data')
@php
$howToSchema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'HowTo',
    'name'        => 'How Embroidery Digitizing Works',
    'description' => 'Create a free account, submit artwork, we build and quality-test your file, you approve. Credits only deducted on final approval.',
    'step'        => array_values(array_map(function ($s, $i) {
        return [
            '@type'    => 'HowToStep',
            'position' => $i + 1,
            'name'     => strip_tags($s['title']),
            'text'     => strip_tags($s['body']),
        ];
    }, $steps, array_keys($steps))),
];
echo json_encode($howToSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
@endsection
