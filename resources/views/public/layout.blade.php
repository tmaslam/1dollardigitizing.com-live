<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $legacyAssetBase = rtrim(url('/'), '/');
        $seoTitle = html_entity_decode(trim($__env->yieldContent('title', $siteContext->displayLabel())), ENT_QUOTES, 'UTF-8');
        $seoDescription = trim(preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('meta_description', 'Embroidery digitizing from $1 per design. Logo digitizing, 3D puff, applique, vector art and more. All machine formats supported, 24-hour turnaround, free revisions.'))));
        $seoCanonical = trim($__env->yieldContent('canonical', url()->current()));
        $seoRobots = trim($__env->yieldContent('meta_robots', 'index,follow,max-image-preview:large'));
        $seoImage = trim($__env->yieldContent('meta_image', $legacyAssetBase.'/images/logo.webp'));
        $seoType = trim($__env->yieldContent('meta_og_type', 'website'));
        $seoTwitterCard = trim($__env->yieldContent('twitter_card', 'summary_large_image'));
        $siteBaseUrl = rtrim(url('/'), '/');
        $supportEmail = $siteContext->supportEmail !== '' ? $siteContext->supportEmail : (string) config('mail.admin_alert_address', '');

        if ($seoImage !== '' && ! \Illuminate\Support\Str::startsWith($seoImage, ['http://', 'https://'])) {
            $seoImage = url($seoImage);
        }

        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => $siteBaseUrl.'/#organization',
            'name' => $siteContext->displayLabel(),
            'url' => $siteBaseUrl.'/',
            'logo' => $legacyAssetBase.'/images/logo.png',
            'telephone' => $siteContext->phoneNumber,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $siteContext->companyAddress,
                'addressCountry' => 'US',
            ],
        ];

        if ($supportEmail !== '') {
            $organizationSchema['email'] = $supportEmail;
            $organizationSchema['contactPoint'] = [[
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => $supportEmail,
                'telephone' => $siteContext->phoneNumber,
            ]];
        }

        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $siteBaseUrl.'/#website',
            'url' => $siteBaseUrl.'/',
            'name' => $siteContext->displayLabel(),
            'publisher' => ['@id' => $siteBaseUrl.'/#organization'],
        ];

        $pageSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'url' => $seoCanonical,
            'name' => $seoTitle,
            'description' => $seoDescription,
            'isPartOf' => ['@id' => $siteBaseUrl.'/#website'],
        ];

        $publicMenu = [
            ['label' => 'Home', 'href' => url('/')],
            ['label' => 'Services', 'href' => url('/our-services.php')],
            ['label' => 'Pricing', 'href' => url('/price-plan.php')],
            ['label' => 'Process', 'href' => url('/work-process.php')],
            ['label' => 'Formats', 'href' => url('/formats.php')],
            ['label' => 'About', 'href' => url('/about-us.php')],
            ['label' => 'Blog', 'href' => url('/blog')],
            ['label' => 'Stock Designs', 'href' => 'https://www.myembdesigns.com/', 'external' => true],
            ['label' => 'Contact', 'href' => url('/contact-us.php')],
        ];
        $serviceLinks = [
            ['label' => 'Embroidery Digitizing', 'href' => url('/embroidery-digitizing.php')],
            ['label' => '3D / Puff Embroidery', 'href' => url('/3d-puff-embroidery-digitizing.php')],
            ['label' => 'Applique Embroidery', 'href' => url('/applique-embroidery-digitizing.php')],
            ['label' => 'Chain Stitch Embroidery', 'href' => url('/chain-stitch-embroidery-digitizing.php')],
            ['label' => 'Photo Digitizing', 'href' => url('/photo-digitizing.php')],
            ['label' => 'Vector Art', 'href' => url('/vector-art.php')],
        ];
        $companyLinks = [
            ['label' => 'About Us', 'href' => url('/about-us.php')],
            ['label' => 'Process', 'href' => url('/work-process.php')],
            ['label' => 'Formats', 'href' => url('/formats.php')],
            ['label' => 'Blog', 'href' => url('/blog')],
            ['label' => 'Pricing', 'href' => url('/price-plan.php')],
            ['label' => 'Contact', 'href' => url('/contact-us.php')],
        ];
    @endphp
    <title>{{ $seoTitle }}</title>
    <link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}">
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:site_name" content="{{ $siteContext->displayLabel() }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="{{ $seoTwitterCard }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <script type="application/ld+json">@json($organizationSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)</script>
    <script type="application/ld+json">@json($websiteSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)</script>
    <script type="application/ld+json">@json($pageSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)</script>
    @hasSection('structured_data')
        <script type="application/ld+json">{!! trim($__env->yieldContent('structured_data')) !!}</script>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css">
    <link rel="stylesheet" href="{{ url('/css/front-theme-overrides.css') }}?v={{ filemtime(public_path('css/front-theme-overrides.css')) }}">
    <style>
        body.front-theme.public-theme *,
        body.front-theme.public-theme *::before,
        body.front-theme.public-theme *::after {
            box-sizing: border-box;
        }

        body.front-theme.public-theme .container {
            width: min(1220px, calc(100% - 28px)) !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        body.front-theme.public-theme .marketing-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #ffffff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.07);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        body.front-theme.public-theme .marketing-header-shell {
            min-height: 66px;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            grid-template-areas: "brand nav actions";
            align-items: center;
            gap: 18px;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        body.front-theme.public-theme .marketing-brand {
            grid-area: brand;
            display: inline-flex;
            align-items: center;
            min-width: 0;
        }

        body.front-theme.public-theme .marketing-brand img {
            height: 52px;
            width: auto;
            max-width: 100%;
            display: block;
        }

        body.front-theme.public-theme .marketing-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border: 1.5px solid rgba(15, 23, 42, 0.25);
            border-radius: 8px;
            background: transparent;
            color: #0f172a;
            font-family: "Inter", "Segoe UI", sans-serif;
            font-size: 0.87rem;
            font-weight: 600;
            box-shadow: none;
        }

        body.front-theme.public-theme .marketing-nav {
            grid-area: nav;
            display: flex;
            align-items: center;
            align-self: center;
            min-width: 0;
            margin-left: 24px;
        }

        body.front-theme.public-theme .marketing-nav-list {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            width: 100%;
            min-width: 0;
            margin: 0;
            padding: 0;
            border-radius: 0;
            background: transparent;
            border: 0;
            box-shadow: none;
        }

        body.front-theme.public-theme .marketing-nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 0.8rem;
            border-radius: 6px;
            color: rgba(15, 23, 42, 0.72);
            text-decoration: none;
            font-family: "Inter", "Segoe UI", sans-serif;
            font-size: 0.87rem;
            font-weight: 500;
            line-height: 1;
            white-space: nowrap;
            transition: color 0.18s ease, background 0.18s ease;
        }

        body.front-theme.public-theme .marketing-nav-link:hover,
        body.front-theme.public-theme .marketing-nav-link.active {
            background: rgba(15, 23, 42, 0.08);
            color: #0f172a;
            box-shadow: none;
        }

        body.front-theme.public-theme .marketing-nav-link[href*="myembdesigns"] {
            background: #fedf33;
            color: #333e48;
            font-weight: 700;
            border-radius: 6px;
        }
        body.front-theme.public-theme .marketing-nav-link[href*="myembdesigns"]:hover {
            background: #e7c400;
            color: #29323a;
        }

        body.front-theme.public-theme .marketing-actions {
            grid-area: actions;
            display: flex;
            align-items: center;
            align-self: center;
            gap: 0.45rem;
            justify-content: flex-end;
            margin-left: 16px;
            margin-top: 0;
        }

        body.front-theme.public-theme .hdr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 16px;
            border-radius: 999px;
            font-family: "Inter", "Segoe UI", sans-serif;
            font-size: 0.87rem;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        body.front-theme.public-theme .hdr-btn--ghost {
            background: transparent;
            border: 1.5px solid rgba(15, 23, 42, 0.20);
            color: rgba(15, 23, 42, 0.85);
        }

        body.front-theme.public-theme .hdr-btn--ghost:hover {
            border-color: rgba(15, 23, 42, 0.40);
            color: #0f172a;
            background: rgba(15, 23, 42, 0.06);
        }

        body.front-theme.public-theme .hdr-btn--cta {
            background: #169fe6;
            border: 0;
            color: #ffffff;
            box-shadow: none;
        }

        body.front-theme.public-theme .hdr-btn--cta:hover {
            background: #0d6ea3;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.38);
            transform: translateY(-1px);
        }

        @media (max-width: 900px) {
            body.front-theme.public-theme .marketing-header-shell {
                grid-template-columns: minmax(0, 1fr) auto;
                grid-template-areas:
                    "brand toggle"
                    "actions actions"
                    "nav nav";
                gap: 12px;
                min-height: auto;
            }

            body.front-theme.public-theme .marketing-brand {
                grid-area: brand;
            }

            body.front-theme.public-theme .marketing-brand img {
                max-width: 150px;
                height: auto;
            }

            body.front-theme.public-theme .marketing-toggle {
                grid-area: toggle;
                display: inline-flex;
                justify-self: end;
            }

            body.front-theme.public-theme .marketing-actions {
                grid-area: actions;
                margin-left: 0;
                position: static;
                top: auto;
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                width: 100%;
                gap: 8px;
            }

            body.front-theme.public-theme .hdr-btn {
                min-height: 42px;
                width: 100%;
                justify-content: center;
                white-space: normal;
                padding: 10px 12px;
                border-radius: 12px;
            }

            body.front-theme.public-theme .marketing-nav {
                grid-area: nav;
                margin-left: 0;
                display: none;
                width: 100%;
            }

            body.front-theme.public-theme .marketing-nav.open {
                display: block;
            }

            body.front-theme.public-theme .marketing-nav-list {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0.2rem;
                padding: 0.75rem;
                border-radius: 8px;
                background: #f8fafc;
                border: 1px solid rgba(0, 0, 0, 0.08);
            }

            body.front-theme.public-theme .marketing-nav-link {
                width: 100%;
                justify-content: flex-start;
                text-align: left;
                white-space: normal;
                color: rgba(15, 23, 42, 0.78);
            }
        }

        @media (max-width: 640px) {
            body.front-theme.public-theme .marketing-header-shell {
                gap: 8px;
            }

            body.front-theme.public-theme .marketing-brand img {
                max-width: 122px;
            }

            body.front-theme.public-theme .marketing-actions {
                grid-template-columns: 1fr;
            }

            body.front-theme.public-theme .hdr-btn {
                border-radius: 10px;
            }

            body.front-theme.public-theme .marketing-toggle {
                padding-left: 14px;
                padding-right: 14px;
            }
        }

        /* Footer — hero background color */
        body.front-theme.public-theme .footer {
            background: linear-gradient(135deg, #eaf2f8 0%, #ffffff 100%) !important;
            color: #0f172a !important;
            padding: 4rem 0 0 !important;
            margin-top: 0 !important;
        }
        body.front-theme.public-theme .footer .footer-grid {
            grid-template-columns: repeat(4, 1fr) !important;
            align-items: stretch !important;
        }
        body.front-theme.public-theme .footer .footer-brand-block,
        body.front-theme.public-theme .footer .footer-column,
        body.front-theme.public-theme .footer .footer-grid > div {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06) !important;
            border-radius: 20px !important;
            text-align: center !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
            padding-top: 1.8rem !important;
        }
        body.front-theme.public-theme .footer .footer-brand-block p,
        body.front-theme.public-theme .footer .footer-links,
        body.front-theme.public-theme .footer .footer-cta-group,
        body.front-theme.public-theme .footer .footer-brand-pills {
            justify-content: center !important;
        }
        body.front-theme.public-theme .footer .footer-links li {
            text-align: center !important;
        }
        body.front-theme.public-theme .footer .footer-links li a {
            display: inline-block !important;
        }
        body.front-theme.public-theme .footer-logo {
            margin-left: auto !important;
            margin-right: auto !important;
            display: block !important;
            height: 72px !important;
            width: auto !important;
        }
        body.front-theme.public-theme .footer h4,
        body.front-theme.public-theme .footer strong {
            color: #0f172a !important;
            font-size: 0.74rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
        }
        body.front-theme.public-theme .footer a,
        body.front-theme.public-theme .footer .footer-links li,
        body.front-theme.public-theme .footer .footer-brand-block p {
            color: #0f172a !important;
        }
        body.front-theme.public-theme .footer a:hover {
            color: #169fe6 !important;
        }
        body.front-theme.public-theme .footer .footer-brand-pills span {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #0f172a !important;
        }
        body.front-theme.public-theme .footer .button.secondary,
        body.front-theme.public-theme .footer a.button.secondary {
            background: #ffffff !important;
            border: 1.5px solid #d1d5db !important;
            color: #0f172a !important;
            box-shadow: none !important;
        }
        body.front-theme.public-theme .footer .button.secondary:hover {
            border-color: #169fe6 !important;
            color: #169fe6 !important;
            background: #f0f9ff !important;
        }
        body.front-theme.public-theme .footer .button.primary,
        body.front-theme.public-theme .footer a.button.primary {
            background: linear-gradient(135deg, #169fe6 0%, #0d6ea3 100%) !important;
            border: 0 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(22, 159, 230, 0.22) !important;
        }
        body.front-theme.public-theme .footer .footer-bottom-wrap {
            background: linear-gradient(135deg, #169fe6 0%, #0d6ea3 100%) !important;
            border-top: 0 !important;
            margin-top: 2rem;
            border-radius: 0 0 18px 18px;
        }
        body.front-theme.public-theme .footer .footer-bottom {
            border-top: 0 !important;
            padding: 1rem 0 !important;
        }
        body.front-theme.public-theme .footer .footer-bottom p,
        body.front-theme.public-theme .footer .footer-bottom-links a {
            color: #ffffff !important;
            font-size: 0.84rem;
        }
        body.front-theme.public-theme .footer-logo {
            filter: none !important;
        }
        /* Footer responsive */
        @media (max-width: 860px) {
            body.front-theme.public-theme .footer .footer-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        @media (max-width: 480px) {
            body.front-theme.public-theme .footer .footer-grid {
                grid-template-columns: 1fr !important;
                gap: 0.875rem !important;
            }
            body.front-theme.public-theme .footer .footer-column {
                text-align: left !important;
                padding: 1.25rem !important;
            }
            body.front-theme.public-theme .footer .footer-column .footer-links li {
                text-align: left !important;
            }
        }
        /* Stat numbers */
        body.front-theme.public-theme .stat-number {
            color: #169fe6 !important;
        }
        /* Stat cards — flat, no box */
        body.front-theme.public-theme .stats-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            max-width: 100% !important;
            gap: 0 !important;
        }
        body.front-theme.public-theme .stat-card {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        /* Hero badge */
        body.front-theme.public-theme .hero-badge {
            background: transparent !important;
            border: 1px solid rgba(37, 99, 235, 0.22) !important;
            color: #0d6ea3 !important;
        }
        /* Section labels */
        body.front-theme.public-theme .section-label {
            background: rgba(37, 99, 235, 0.04) !important;
            border: 1px solid rgba(37, 99, 235, 0.22) !important;
            color: #169fe6 !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
            padding: 0.28rem 0.75rem !important;
            border-radius: 999px !important;
            font-size: 0.78rem !important;
            font-weight: 600 !important;
            display: inline-block !important;
        }
        /* Primary buttons */
        body.front-theme.public-theme .button.primary {
            background: #169fe6 !important;
        }
        body.front-theme.public-theme .hdr-btn--cta {
            background: #169fe6 !important;
        }
        /* Section heading accent spans */
        body.front-theme.public-theme .section-header h2 span,
        body.front-theme.public-theme h2 span {
            color: #169fe6 !important;
        }
        /* Blue CTA card heading span — amber accent on blue bg */
        body.front-theme.public-theme .blue-cta-left h2 span {
            color: #fbbf24 !important;
        }
    </style>
</head>
<body class="front-theme public-theme">
    <div class="site-frame">
        <div class="top-bar">
            <div class="container topbar-inner">
                <span class="template-topbar-message">
                    Trusted Since 2005 | Custom Embroidery Digitizing &amp; Vector Art
                    <span class="template-topbar-separator">—</span>
                    @if ($siteContext->phoneNumber)<a href="tel:{{ $siteContext->phoneForTel() }}">Call Us: {{ $siteContext->phoneNumber }}</a>@endif
                </span>
            </div>
        </div>

        <header class="marketing-header">
            <div class="container marketing-header-shell">
                <a href="{{ url('/') }}" class="marketing-brand">
                    <img class="site-logo" src="{{ $legacyAssetBase }}/images/logo.png" alt="1 Dollar Digitizing">
                </a>

                <button class="marketing-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="public-navigation">Menu</button>

                <div class="marketing-actions">
                    @if (session()->has('customer_user_id'))
                        <a class="hdr-btn hdr-btn--ghost" href="{{ url('/dashboard.php') }}">Dashboard</a>
                        <a class="hdr-btn hdr-btn--ghost" href="{{ url('/logout.php') }}">Logout</a>
                        <a class="hdr-btn hdr-btn--cta" href="{{ url('/book-a-meeting.php') }}">Book a Meeting</a>
                    @else
                        <a class="hdr-btn hdr-btn--ghost" href="{{ url('/login.php') }}">Login</a>
                        <a class="hdr-btn hdr-btn--ghost" href="{{ url('/sign-up.php') }}">Sign Up</a>
                        <a class="hdr-btn hdr-btn--cta" href="{{ url('/book-a-meeting.php') }}">Book a Meeting</a>
                    @endif
                </div>

                <nav class="marketing-nav" id="public-navigation">
                    <div class="marketing-nav-list">
                        @foreach ($publicMenu as $item)
                            @php
                                $currentPath = request()->path();
                                $active = empty($item['external']) && ($currentPath === ltrim($item['href'], '/') || ($item['href'] === '/' && ($currentPath === '/' || $currentPath === '')));
                            @endphp
                            <a class="marketing-nav-link {{ $active ? 'active' : '' }}"
                               href="{{ $item['href'] }}"
                               @if (!empty($item['external'])) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            </div>
         </header>

        <main class="page-content">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand-block">
                        <img class="footer-logo" src="{{ $legacyAssetBase }}/images/logo.png" alt="1 Dollar Digitizing">
                        <p>Professional embroidery digitizing services<br>at affordable prices.<br>Quality you can count on.</p>
                    </div>

                    <div class="footer-column">
                        <h4>Services</h4>
                        <ul class="footer-links">
                            @foreach ($serviceLinks as $item)
                                <li>
                                    <a href="{{ $item['href'] }}"
                                       @if (!empty($item['external'])) target="_blank" rel="noopener noreferrer" @endif>
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Company</h4>
                        <ul class="footer-links">
                            @foreach ($companyLinks as $item)
                                <li><a href="{{ $item['href'] }}">{{ $item['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="footer-column footer-contact-block">
                        <h4>Contact</h4>
                        <ul class="footer-links">
                            @if ($siteContext->phoneNumber)<li><a href="tel:{{ $siteContext->phoneForTel() }}">{{ $siteContext->phoneNumber }}</a></li>@endif
                            @if ($siteContext->companyAddress)<li>{{ $siteContext->companyAddress }}</li>@endif
                            @if ($supportEmail !== '')
                                <li><a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
                            @endif
                        </ul>

                    </div>
                </div>
            </div>

            <div class="footer-bottom-wrap">
                <div class="container">
                    <div class="footer-bottom" style="margin-top:0;padding-top:0;">
                        <p>&copy; {{ date('Y') }} 1Dollar Digitizing. All rights reserved.</p>
                        <p>Hand-built stitch files since 2005.</p>
                        <div class="footer-bottom-links">
                            <a href="{{ url('/privacy-policy.php') }}">Privacy Policy</a>
                            <a href="{{ url('/terms.php') }}">Terms</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggle = document.querySelector('[data-nav-toggle]');
            var navigation = document.getElementById('public-navigation');

            if (toggle && navigation) {
                toggle.addEventListener('click', function () {
                    var isOpen = navigation.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            }

            document.querySelectorAll('form[data-validate-form]').forEach(function (form) {
                var controls = Array.prototype.slice.call(form.querySelectorAll('input, select, textarea')).filter(function (control) {
                    return control.type !== 'hidden' && control.type !== 'submit' && control.type !== 'button' && control.type !== 'reset';
                });

                function fieldContainer(control) {
                    return control.closest('[data-form-field]') || control.closest('label') || control.parentElement;
                }

                function fieldErrorNode(control) {
                    var container = fieldContainer(control);
                    return container ? container.querySelector('[data-field-error]') : null;
                }

                function renderError(control, isValid, message) {
                    var error = fieldErrorNode(control);
                    control.classList.toggle('is-invalid', !isValid);
                    control.setAttribute('aria-invalid', isValid ? 'false' : 'true');

                    if (error) {
                        error.textContent = isValid ? '' : message;
                    }
                }

                function validateControl(control) {
                    if (control.disabled) {
                        return true;
                    }

                    var valid = control.checkValidity();
                    renderError(control, valid, valid ? '' : control.validationMessage);
                    return valid;
                }

                controls.forEach(function (control) {
                    control.addEventListener('blur', function () {
                        validateControl(control);
                    });

                    control.addEventListener('input', function () {
                        if (control.classList.contains('is-invalid') || control.getAttribute('aria-invalid') === 'true') {
                            validateControl(control);
                        }
                    });
                });

                form.addEventListener('submit', function (event) {
                    var firstInvalid = null;

                    controls.forEach(function (control) {
                        if (!validateControl(control) && !firstInvalid) {
                            firstInvalid = control;
                        }
                    });

                    if (firstInvalid) {
                        event.preventDefault();
                        firstInvalid.focus();
                    }
                });
            });
        });
    </script>

    {{-- Scroll-to-top button --}}
    <style>
        #scroll-top-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 110;
            border: none;
            background: none;
            padding: 0;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transform: translateY(14px);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
        }
        #scroll-top-btn:hover { transform: translateY(-5px); }
        #scroll-top-btn.visible { opacity: 1; visibility: visible; transform: translateY(0); }
        .stt-r {
            display: block;
            font-size: 2.6rem;
            line-height: 1;
            transform: rotate(-45deg);
        }
        .stt-f {
            display: block;
            width: 8px;
            height: 14px;
            margin-top: -3px;
            background: linear-gradient(to bottom, #ffe082, #ff9800 50%, transparent);
            border-radius: 40% 40% 50% 50%;
            filter: blur(2px);
            animation: stt-burn 0.28s ease-in-out infinite alternate;
        }
        @keyframes stt-burn {
            from { height: 10px; opacity: 0.75; transform: scaleX(0.8); }
            to   { height: 18px; opacity: 1;    transform: scaleX(1.2); }
        }
    </style>
    <button id="scroll-top-btn" aria-label="Back to top" title="Back to top">
        <span class="stt-r">🚀</span><span class="stt-f"></span>
    </button>
    <script>
        (function () {
            var btn = document.getElementById('scroll-top-btn');
            if (!btn) return;
            window.addEventListener('scroll', function () {
                btn.classList.toggle('visible', window.scrollY > 320);
            }, { passive: true });
            btn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }());
    </script>
</body>
</html>
