@extends('public.layout')

@section('title', 'Embroidery Digitizing Quality | '.$siteContext->displayLabel())
@section('meta_description', 'Production-ready embroidery files from experienced digitizers. Clean stitch paths, correct density, and 24-hour turnaround. Every order built for real production.')

@section('content')
    @php
        $offerItems = [
            [
                'title' => 'Fast Turnaround',
                'body' => 'Standard orders ship in 24 hours. Not "usually" — consistently. Shops plan their production schedules around that window, so we hold to it.',
            ],
            [
                'title' => 'Straightforward Pricing',
                'body' => '$1 per 1,000 stitches. $6 minimum. No complexity fee added later, no surprise charges after you\'ve already said yes. What we quote is what you pay.',
            ],
            [
                'title' => 'Every Format You Need',
                'body' => 'DST, PES, EXP, VP3, JEF, XXX and more for embroidery. AI, EPS, SVG, PDF for vector. Just tell us what machine or software you\'re running.',
            ],
            [
                'title' => 'Simple, Secure Payment',
                'body' => 'Pay through Stripe or 2Checkout after you approve the quote. Visa, MasterCard, Amex, Discover, PayPal. We don\'t store card details on our servers.',
            ],
            [
                'title' => 'Files Built for the Machine',
                'body' => 'Correct stitch density, proper underlay, sequenced paths. Built by someone who knows what happens when those things are wrong — not exported from auto-digitizing software.',
            ],
        ];
    @endphp

    <section class="page-header">
        <div class="container">
            <div>
                <span class="theme-badge">{{ $siteContext->displayLabel() }}</span>
                <h1>Embroidery Digitizing <span>Quality</span> — Built for Real Production</h1>
                <p>Not just clean previews — files that run correctly on the actual machine. That's the standard we hold to on every order, from a simple logo to a complex portrait.</p>
                <div class="theme-header-actions">
                    <a class="button primary" href="{{ url('/price-plan.php') }}">View Pricing</a>
                    <a class="button secondary" href="{{ url('/contact-us.php') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-card">
                <div class="split">
                    <div class="copy">
                        <div class="section-head quality-head">
                            <h2><span style="color:#169fe6;">Quality</span> Digitizing & Embroidery</h2>
                        </div>
                        <div class="quality-copy">
                            <p>We've been digitizing since 2005. In that time we've learned one thing that doesn't change: shops don't care about your process — they care whether the file runs the first time. So that's what we focus on.</p>
                            <p>Every file is built by a digitizer who understands stitch sequence, underlay behavior, and how density changes across different fabric types. No auto-digitizing, no offshore outsourcing. Someone with real embroidery knowledge is behind every file we send.</p>
                            <p>If something's off — thread breaks, edge blowout, density issues — we fix it. No ticket, no debate. That's why customers keep reordering instead of shopping around.</p>
                        </div>
                    </div>
                    <div class="quality-side-card">
                        <span class="quality-side-label">What Customers Expect</span>
                        <ul>
                            <li>Clean embroidery digitizing</li>
                            <li>Responsive turnaround</li>
                            <li>Affordable rates</li>
                            <li>Reliable production files</li>
                            <li>Support when revisions are needed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-card">
                <div class="section-head" style="margin-bottom:20px;">
                    <h2>What You're Getting</h2>
                    <p>A few things that set apart how we actually run orders.</p>
                </div>
                <div class="quality-offer-grid">
                    @foreach ($offerItems as $item)
                        <article class="quality-offer-card">
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['body'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-card quality-cta-card">
                <div>
                    <h2>Ready to Send Something Over?</h2>
                    <p>Drop us the artwork and tell us what you're making. Quote is free, comes back fast, and there's no obligation until you say go.</p>
                </div>
                <div class="theme-header-actions" style="margin:0;">
                    <a class="button primary" href="{{ url('/sign-up.php') }}">Get Started</a>
                    <a class="button secondary" href="{{ url('/formats.php') }}">View Formats</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .quality-head {
            text-align: left;
            margin-bottom: 14px;
        }

        .quality-copy {
            display: grid;
            gap: 14px;
        }

        .quality-copy p {
            margin: 0;
            color: #526071;
            line-height: 1.78;
        }

        .quality-side-card {
            align-self: start;
            padding: 24px;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(22, 159, 230, 0.08) 0%, rgba(255, 255, 255, 0.98) 100%);
            border: 1px solid rgba(22, 159, 230, 0.14);
            box-shadow: 0 18px 36px rgba(12, 48, 89, 0.08);
        }

        .quality-side-label {
            display: inline-block;
            margin-bottom: 14px;
            color: #0f6d9f;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .quality-side-card ul {
            margin: 0;
            padding-left: 18px;
            color: #3c4c5e;
            line-height: 1.75;
        }

        .quality-offer-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .quality-offer-card {
            padding: 24px;
            border-radius: 22px;
            background: #fff;
            border: 1px solid rgba(22, 159, 230, 0.12);
            box-shadow: 0 16px 30px rgba(12, 48, 89, 0.08);
        }

        .quality-offer-card h3 {
            margin: 0 0 10px;
            color: #182a3e;
            font-size: 1.08rem;
        }

        .quality-offer-card p {
            margin: 0;
            color: #526071;
            line-height: 1.72;
        }

        .quality-cta-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .quality-cta-card h2 {
            margin: 0 0 10px;
        }

        .quality-cta-card p {
            margin: 0;
            color: #526071;
            line-height: 1.72;
            max-width: 720px;
        }

        @media (max-width: 960px) {
            .quality-offer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .quality-cta-card {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 720px) {
            .quality-offer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
