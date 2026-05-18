<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $siteContext->displayLabel())</title>
    <link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}">
    @php
        $legacyAssetBase = rtrim(url('/'), '/');
        $supportEmail = $siteContext->supportEmail !== '' ? $siteContext->supportEmail : (string) config('mail.admin_alert_address', '');
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
        $resourceLinks = [
            ['label' => 'Our Prices', 'href' => url('/price-plan.php')],
            ['label' => 'Formats', 'href' => url('/formats.php')],
            ['label' => 'Privacy Policy', 'href' => url('/privacy-policy.php')],
            ['label' => 'Terms and Conditions', 'href' => url('/terms.php')],
        ];
    @endphp
    <style>
        :root {
            color-scheme: light;
            --page-bg: #f4f4f4;
            --surface: #ffffff;
            --surface-soft: #f8fbfd;
            --ink: #1f252d;
            --muted: #5e6772;
            --brand: #169fe6;
            --brand-dark: #0d6ea3;
            --line: #dde4ea;
            --shadow: 0 18px 38px rgba(17, 31, 45, 0.12);
            --footer: #111821;
            --max: 1180px;
            --danger: #b8504d;
            --success: #2d7b53;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Roboto", "Segoe UI", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(22, 159, 230, 0.08), transparent 24%),
                linear-gradient(180deg, #f7fbff 0%, #eef4f8 100%);
            line-height: 1.6;
        }

        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; height: auto; display: block; }

        .container {
            width: min(var(--max), calc(100% - 28px));
            margin: 0 auto;
        }

        .site-frame {
            width: min(100%, 1280px);
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 10px 32px rgba(15, 23, 42, 0.08);
        }


        .site-header {
            position: relative;
            z-index: 50;
            background: #ffffff;
            border-bottom: 1px solid #dde4ea;
        }

        .nav-shell {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 76px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
        }

        .brand img {
            height: 78px;
            width: auto;
            max-width: 48vw;
        }

        .nav-toggle {
            display: none;
            border: 1px solid rgba(255,255,255,0.45);
            background: rgba(255,255,255,0.12);
            color: #fff;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav-links a {
            padding: 26px 14px;
            font-size: 15px;
            font-family: 'Roboto Slab', serif;
            color: var(--ink);
            transition: color 0.2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--brand);
        }

        .page-content {
            padding: 40px 0 56px;
        }

        .guest-shell {
            width: min(1120px, 100%);
            margin: 0 auto;
        }

        .panel {
            border-radius: 24px;
            background: #fff;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .intro-panel {
            padding: clamp(28px, 4vw, 42px);
            color: #fff;
            background:
                linear-gradient(rgba(0, 0, 0, 0.48), rgba(0, 0, 0, 0.48)),
                url('{{ $legacyAssetBase }}/images/1dollar-digitizing-banner.webp') center/cover no-repeat;
        }

        .intro-panel span {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            font-size: 0.76rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .intro-panel h1 {
            margin: 18px 0 10px;
            font-size: clamp(2rem, 4.6vw, 3.5rem);
            line-height: 0.98;
            letter-spacing: -0.04em;
        }

        .intro-panel p {
            margin: 0;
            color: rgba(255,255,255,0.88);
            line-height: 1.8;
        }

        .intro-stack {
            display: grid;
            gap: 12px;
            margin-top: 24px;
        }

        .intro-card {
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.16);
        }

        .form-panel {
            padding: clamp(24px, 3vw, 36px);
        }

        .form-panel.auth-panel {
            border-top: 5px solid var(--brand);
        }

        .form-panel h2 {
            margin: 0 0 10px;
            font-size: 1.9rem;
            letter-spacing: -0.04em;
        }

        .muted {
            margin: 0 0 22px;
            color: var(--muted);
            line-height: 1.7;
        }

        .alert {
            margin-bottom: 16px;
            padding: 13px 15px;
            border-radius: 16px;
            border: 1px solid rgba(184,80,77,0.2);
            background: rgba(184,80,77,0.10);
            color: #7c2f2d;
        }

        .alert.success {
            background: rgba(45,123,83,0.10);
            color: #1d5639;
            border-color: rgba(45,123,83,0.18);
        }

        form {
            display: grid;
            gap: 16px;
        }

        .grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        label {
            display: grid;
            gap: 8px;
            font-weight: 700;
        }

        .form-field {
            display: grid;
            gap: 8px;
            position: relative;
        }

        .field-label {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-weight: 700;
            color: var(--ink);
        }

        .field-meta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 22px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.72rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .field-meta.required {
            min-height: auto;
            padding: 0;
            background: transparent;
            color: #d43f3a;
            font-size: 1.2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: 0;
        }

        .field-meta.optional {
            display: none;
        }

        .form-section {
            display: grid;
            gap: 14px;
            margin-top: 6px;
            padding-top: 10px;
        }

        .form-section + .form-section {
            border-top: 1px solid var(--line);
            padding-top: 20px;
            margin-top: 4px;
        }

        .section-heading {
            display: grid;
            gap: 4px;
        }

        .section-heading h3 {
            margin: 0;
            font-size: 1rem;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        .section-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .field-help {
            margin-top: -2px;
            min-height: 20px;
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .quick-picks {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .country-results {
            display: grid;
            gap: 6px;
            max-height: 240px;
            overflow-y: auto;
            padding: 10px;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            z-index: 40;
            border: 1px solid rgba(13, 110, 163, 0.14);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 16px 32px rgba(17, 31, 45, 0.08);
        }

        .country-results[hidden] {
            display: none;
        }

        .country-result {
            min-height: auto;
            width: 100%;
            justify-content: flex-start;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid transparent;
            background: #fff;
            color: var(--ink);
            font-weight: 600;
            text-align: left;
            box-shadow: none;
        }

        .country-result:hover,
        .country-result:focus,
        .country-result.is-selected {
            background: rgba(22, 159, 230, 0.10);
            border-color: rgba(22, 159, 230, 0.18);
        }

        .quick-pick {
            min-height: 36px;
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid rgba(13, 110, 163, 0.18);
            background: rgba(22, 159, 230, 0.08);
            color: var(--brand-dark);
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.2;
            box-shadow: none;
        }

        .quick-pick:hover,
        .quick-pick:focus {
            background: rgba(22, 159, 230, 0.14);
        }

        .field-error {
            min-height: 18px;
            color: var(--danger);
            font-size: 0.86rem;
            line-height: 1.4;
        }

        input, select, textarea {
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border-radius: 16px;
            border: 2px solid #8fa3b5;
            background: #fff;
            color: var(--ink);
            font: inherit;
            box-shadow: inset 0 1px 2px rgba(17, 31, 45, 0.04);
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        textarea { min-height: 110px; resize: vertical; }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(22, 159, 230, 0.16);
        }

        input.is-invalid,
        select.is-invalid,
        textarea.is-invalid,
        .field-check.is-invalid,
        .radio-group.is-invalid {
            border-color: rgba(184, 80, 77, 0.65) !important;
            box-shadow: 0 0 0 4px rgba(184, 80, 77, 0.10);
        }

        .radio-group {
            display: grid;
            gap: 10px;
            padding: 4px;
            border-radius: 18px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .radio-option {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(255,255,255,0.88);
        }

        .radio-option input { width: auto; min-height: auto; margin-top: 4px; }

        .field-check,
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(255,255,255,0.88);
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .field-check input,
        .terms-row input {
            width: auto;
            min-height: auto;
            margin-top: 4px;
        }

        .field-check-copy,
        .terms-copy {
            display: grid;
            gap: 6px;
        }

        .terms-copy a {
            color: var(--brand-dark);
            font-weight: 700;
        }

        .terms-line {
            display: inline-flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 6px;
            font-weight: 600;
            white-space: nowrap;
        }

        @media (max-width: 640px) {
            .terms-line {
                display: inline;
                white-space: normal;
            }
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .info-note {
            margin-bottom: 14px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid rgba(22, 159, 230, 0.16);
            background: rgba(22, 159, 230, 0.06);
            color: #355061;
        }

        .info-note strong {
            color: var(--brand-dark);
        }

        button, .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 18px;
            border-radius: 16px;
            border: 0;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: white;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .button.secondary {
            background: white;
            color: var(--brand-dark);
            border: 1px solid var(--line);
        }

        .footer {
            margin-top: 48px;
            background: var(--footer);
            color: rgba(255, 255, 255, 0.78);
            padding: 44px 0 18px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr repeat(3, 1fr);
            gap: 24px;
        }

        .footer-card {
            padding: 22px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-logo {
            width: auto;
            height: 40px;
            max-width: 100%;
            margin-bottom: 16px;
        }

        .footer-intro {
            margin: 0;
            color: #f8fafc;
        }

        .footer h3 {
            margin-top: 0;
            margin-bottom: 14px;
            color: #fff;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        .footer-link {
            color: #ffffff;
            font-weight: 600;
        }

        .footer-link:hover {
            color: #dbeafe;
        }

        .footer-contact {
            display: grid;
            gap: 14px;
        }

        .footer-contact-item {
            display: grid;
            gap: 4px;
        }

        .footer-contact-item span {
            color: #cbd5e1;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .footer-bottom {
            margin-top: 28px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.12);
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 0.92rem;
            color: #e2e8f0;
        }

        @media (max-width: 980px) {
            .guest-shell,
            .footer-grid,
            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 860px) {
            .nav-toggle { display: inline-flex; }
            .nav-links {
                display: none;
                width: 100%;
                padding: 8px 0 16px;
            }
            .nav-links.open { display: flex; }
            .nav-links a {
                padding: 12px 14px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ url('/css/front-theme-overrides.css') }}?v={{ filemtime(public_path('css/front-theme-overrides.css')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
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
        /* Footer responsive — high-specificity to override repeat(4,1fr) !important */
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
    </style>
</head>
<body class="front-theme public-theme customer-guest-theme">
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
                                <li><a href="{{ $item['href'] }}">{{ $item['label'] }}</a></li>
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

            if (!toggle || !navigation) {
                return;
            }

            toggle.addEventListener('click', function () {
                var isOpen = navigation.classList.toggle('open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.querySelectorAll('form[data-validate-form]').forEach(function (form) {
                var controls = Array.prototype.slice.call(form.querySelectorAll('input, select, textarea')).filter(function (control) {
                    return control.type !== 'hidden' && control.type !== 'submit' && control.type !== 'button' && control.type !== 'reset';
                });

                var radioNames = {};

                function fieldContainer(control) {
                    return control.closest('[data-form-field]') || control.closest('label') || control.parentElement;
                }

                function fieldErrorNode(control) {
                    var container = fieldContainer(control);

                    return container ? container.querySelector('[data-field-error]') : null;
                }

                function syncMatchValidity(control) {
                    var otherName = control.getAttribute('data-match');

                    if (!otherName) {
                        syncCountryValidity(control);
                        return;
                    }

                    var other = form.querySelector('[name="' + otherName + '"]');

                    if (!other) {
                        control.setCustomValidity('');
                        return;
                    }

                    if (control.value !== '' && other.value !== '' && control.value !== other.value) {
                        control.setCustomValidity(control.getAttribute('data-match-message') || 'This field must match.');
                    } else {
                        control.setCustomValidity('');
                    }

                    syncCountryValidity(control);
                }

                function syncCountryValidity(control) {
                    if (!control.hasAttribute('data-country-strict')) {
                        return;
                    }

                    var options = [];

                    try {
                        options = JSON.parse(control.getAttribute('data-country-options') || '[]');
                    } catch (error) {
                        options = [];
                    }

                    var value = (control.value || '').trim();

                    if (value === '' || options.indexOf(value) !== -1) {
                        control.setCustomValidity('');
                    } else {
                        control.setCustomValidity('Please choose a country from the suggested list.');
                    }
                }

                function renderError(control, isValid, message) {
                    var container = fieldContainer(control);
                    var error = fieldErrorNode(control);

                    control.classList.toggle('is-invalid', !isValid);
                    control.setAttribute('aria-invalid', isValid ? 'false' : 'true');

                    if (container && (control.type === 'checkbox' || control.type === 'radio')) {
                        container.classList.toggle('is-invalid', !isValid);
                    }

                    if (error) {
                        error.textContent = isValid ? '' : message;
                    }
                }

                function validateRadio(control) {
                    if (radioNames[control.name]) {
                        return radioNames[control.name];
                    }

                    var group = Array.prototype.slice.call(form.querySelectorAll('input[type="radio"][name="' + control.name + '"]'));
                    var required = group.some(function (item) { return item.required; });
                    var valid = !required || group.some(function (item) { return item.checked; });
                    var message = valid ? '' : (control.getAttribute('data-group-error') || 'Please select an option.');

                    group.forEach(function (item) {
                        renderError(item, valid, message);
                    });

                    radioNames[control.name] = valid;

                    return valid;
                }

                function validateControl(control) {
                    if (control.disabled) {
                        return true;
                    }

                    if (control.type === 'radio') {
                        return validateRadio(control);
                    }

                    syncMatchValidity(control);

                    var valid = control.checkValidity();
                    renderError(control, valid, valid ? '' : control.validationMessage);

                    return valid;
                }

                controls.forEach(function (control) {
                    control.addEventListener('blur', function () {
                        radioNames = {};
                        validateControl(control);
                    });

                    control.addEventListener('input', function () {
                        radioNames = {};
                        if (control.classList.contains('is-invalid') || control.getAttribute('aria-invalid') === 'true') {
                            validateControl(control);
                        } else if (control.hasAttribute('data-match')) {
                            validateControl(control);
                        }
                    });

                    control.addEventListener('change', function () {
                        radioNames = {};
                        validateControl(control);
                    });
                });

                form.addEventListener('submit', function (event) {
                    radioNames = {};
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

            document.querySelectorAll('[data-country-pick]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var field = document.querySelector('[data-country-input]');

                    if (!field) {
                        return;
                    }

                    field.value = button.getAttribute('data-country-pick') || '';
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                    field.focus();
                });
            });

            document.querySelectorAll('[data-country-input]').forEach(function (field) {
                var results = field.parentElement ? field.parentElement.querySelector('[data-country-results]') : null;
                var options = [];

                try {
                    options = JSON.parse(field.getAttribute('data-country-options') || '[]');
                } catch (error) {
                    options = [];
                }

                function renderCountryOptions(term) {
                    if (!results) {
                        return;
                    }

                    var query = (term || '').trim().toLowerCase();
                    var hasFocus = document.activeElement === field;

                    if (!hasFocus) {
                        results.hidden = true;
                        return;
                    }

                    var startsWith = options.filter(function (country) {
                        return query === '' || country.toLowerCase().indexOf(query) === 0;
                    });
                    var includes = options.filter(function (country) {
                        return query !== '' && country.toLowerCase().indexOf(query) > 0;
                    });
                    var matches = startsWith.concat(includes);

                    if (!matches.length) {
                        results.innerHTML = '';
                        results.hidden = true;
                        return;
                    }

                    results.innerHTML = matches.map(function (country) {
                        var selected = field.value === country ? ' is-selected' : '';
                        return '<button type="button" class="country-result' + selected + '" data-country-value="' + country.replace(/"/g, '&quot;') + '">' + country + '</button>';
                    }).join('');
                    results.hidden = false;
                }

                field.addEventListener('focus', function () {
                    renderCountryOptions('');
                });

                field.addEventListener('input', function () {
                    renderCountryOptions(field.value);
                });

                field.addEventListener('keydown', function (e) {
                    if (e.key === 'Tab' || e.key === 'Escape') {
                        if (results) {
                            results.hidden = true;
                        }
                    }
                });

                field.addEventListener('blur', function () {
                    window.setTimeout(function () {
                        if (results) {
                            results.hidden = true;
                        }
                    }, 140);
                });

                if (!results) {
                    return;
                }

                results.addEventListener('click', function (event) {
                    var option = event.target.closest('[data-country-value]');

                    if (!option) {
                        return;
                    }

                    field.value = option.getAttribute('data-country-value') || '';
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                    results.hidden = true;
                    field.focus();
                });

                results.hidden = true;
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
