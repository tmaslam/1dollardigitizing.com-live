@extends('public.layout')

@section('title', 'Book a Meeting — '.$siteContext->displayLabel())
@section('meta_description', 'Schedule a free 30-minute consultation with our digitizing team. Pick a time that works for you and we\'ll walk you through pricing, turnaround, and your project needs.')

@section('content')

    <section class="theme-hero-section">
        <div class="container">
            <div class="theme-hero-content bam-hero">
                <span class="theme-badge">Free 30-Minute Consultation</span>
                <h1>Book a Meeting With Our Team</h1>
                <p>Pick a time that suits you. We'll walk through your artwork, discuss turnaround options, answer questions about pricing, and make sure you know exactly what to expect — before anything is owed.</p>

                <div class="bam-trust-pills">
                    <span>No commitment required</span>
                    <span>Same team that handles your orders</span>
                    <span>Mon–Fri, flexible hours</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="bam-layout">

                {{-- Left: what to expect --}}
                <div class="bam-sidebar">
                    <div class="bam-sidebar-card">
                        <h3>What We'll Cover</h3>
                        <ul class="bam-checklist">
                            <li>Review your artwork and flag any potential issues before work begins</li>
                            <li>Confirm the right turnaround option for your deadline</li>
                            <li>Explain pricing clearly — no surprises on the invoice</li>
                            <li>Walk through file formats and machine compatibility</li>
                            <li>Answer any questions about the digitizing process</li>
                        </ul>
                    </div>

                    <div class="bam-sidebar-card bam-sidebar-card--alt">
                        <h3>Prefer to Skip the Call?</h3>
                        <p>You can always <a href="{{ url('/contact-us.php') }}">send us a message</a> with your artwork and we'll reply with a quote within a few hours — no meeting needed.</p>
                    </div>
                </div>

                {{-- Right: Calendly embed --}}
                <div class="bam-calendar">
                    <div class="calendly-inline-widget"
                         data-url="https://calendly.com/tmaslam/30min?hide_landing_page_details=1&hide_gdpr_banner=1"
                         style="min-width:320px;height:700px;"></div>
                    <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
                </div>

            </div>
        </div>
    </section>

    <style>
        .bam-hero { max-width: 660px; }

        .bam-trust-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
        }
        .bam-trust-pills span {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 999px;
            background: rgba(22,159,230,0.10);
            color: #0d6ea3;
            border: 1px solid rgba(22,159,230,0.18);
        }

        .bam-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 32px;
            align-items: start;
        }

        @media (max-width: 860px) {
            .bam-layout {
                grid-template-columns: 1fr;
            }
        }

        .bam-sidebar { display: flex; flex-direction: column; gap: 18px; }

        .bam-sidebar-card {
            background: #fff;
            border: 1.5px solid rgba(22,159,230,0.14);
            border-radius: 16px;
            padding: 22px 22px 20px;
            box-shadow: 0 4px 16px rgba(12,48,89,0.06);
        }

        .bam-sidebar-card h3 {
            margin: 0 0 14px;
            font-size: 1rem;
            color: #182a3e;
        }

        .bam-sidebar-card p {
            margin: 0;
            font-size: 0.85rem;
            color: #526071;
            line-height: 1.6;
        }

        .bam-sidebar-card p a {
            color: #169fe6;
            text-decoration: none;
        }

        .bam-sidebar-card--alt {
            background: rgba(22,159,230,0.03);
            border-color: rgba(22,159,230,0.10);
        }

        .bam-checklist {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .bam-checklist li {
            font-size: 0.84rem;
            color: #526071;
            padding-left: 24px;
            position: relative;
            line-height: 1.5;
        }

        .bam-checklist li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 0;
            color: #3c9e6a;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .bam-calendar {
            background: #fff;
            border: 1.5px solid rgba(22,159,230,0.12);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(12,48,89,0.08);
        }

        .bam-calendar .calendly-inline-widget {
            border-radius: 18px;
        }
    </style>

@endsection
