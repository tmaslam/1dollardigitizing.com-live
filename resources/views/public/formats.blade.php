@extends('public.layout')

@section('title', 'Supported Embroidery & Vector Formats | '.$siteContext->displayLabel())
@section('meta_description', 'Supported embroidery formats: DST, PES, EXP, VP3, JEF, XXX, HUS, SEW and more. Vector: AI, EPS, SVG, PDF. We deliver the right format for your machine.')

@section('content')
    @php
        $legacyAssetBase = rtrim(url('/'), '/');
    @endphp

    <section class="page-header">
        <div class="container">
            <div>
                <span class="theme-badge">{{ $siteContext->displayLabel() }}</span>
                <h1>Embroidery &amp; Vector <span>Formats We Deliver</span></h1>
                <p>All major machine embroidery formats and vector file types. Just tell us what software or machine you're using and we'll deliver the right format — no conversion needed on your end.</p>
                <div class="theme-header-actions">
                    <a class="button primary" href="{{ url('/book-a-meeting.php') }}">Book a Meeting</a>
                    <a class="button secondary" href="{{ url('/contact-us.php') }}">Ask About A Format</a>
                </div>
                <div class="formats-jump-nav" aria-label="Format sections">
                    <a href="#embroidery-formats">Machine Embroidery</a>
                    <a href="#vector-formats">Vector File</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="embroidery-formats">
        <div class="container">
            <div class="section-card">
                <div class="section-head legacy-underline" style="text-align:left;margin-bottom:14px;">
                    <h2>Machine Embroidery Formats</h2>
                </div>
                <div class="copy">
                    <p>Different machines read different file types — a Brother PES file won't run on a Tajima, and a DST file won't load into a Janome JEF machine without conversion. <strong>1 Dollar Digitizing</strong> delivers in the exact format your machine reads, so you can load and run without extra steps.</p>
                    <div class="service-offers-block" style="margin-top:18px;">
                        <h3>Our Supported Machine Embroidery Formats are as Follows</h3>
                        <ul class="service-offers-list">
                            <li><strong>DST</strong> — Tajima (industry standard for commercial machines)</li>
                            <li><strong>PES</strong> — Brother and Babylock machines</li>
                            <li><strong>EXP</strong> — Melco and Ameco machines</li>
                            <li><strong>VP3</strong> — Pfaff and Husqvarna Viking machines</li>
                            <li><strong>JEF</strong> — Janome machines</li>
                            <li><strong>XXX</strong> — Singer machines</li>
                            <li><strong>HUS</strong> — Husqvarna machines</li>
                            <li><strong>SEW</strong> — Elna and Janome machines</li>
                            <li><strong>VIP</strong> — Pfaff and Viking (older format)</li>
                            <li><strong>EMB</strong> — Wilcom native format</li>
                            <li><strong>OFM</strong> — Melco Ameco format</li>
                            <li><strong>CSD</strong> — Poem, Huskygram, Singer</li>
                            <li><strong>PHC</strong> — Brother photo stitch format</li>
                        </ul>
                    </div>
                </div>
                <div class="media-frame format-chart-frame">
                    <img src="{{ $legacyAssetBase }}/images/Digitizing-Formats.png" alt="Machine embroidery format reference chart — DST, PES, EXP, VP3, JEF, XXX and more">
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="vector-formats">
        <div class="container">
            <div class="section-card">
                <div class="section-head legacy-underline" style="text-align:left;margin-bottom:14px;">
                    <h2>Vector File Formats</h2>
                </div>
                <div class="copy">
                    <p>Unlike raster images, vector files scale to any size without losing quality. A logo built as a vector is just as clean at 2 inches as it is at 20 feet — which is why print shops, sign makers, and screen printers all ask for it. We deliver in whichever format the downstream vendor needs.</p>
                    <div class="service-offers-block" style="margin-top:18px;">
                        <h3>Vector File Formats and Extensions We Offer</h3>
                        <ul class="service-offers-list">
                            <li><strong>AI</strong> — Adobe Illustrator (preferred by print studios and designers)</li>
                            <li><strong>EPS</strong> — Encapsulated PostScript (universal, accepted everywhere)</li>
                            <li><strong>SVG</strong> — Scalable Vector Graphics (web, vinyl cutting, laser engravers)</li>
                            <li><strong>PDF</strong> — Portable Document Format (print-ready with embedded paths)</li>
                            <li><strong>CDR</strong> — CorelDRAW format (on request)</li>
                        </ul>
                    </div>
                </div>
                <div class="media-frame format-chart-frame">
                    <img src="{{ $legacyAssetBase }}/images/Vector-File-Formats.jpg" alt="Vector file format reference chart — AI, EPS, SVG, PDF and more">
                </div>
            </div>
        </div>
    </section>

    <style>
        .format-chart-frame {
            margin-top: 20px;
            padding: 14px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 18px 38px rgba(12, 48, 89, 0.10);
        }

        .format-chart-frame img {
            display: block;
            width: 100%;
            height: auto;
            max-height: none;
            object-fit: contain;
        }

        .formats-jump-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin: 20px 0 0;
        }

        .formats-jump-nav a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 18px;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(22, 159, 230, 0.10) 0%, rgba(22, 159, 230, 0.04) 100%);
            border: 1px solid rgba(22, 159, 230, 0.16);
            color: #0d6ea3;
            font-weight: 700;
            box-shadow: 0 12px 24px rgba(12, 48, 89, 0.07);
        }

        .formats-jump-nav a:hover {
            background: #169fe6;
            color: #fff;
        }
    </style>
@endsection
