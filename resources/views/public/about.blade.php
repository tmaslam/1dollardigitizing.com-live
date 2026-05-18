@extends('public.layout')

@section('title', 'About 1 Dollar Digitizing | Embroidery Digitizing Experts Since 2005')
@section('meta_description', 'Founded by Tariq Aslam with 20+ years in garment manufacturing. 1 Million+ designs digitized, 10,000+ customers, offices in US, UK & Pakistan. From $1 per 1,000 stitches.')

@section('content')
    @php
        $legacyAssetBase = rtrim(url('/'), '/');
        $stats = [
            ['number' => '2005', 'label' => 'Year Founded'],
            ['number' => '10K+', 'label' => 'Happy Customers'],
            ['number' => '1M+', 'label' => 'Designs Completed'],
        ];
        $milestones = [
            ['year' => '2005', 'text' => 'Founded by Tariq Aslam. First clients served from a small but disciplined operation built around clean files and fair pricing.'],
            ['year' => '2006', 'text' => 'Landed our first large-scale U.S. promotional apparel partner — 200 to 300 logos per day. Built the workflow systems that still run the business today.'],
            ['year' => '2010', 'text' => 'Expanded the in-house digitizing team and added vector art as a full service line. Added file format coverage for all major machine brands.'],
            ['year' => '2015', 'text' => 'Opened offices in the United Kingdom and Pakistan to deliver around-the-clock support across time zones.'],
            ['year' => '2020', 'text' => 'Surpassed 500,000 designs completed. Introduced rush turnaround tiers (12-hour and 8-hour) for deadline-driven production shops.'],
            ['year' => 'Today', 'text' => 'Over 1 million designs delivered to more than 10,000 customers across the globe. The 2006 foundational client is still with us.'],
        ];
        $software = [
            [
                'img'   => url('/images/wilcom-studio-icon.png'),
                'abbr'  => 'WI',
                'color' => '#1a8a3c',
                'bg'    => '#e8f5ee',
                'name'  => 'Wilcom Embroidery Studio e4.5',
                'tag'   => 'Industry Standard',
                'desc'  => 'The world\'s most widely used professional digitizing platform. We rely on it for complex multi-color logos, fine lettering, and any design that demands precision at the stitch level.',
                'use'   => 'Complex logos · Fine detail · Multi-color work',
            ],
            [
                'img'   => url('/images/dg16-icon.png'),
                'abbr'  => 'TJ',
                'color' => '#c41e1e',
                'bg'    => '#fdeaea',
                'name'  => 'Tajima DG16 by Pulse',
                'tag'   => 'Tajima Optimized',
                'desc'  => 'Built specifically for Tajima machine operators. We use DG16 to produce files tuned for Tajima hardware — correct needle sequencing, optimized jump stitches, and clean lock-off points.',
                'use'   => 'Tajima hardware · Jump optimization · Production runs',
            ],
            [
                'img'   => url('/images/wings-xp-icon.gif'),
                'abbr'  => 'WX',
                'color' => '#1d5db5',
                'bg'    => '#e8effe',
                'name'  => 'Wings XP 6',
                'tag'   => 'Broad Compatibility',
                'desc'  => 'A versatile punching platform with deep format support. Wings XP 6 gives us the flexibility to output production-ready files for virtually any embroidery machine brand on the market.',
                'use'   => 'Format flexibility · Multi-brand output · Flat embroidery',
            ],
            [
                'img'   => url('/images/aps-ethos-icon.png'),
                'abbr'  => 'AP',
                'color' => '#7c3aed',
                'bg'    => '#f3eeff',
                'name'  => 'APS Ethos',
                'tag'   => 'Specialty Stitching',
                'desc'  => 'Purpose-built for high-density and specialty stitch work. APS Ethos is our go-to for 3D puff digitizing, applique tackdown paths, and designs that require tightly controlled stitch density.',
                'use'   => '3D Puff · Applique · High-density work',
            ],
            [
                'img'   => url('/images/designshop-v11-icon.jpg'),
                'abbr'  => 'DS',
                'color' => '#0e7490',
                'bg'    => '#e0f6fa',
                'name'  => 'DesignShop v11',
                'tag'   => 'Melco / AMECO',
                'desc'  => 'Melco\'s professional digitizing suite, used to ensure full compatibility for customers running Melco or AMECO multi-head machines. No conversion artifacts, no format issues.',
                'use'   => 'Melco machines · AMECO · Commercial multi-head',
            ],
        ];
        $services = [
            ['icon' => '🧵', 'title' => 'Custom Embroidery Digitizing', 'desc' => 'Logos, patches, left-chest designs, jacket backs — hand-built for clean, efficient stitchout on any multi-head machine.'],
            ['icon' => '🧢', 'title' => '3D Puff Embroidery', 'desc' => 'Correct foam specs, tight satin coverage, clean edges. No blowout. No thread breaks. Included at no extra charge.'],
            ['icon' => '🎨', 'title' => 'Applique & Chain Stitch', 'desc' => 'Precision tackdown paths for applique and authentic chain stitch for western wear, vintage brands, and decorative work.'],
            ['icon' => '🖼️', 'title' => 'Photo Digitizing', 'desc' => 'Portraits, pets, and memorial patches interpreted stitch by stitch. You get a preview before the final file ships.'],
            ['icon' => '✏️', 'title' => 'Vector Art & Redrawing', 'desc' => 'Clean, scalable AI, EPS, SVG, and PDF files for print shops, sign makers, and screen printers.'],
            ['icon' => '⚡', 'title' => 'Rush Turnaround', 'desc' => '12-hour and 8-hour rush options for when a deadline doesn\'t negotiate. Same quality, faster delivery.'],
        ];
        $guarantees = [
            ['icon' => '✓', 'title' => '100% Satisfaction Guarantee', 'desc' => 'If our digitizing causes a stitchout issue, we fix it free — no argument, no runaround. Our reputation depends on getting it right.'],
            ['icon' => '🔄', 'title' => 'Free Revisions', 'desc' => 'Design revisions are part of the job. We don\'t charge for legitimate fix requests on files we built.'],
            ['icon' => '💾', 'title' => 'Free Backups', 'desc' => 'Every file we produce is backed up. If you lose a file or need a different format years later, just ask.'],
            ['icon' => '🕐', 'title' => '24/7 Customer Support', 'desc' => 'With offices across three time zones, someone is always available when you need a status update or have a question.'],
        ];
    @endphp

    <div class="about-page">
    <section class="page-header">
        <div class="container">
            <div class="about-page-header">
                <span class="section-label">Our Story</span>
                <h1>About <span>1 Dollar Digitizing</span></h1>
                <p style="text-align:center;max-width:620px;margin:0 auto;line-height:1.72;word-wrap:break-word;overflow-wrap:break-word;">We have been building embroidery files by hand since 2005. No automation shortcuts, no offshore file farms — just experienced digitizers who know what a clean file looks like and why it matters on your production floor.</p>
            </div>
        </div>
    </section>

    {{-- Stats bar --}}
    <section class="section section-flush-top">
        <div class="container">
            <div class="stats-grid about-stats-row">
                @foreach ($stats as $stat)
                    <div class="stat-card">
                        <div class="stat-number">{{ $stat['number'] }}</div>
                        <div class="stat-label">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Founder Story --}}
    <section class="section">
        <div class="container">
            <div class="section-card">
                <div class="founder-layout">
                    <div class="founder-body">
                        <span class="section-label" style="display:inline-block;width:fit-content;">Our Founder</span>
                        <h2>Built by Someone Who <span>Knows the Floor</span></h2>
                        <p>1 Dollar Digitizing was founded by <strong>Tariq Aslam</strong> — not a programmer who stumbled into embroidery, but someone who spent decades inside the garment manufacturing industry. Tariq served five years as a Quality Assurance Inspector for <strong>Target Corporation</strong> across the Gulf region, evaluating production standards for one of the world's largest retail buyers. He knows exactly what global apparel brands demand, and he built this company around those same standards.</p>
                        <p>That background shapes everything we do. When our digitizers build a file, they are not just thinking about how it looks on screen — they are thinking about how it runs on a 12-head Tajima at 800 stitches per minute, with a production operator who can't stop mid-run to fix a thread break. Files built by people who have never been on a production floor are files that cause problems. We don't build those files.</p>
                        <p>Our journey into large-scale digitizing began in <strong>2006</strong> when a major U.S. promotional apparel company asked us to take on their full daily volume — between 200 and 300 logos every single day. We built the systems, the team, and the quality controls to meet that demand reliably. That foundational client has been with us ever since.</p>
                    </div>
                    <div class="founder-aside">
                        <div class="founder-card">
                            <img src="{{ url('/images/avatar.jpg') }}" alt="Tariq Aslam" class="founder-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="founder-avatar" style="display:none;">TA</div>
                            <div class="founder-card-name">Tariq Aslam</div>
                            <div class="founder-card-title">Founder &amp; President</div>
                            <div class="founder-card-company">1 Dollar Digitizing</div>
                            <div class="founder-card-divider"></div>
                            <ul class="founder-card-facts">
                                <li>
                                    <span class="founder-fact-label">Experience</span>
                                    <span class="founder-fact-val">20+ years in garment manufacturing</span>
                                </li>
                                <li>
                                    <span class="founder-fact-label">Previous role</span>
                                    <span class="founder-fact-val">QA Inspector, Target Corporation</span>
                                </li>
                                <li>
                                    <span class="founder-fact-label">Region</span>
                                    <span class="founder-fact-val">Gulf region &amp; global apparel brands</span>
                                </li>
                                <li>
                                    <span class="founder-fact-label">Founded</span>
                                    <span class="founder-fact-val">2005</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Timeline --}}
    <section class="section section-surface">
        <div class="container">
            <div class="section-card">
                <div class="section-header">
                    <span class="section-label">Milestones</span>
                    <h2>Two Decades of <span>Consistent Work</span></h2>
                    <p>We didn't grow by cutting corners. We grew because shops kept coming back.</p>
                </div>
                <div class="milestone-cards">
                    @foreach ($milestones as $m)
                        <div class="milestone-card">
                            <div class="milestone-year">{{ $m['year'] }}</div>
                            <p class="milestone-text">{{ $m['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="section">
        <div class="container">
            <div class="section-card">
                <div class="section-header">
                    <span class="section-label">What We Do</span>
                    <h2>Every Service Your <span>Shop Needs</span></h2>
                    <p>From a single hat logo to a daily volume of hundreds — we have the capacity, the software, and the experience to handle it all.</p>
                </div>
                <div class="about-services-grid">
                    @foreach ($services as $s)
                        <div class="about-service-item">
                            <div class="about-service-icon">{{ $s['icon'] }}</div>
                            <h3>{{ $s['title'] }}</h3>
                            <p>{{ $s['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="about-pricing-callout">
                    <strong>Pricing:</strong> $1.00 per 1,000 stitches &mdash; $6.00 minimum. No setup fees, no complexity surcharges. Vector art at $6 per hour. Rush turnaround available on request.
                </div>
            </div>
        </div>
    </section>

    {{-- Technology --}}
    <section class="section section-surface">
        <div class="container">
            <div class="section-card">
                <div class="section-header">
                    <span class="section-label">Our Tools</span>
                    <h2>World-Class Software, <span>Operated by Experts</span></h2>
                    <p>We invest in the best digitizing platforms available — and more importantly, we have digitizers who know how to use them. Software is only as good as the person running it.</p>
                </div>
                <div class="sw-cards">
                    @foreach ($software as $sw)
                        <div class="sw-card">
                            <div class="sw-icon" style="background:{{ $sw['bg'] }};width:72px;height:72px;min-width:72px;min-height:72px;overflow:hidden;">
                                @if (!empty($sw['img']))
                                    <img src="{{ $sw['img'] }}" alt="{{ $sw['name'] }}" width="52" height="52" style="width:52px!important;height:52px!important;max-width:52px!important;max-height:52px!important;object-fit:contain;display:block;">
                                @else
                                    <span style="color:{{ $sw['color'] }};">{{ $sw['abbr'] }}</span>
                                @endif
                            </div>
                            <div class="sw-tag" style="color:{{ $sw['color'] }};background:{{ $sw['bg'] }};">{{ $sw['tag'] }}</div>
                            <h3 class="sw-name">{{ $sw['name'] }}</h3>
                            <p class="sw-desc">{{ $sw['desc'] }}</p>
                            <div class="sw-use">{{ $sw['use'] }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="about-tech-note">
                    <p>Every file we produce is tested against the target format spec before delivery. We support <strong>DST, PES, EXP, VP3, JEF, XXX, HUS, SEW, VIP</strong>, and all other major machine formats. Just tell us what machine you run and we send the right file — no guessing, no conversion artifacts.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Guarantees --}}
    <section class="section">
        <div class="container">
            <div class="section-card">
                <div class="section-header">
                    <span class="section-label">Our Commitments</span>
                    <h2>What You Get <span>Every Time</span></h2>
                    <p>These aren't marketing lines. They are how we have operated since 2005.</p>
                </div>
                <div class="features-grid">
                    @foreach ($guarantees as $g)
                        <article class="feature-item">
                            <div class="feature-icon">{{ $g['icon'] }}</div>
                            <h3>{{ $g['title'] }}</h3>
                            <p>{{ $g['desc'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Who We Serve --}}
    <section class="section section-surface">
        <div class="container">
            <div class="section-card">
                <div class="about-origin-block">
                    <span class="section-label">Who We Work With</span>
                    <h2>Built for Every <span>Type of Shop</span></h2>
                    <p>Our client base spans the full range of the embroidery and apparel industry. Home-based operators who run a single-head machine and need clean files without paying agency rates. Small custom shops handling local schools and sports teams. Mid-size commercial operations with multiple machines running five or six days a week. And large-scale promotional goods distributors who need consistent daily volume turned around without delays.</p>
                    <p>The size of the order doesn't change how we approach the file. A 4,000-stitch left-chest logo gets the same care as a complex 30,000-stitch jacket back. Every design goes through the same quality check before it leaves our team.</p>
                    <p>We also work with contract digitizers, decorators, print shops adding embroidery to their service mix, and sourcing companies that need a reliable digitizing partner they can white-label. If you produce embroidered goods — at any scale — we can support your workflow.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Global Presence --}}
    <section class="section">
        <div class="container">
            <div class="section-card">
                <div class="service-showcase-grid service-showcase-grid-split">
                    <div class="service-showcase-copy">
                        <span class="section-label" style="display:inline-block;width:fit-content;">Global Offices</span>
                        <h2>Around-the-Clock <span>Service</span></h2>
                        <p>With offices in the <strong>United States</strong>, the <strong>United Kingdom</strong>, and <strong>Pakistan</strong>, we operate across three time zones. That means when your shop opens in the morning, your files are already waiting — and when a rush job comes in at 11pm, there is someone on our team to receive it.</p>
                        <p>We serve customers in North America, Europe, Australia, and the Middle East. Our clients include retail apparel brands, custom decorators, sports league suppliers, promotional goods companies, and independent digitizers who need overflow capacity they can trust.</p>
                    </div>
                    <div class="about-offices-panel">
                        <div class="about-office-entry">
                            <div class="about-office-entry-header">
                                <span class="fi fi-us about-office-flag"></span>
                                <strong>US Office — 24/7 Support</strong>
                            </div>
                            <a href="tel:{{ $siteContext->phoneForTel() }}" class="about-office-phone">{{ $siteContext->phoneNumber }}</a>
                            <span class="about-office-address">{{ $siteContext->companyAddress }}</span>
                        </div>
                        <div class="about-office-entry">
                            <div class="about-office-entry-header">
                                <span class="fi fi-gb about-office-flag"></span>
                                <strong>UK Office</strong>
                            </div>
                            <a href="tel:{{ $siteContext->ukPhoneForTel() }}" class="about-office-phone">{{ $siteContext->ukPhoneNumber }}</a>
                            <span class="about-office-address">{{ $siteContext->ukAddress }}</span>
                        </div>
                        <div class="about-office-entry">
                            <div class="about-office-entry-header">
                                <span class="fi fi-pk about-office-flag"></span>
                                <strong>Pakistan Office</strong>
                            </div>
                            <a href="tel:{{ $siteContext->pkPhoneForTel() }}" class="about-office-phone">{{ $siteContext->pkPhoneNumber }}</a>
                            <span class="about-office-address">{{ $siteContext->pkAddress }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section class="section section-surface">
        <div class="container">
            <div class="section-card public-card-center">
                <div class="section-header section-header-tight">
                    <h2>Get in <span>Touch</span></h2>
                    <p>Offices in the U.S., U.K., and Pakistan — someone is always available.</p>
                </div>
                <h3 class="location-title">1 Dollar Digitizing</h3>
                @if ($siteContext->companyAddress)<p class="location-copy">{{ $siteContext->companyAddress }}</p>@endif
                @if ($siteContext->phoneNumber)<p class="location-line"><a href="tel:{{ $siteContext->phoneForTel() }}" class="inline-link">{{ $siteContext->phoneNumber }}</a></p>@endif
                @if ($siteContext->supportEmail)
                    <p><a href="mailto:{{ $siteContext->supportEmail }}" class="inline-link">{{ $siteContext->supportEmail }}</a></p>
                @endif
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section">
        <div class="container">
            <div class="template-cta-card">
                <h2>Want to See What We Can Do?</h2>
                <p>First order is $1. No commitment, no setup fee. Just send us your artwork and see how it comes back.</p>
                <div class="theme-header-actions">
                    <a class="button secondary" href="{{ session()->has('customer_user_id') ? url('/quote.php') : url('/sign-up.php') }}">Get Your Free Quote</a>
                    <a class="button" href="{{ url('/contact-us.php') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
    </div>{{-- .about-page --}}
@endsection
