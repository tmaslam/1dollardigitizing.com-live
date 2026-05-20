<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteContext->displayLabel())</title>
    <link rel="icon" type="image/png" href="{{ url('/images/favicon.png') }}">
    @php
        $legacyAssetBase = rtrim(url('/'), '/');
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
            --accent: #f4b43a;
            --line: #dde4ea;
            --line-strong: #c9d4de;
            --shadow: 0 18px 38px rgba(17, 31, 45, 0.12);
            --footer: #111821;
            --max: 1180px;
        }

        html[data-theme="dark"] {
            color-scheme: dark;
            --page-bg: #0f1419;
            --surface: #1a2029;
            --surface-soft: #141b24;
            --ink: #e8ecf1;
            --muted: #9aa4b2;
            --brand: #4db8f7;
            --brand-dark: #7ecbf9;
            --accent: #f4b43a;
            --line: #2a3441;
            --line-strong: #3a4656;
            --shadow: 0 18px 38px rgba(0, 0, 0, 0.35);
        }

        html[data-theme="dark"] body {
            color: var(--ink);
            background: #0b1016;
        }
        html[data-theme="dark"] .site-frame {
            background: #0b1016;
        }
        html[data-theme="dark"] .site-header {
            background: #161d27;
            border-bottom-color: #2a3441;
        }
        html[data-theme="dark"] .sub-nav {
            background: #1a2029;
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.45);
        }
        html[data-theme="dark"] .sub-nav a {
            color: var(--ink);
            border-bottom-color: #2a3441;
        }
        html[data-theme="dark"] .sub-nav a:hover,
        html[data-theme="dark"] .sub-nav a.active {
            background: #232d3a;
            color: var(--brand-dark);
        }
        html[data-theme="dark"] .customer-hero {
            background: linear-gradient(180deg, rgba(22, 159, 230, 0.10) 0%, rgba(26, 32, 41, 0.96) 100%), #1a2029;
        }
        html[data-theme="dark"] .customer-tab {
            background: #1a2029;
            color: var(--ink);
        }
        html[data-theme="dark"] .customer-tab:hover,
        html[data-theme="dark"] .customer-tab.active {
            background: rgba(22, 159, 230, 0.18);
        }
        html[data-theme="dark"] input,
        html[data-theme="dark"] select,
        html[data-theme="dark"] textarea {
            background: #111820;
            color: var(--ink);
            border-color: #2a3441;
        }
        html[data-theme="dark"] input:focus,
        html[data-theme="dark"] select:focus,
        html[data-theme="dark"] textarea:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(22, 159, 230, 0.20);
        }
        html[data-theme="dark"] .table-wrap table {
            background: #1a2029;
        }
        html[data-theme="dark"] .table-wrap th {
            background: #232d3a;
            color: var(--ink);
            border-bottom-color: #2a3441;
        }
        html[data-theme="dark"] .table-wrap td {
            border-bottom-color: #2a3441;
        }
        html[data-theme="dark"] .alert {
            background: #1a2029;
            border-color: #2a3441;
        }
        html[data-theme="dark"] .alert-success {
            background: rgba(45, 123, 83, 0.15);
            border-color: rgba(45, 123, 83, 0.35);
        }
        html[data-theme="dark"] .alert-error {
            background: rgba(180, 60, 60, 0.15);
            border-color: rgba(180, 60, 60, 0.35);
        }
        html[data-theme="dark"] .dp-card {
            background: #1a2029;
            border-color: #2a3441;
        }
        html[data-theme="dark"] .dp-card.selected {
            border-color: var(--brand);
            background: rgba(22, 159, 230, 0.12);
        }
        html[data-theme="dark"] .dp-tab {
            background: #1a2029;
            color: var(--ink);
            border-color: #2a3441;
        }
        html[data-theme="dark"] .dp-tab.active {
            background: var(--brand);
            color: #fff;
        }
        html[data-theme="dark"] .modal-body {
            background: #1a2029;
            color: var(--ink);
        }
        html[data-theme="dark"] .modal-overlay {
            background: rgba(0, 0, 0, 0.70);
        }
        html[data-theme="dark"] .content-card {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.30);
            border-color: #2a3441;
        }
        html[data-theme="dark"] .activity-item {
            background: #141b24;
            border-color: #2a3441;
        }
        html[data-theme="dark"] .customer-action-list {
            background: #1a2029;
            border-color: #2a3441;
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.45);
        }
        html[data-theme="dark"] .customer-action-list a {
            color: var(--ink);
        }
        html[data-theme="dark"] .customer-action-list a:hover {
            background: rgba(77, 184, 247, 0.12);
            color: var(--brand-dark);
        }

        /* Portal stat cards */
        html[data-theme="dark"] a.portal-stat:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.40);
        }

        /* Metric link hover */
        html[data-theme="dark"] .metric-link:hover {
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.35);
            border-color: rgba(77, 184, 247, 0.30);
        }

        /* Secondary button */
        html[data-theme="dark"] .button.secondary,
        html[data-theme="dark"] button.secondary {
            background: #232d3a;
            border-color: #3a4656;
            color: var(--ink);
        }

        html[data-theme="dark"] .button.secondary:hover,
        html[data-theme="dark"] button.secondary:hover {
            background: #2a3441;
            border-color: var(--brand);
        }

        html[data-theme="dark"] .button.ghost,
        html[data-theme="dark"] button.ghost {
            background: rgba(77, 184, 247, 0.12);
            color: var(--brand-dark);
            border-color: rgba(77, 184, 247, 0.22);
        }

        /* Card head (list/detail card header strip) */
        html[data-theme="dark"] .card-head {
            background: rgba(77, 184, 247, 0.06);
            border-bottom-color: #2a3441;
        }

        /* Content note (info banners) */
        html[data-theme="dark"] .content-note {
            background: rgba(77, 184, 247, 0.08);
            border-color: rgba(77, 184, 247, 0.18);
        }

        /* Info / list / detail cards */
        html[data-theme="dark"] .info-card,
        html[data-theme="dark"] .list-card,
        html[data-theme="dark"] .detail-card {
            background: var(--surface);
            border-color: #2a3441;
        }

        /* Order meta strip */
        html[data-theme="dark"] .order-meta-strip,
        html[data-theme="dark"] .order-meta-strip li {
            border-color: #2a3441;
        }

        /* File / comment items */
        html[data-theme="dark"] .file-item,
        html[data-theme="dark"] .comment-item {
            border-top-color: #2a3441;
        }

        /* Table base (outside table-wrap) */
        html[data-theme="dark"] table {
            background: #1a2029;
        }

        html[data-theme="dark"] th {
            background: rgba(77, 184, 247, 0.06);
            color: var(--muted);
        }

        /* Card numbers / stat values in dark mode */
        html[data-theme="dark"] .action-card strong,
        html[data-theme="dark"] .activity-card strong,
        html[data-theme="dark"] .info-card strong,
        html[data-theme="dark"] .metric strong,
        html[data-theme="dark"] .portal-stat strong,
        html[data-theme="dark"] .activity-item strong,
        html[data-theme="dark"] .file-item strong,
        html[data-theme="dark"] .comment-item strong {
            color: #e8ecf1 !important;
        }

        /* Invoice filter bar */
        html[data-theme="dark"] .invoice-filterbar {
            background: linear-gradient(180deg, #1a2029, #141b24);
            border: 1px solid #2a3441;
        }
        html[data-theme="dark"] .invoice-filterbar input[type="date"] {
            background: #232d3a;
            border-color: #3a4656;
            color: var(--ink);
            color-scheme: dark;
        }

@include('shared.file-preview-styles')

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; font-size: 15px; }
        body {
            margin: 0;
            font-family: "Inter", "Segoe UI", sans-serif;
            color: var(--ink);
            background: #f0f4f8;
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
            background: #f0f4f8;
        }

        .topbar {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            color: rgba(255,255,255,0.96);
            font-size: 0.92rem;
        }

        .topbar-inner {
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding: 10px 0;
        }

        .topbar a { color: #ffffff; }
        .topbar a:hover { color: rgba(255,255,255,0.86); }

        .topbar-links {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .account-chip {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(22, 159, 230, 0.08);
            border: 1px solid rgba(22, 159, 230, 0.12);
            color: var(--ink);
        }

        .account-chip-meta {
            display: block;
            line-height: 1.2;
        }

        .account-chip-meta strong {
            font-size: 0.92rem;
            color: var(--ink);
        }

        .account-chip-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .account-chip-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--ink) !important;
            background: rgba(255,255,255,0.94);
            border: 1px solid rgba(17, 31, 45, 0.10);
            transition: background .18s ease, border-color .18s ease, color .18s ease;
        }

        .topbar .account-chip-link {
            color: var(--ink) !important;
        }

        .account-chip-link:hover {
            color: var(--brand-dark) !important;
            border-color: rgba(22, 159, 230, 0.18);
            background: rgba(22, 159, 230, 0.10);
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
            gap: 0;
            min-width: 0;
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

        .nav-item {
            position: relative;
        }

        .nav-links > a,
        .nav-item > a,
        .nav-parent-toggle {
            padding: 26px 14px;
            border-radius: 0;
            font-size: 15px;
            font-family: 'Roboto Slab', serif;
            color: var(--ink);
            display: block;
            transition: color 0.2s ease;
        }

        .nav-parent-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 0;
            cursor: pointer;
            width: 100%;
        }

        .nav-parent-toggle::after {
            content: "";
            width: 8px;
            height: 8px;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: rotate(45deg) translateY(-2px);
            transition: transform 0.2s ease;
            opacity: 0.95;
        }

        .nav-links > a:hover,
        .nav-links > a.active,
        .nav-item > a:hover,
        .nav-item > a.active,
        .nav-parent-toggle:hover,
        .nav-parent-toggle.active,
        .nav-item:hover > a {
            color: var(--brand);
        }

        .sub-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 260px;
            background: #fff;
            box-shadow: 0 18px 38px rgba(17, 31, 45, 0.18);
            z-index: 60;
        }

        .sub-nav a {
            display: block;
            padding: 12px 16px;
            color: var(--ink);
            font-family: "Roboto", "Segoe UI", sans-serif;
            font-size: 0.98rem;
            border-bottom: 1px solid #e8eef3;
        }

        .sub-nav a:hover,
        .sub-nav a.active {
            background: rgba(22, 159, 230, 0.08);
            color: var(--brand-dark);
        }

        .nav-item:hover .sub-nav {
            display: block;
        }

        .nav-item:hover .nav-parent-toggle::after,
        .nav-item:focus-within .nav-parent-toggle::after,
        .nav-item.open .nav-parent-toggle::after {
            transform: rotate(225deg) translateY(2px);
        }

        .nav-item:focus-within .sub-nav {
            display: block;
        }

        .nav-cta {
            background: transparent;
            color: #fff !important;
            font-weight: 400;
            box-shadow: none;
        }

        .nav-cta:hover {
            background: #fff !important;
            color: var(--brand) !important;
        }

        .page-content {
            padding: 28px 0 48px;
        }

        .customer-shell {
            display: grid;
            gap: 18px;
        }

        .customer-hero,
        .customer-nav,
        .content-card {
            background: var(--surface);
            border-radius: 16px;
            border: 1.5px solid var(--line);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }

        .customer-hero {
            padding: clamp(20px, 3vw, 28px) 18px;
            background:
                linear-gradient(180deg, rgba(22, 159, 230, 0.06) 0%, rgba(255, 255, 255, 0.96) 100%),
                #fff;
        }

        .customer-hero.hero-compact {
            padding: 14px clamp(20px, 3vw, 28px);
        }

        .customer-hero.hero-compact .eyebrow {
            display: none;
        }

        .customer-hero.hero-compact .hero-grid {
            margin-top: 0;
            align-items: center;
        }

        .customer-hero.hero-compact h2 {
            font-size: 1.05rem;
            margin: 0;
            letter-spacing: -0.01em;
            line-height: 1.4;
        }

        .customer-hero.hero-compact p {
            font-size: 0.85rem;
            margin-top: 2px;
        }

        .eyebrow {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 999px;
            background: var(--brand-dark);
            color: #000;
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 700;
        }

        body.front-theme.customer-portal-theme .eyebrow {
            background: var(--brand) !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .hero-welcome-row {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .hero-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        .hero-grid {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .customer-hero h2 {
            margin: 12px 0 8px;
            font-size: clamp(1.6rem, 2.8vw, 2.2rem);
            letter-spacing: -0.04em;
            line-height: 1.1;
        }

        .customer-hero p {
            margin: 0;
            max-width: 760px;
            color: var(--muted);
            line-height: 1.7;
        }

        .hero-meta {
            display: grid;
            gap: 6px;
            justify-items: end;
            text-align: right;
        }

        .hero-meta strong {
            font-size: 1rem;
        }

        .hero-meta .status {
            justify-self: end;
        }

        .customer-nav {
            padding: 16px 18px;
        }

        .customer-nav-shell {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .customer-nav-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .customer-nav-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .customer-action-menu {
            position: relative;
        }

        .customer-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            font-weight: 700;
            transition: background .18s ease, border-color .18s ease, color .18s ease;
        }

        .customer-tab:hover,
        .customer-tab.active {
            background: rgba(22, 159, 230, 0.10);
            border-color: rgba(22, 159, 230, 0.24);
            color: var(--brand-dark);
        }

        .customer-tab.account {
            background: rgba(22, 159, 230, 0.06);
        }

        .customer-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-weight: 700;
            transition: background .18s ease, border-color .18s ease, color .18s ease, transform .18s ease;
        }

        .customer-action.primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            box-shadow: 0 12px 24px rgba(22, 159, 230, 0.18);
        }

        .customer-action.secondary {
            background: rgba(22, 159, 230, 0.08);
            color: var(--brand-dark);
            border-color: rgba(22, 159, 230, 0.16);
        }

        .customer-action:hover {
            transform: translateY(-1px);
        }

        .customer-action-menu summary {
            list-style: none;
            cursor: pointer;
        }

        .customer-action-menu summary::-webkit-details-marker {
            display: none;
        }

        .customer-action-menu .customer-action {
            gap: 8px;
        }

        .customer-action-menu .customer-action::after {
            content: "";
            width: 8px;
            height: 8px;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: rotate(45deg) translateY(-2px);
            transition: transform 0.18s ease;
        }

        .customer-action-menu[open] .customer-action::after {
            transform: rotate(225deg) translateY(2px);
        }

        .customer-action-list {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 240px;
            padding: 10px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,0.98);
            box-shadow: 0 18px 36px rgba(17, 31, 45, 0.16);
            display: grid;
            gap: 6px;
            z-index: 30;
        }

        .customer-action-list a {
            display: grid;
            gap: 2px;
            padding: 12px 14px;
            border-radius: 14px;
            color: var(--ink);
            transition: background .18s ease, color .18s ease;
        }

        .customer-action-list a:hover {
            background: rgba(22, 159, 230, 0.08);
            color: var(--brand-dark);
        }

        .customer-action-list strong {
            font-size: 0.98rem;
            letter-spacing: -0.02em;
        }

        .customer-action-list span {
            color: var(--muted);
            font-size: 0.84rem;
        }

        .content-card {
            padding: clamp(18px, 2.4vw, 28px);
        }

        .flash {
            display: grid;
            gap: 10px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid transparent;
            font-size: 0.96rem;
            line-height: 1.6;
            background: rgba(184,80,77,0.10);
            color: #7c2f2d;
            border-color: rgba(184,80,77,0.18);
        }

        .alert-success {
            background: rgba(45,123,83,0.10);
            color: #1d5639;
            border-color: rgba(45,123,83,0.18);
        }

        .alert-error {
            background: rgba(184,80,77,0.10);
            color: #7c2f2d;
            border-color: rgba(184,80,77,0.18);
        }

        /* Allow class="alert success" as well as "alert alert-success" */
        .alert.success {
            background: rgba(45,123,83,0.10);
            color: #1d5639;
            border-color: rgba(45,123,83,0.18);
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .portal-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .portal-stat {
            display: block;
            padding: 20px 22px;
            border-radius: 16px;
            background: var(--surface);
            border: 1.5px solid var(--line);
            text-decoration: none;
            color: inherit;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        a.portal-stat:hover {
            transform: translateY(-2px);
            border-color: var(--brand);
            box-shadow: 0 8px 24px rgba(22, 159, 230, 0.12);
        }

        .portal-stat span {
            display: block;
            color: var(--muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .portal-stat strong {
            display: block;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        .action-grid,
        .workspace-grid,
        .summary-grid {
            display: grid;
            gap: 14px;
        }

        .action-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .workspace-grid {
            grid-template-columns: 1.15fr 0.85fr;
        }

        .summary-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .metric {
            padding: 20px 22px;
            border-radius: 16px;
            background: var(--surface);
            border: 1.5px solid var(--line);
        }

        .metric-link {
            display: block;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease;
        }

        .metric-link:hover {
            transform: translateY(-1px);
            border-color: rgba(22, 159, 230, 0.22);
            background: var(--surface-soft);
            box-shadow: 0 16px 28px rgba(12, 48, 89, 0.08);
        }

        .metric span {
            display: block;
            color: var(--muted);
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .metric strong {
            display: block;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .action-card,
        .activity-card {
            padding: 20px;
            border-radius: 16px;
            border: 1.5px solid var(--line);
            background: var(--surface);
        }

        .action-card {
            display: grid;
            gap: 10px;
            align-content: start;
        }

        .action-card span,
        .activity-kicker {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            font-weight: 700;
        }

        .action-card strong,
        .activity-card strong {
            font-size: 1.08rem;
            letter-spacing: -0.03em;
            color: var(--ink);
        }

        .action-card p,
        .activity-card p {
            margin: 0;
            color: var(--muted);
        }

        a.action-card {
            color: var(--ink);
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        a.action-card strong {
            color: var(--brand);
        }

        a.action-card:hover {
            transform: translateY(-1px);
            border-color: rgba(22, 159, 230, 0.22);
            box-shadow: 0 16px 28px rgba(12, 48, 89, 0.08);
        }

        html[data-theme="dark"] a.action-card:hover {
            border-color: rgba(77, 184, 247, 0.35);
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.35);
            background: var(--surface-soft);
        }

        html[data-theme="dark"] a.action-card strong {
            color: var(--brand-dark);
        }

        .activity-list {
            display: grid;
            gap: 12px;
        }

        .activity-item {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1.5px solid var(--line);
            background: var(--surface-soft);
        }

        .activity-meta {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 8px;
        }

        .table-note {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.86rem;
        }

        .inline-link {
            color: var(--brand-dark);
        }

        .inline-link:hover {
            text-decoration: underline;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-end;
            padding-bottom: 16px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--line);
            flex-wrap: wrap;
        }

        .section-head h3 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            color: var(--ink);
            line-height: 1.25;
        }

        .section-head p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .invoice-detail-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .invoice-detail-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .table-wrap {
            overflow-x: auto;
            border-radius: 14px;
            border: 1.5px solid var(--line);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
            background: rgba(255,255,255,0.96);
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }

        th {
            background: rgba(22,159,230,0.05);
            color: var(--muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }

        tr:last-child td { border-bottom: none; }

        .status {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(22,159,230,0.08);
            color: var(--brand-dark);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status.warning {
            background: rgba(216,155,50,0.12);
            color: #8b5a00;
        }

        .status.success {
            background: rgba(45,123,83,0.12);
            color: #1d5639;
        }

        html[data-theme="dark"] .status {
            background: rgba(77, 184, 247, 0.15);
            color: var(--brand-dark);
        }

        html[data-theme="dark"] .status.warning {
            background: rgba(216, 155, 50, 0.18);
            color: #f4b43a;
        }

        html[data-theme="dark"] .status.success {
            background: rgba(45, 123, 83, 0.20);
            color: #4ade80;
        }

        .stack {
            display: grid;
            gap: 14px;
        }

        .content-note {
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(22, 159, 230, 0.06);
            border: 1px solid rgba(22, 159, 230, 0.14);
            font-size: 0.93rem;
            line-height: 1.6;
            color: var(--ink);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .info-card,
        .list-card,
        .detail-card {
            padding: 16px 18px;
            border-radius: 14px;
            border: 1.5px solid var(--line);
            background: var(--surface);
        }

        .info-card span {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            margin-bottom: 6px;
        }

        .info-card strong {
            font-size: 1.1rem;
            font-weight: 700;
            overflow-wrap: break-word;
            word-break: break-all;
        }

        .info-card p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.5;
        }

        /* Compact horizontal metadata strip used on order / quote detail pages */
        .order-meta-strip {
            display: flex;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            list-style: none;
            border-top: 1px solid var(--line);
            margin-top: 14px;
        }

        .order-meta-strip li {
            flex: 1 1 140px;
            padding: 10px 18px;
            border-right: 1px solid var(--line);
        }

        .order-meta-strip li:last-child {
            border-right: none;
        }

        .order-meta-strip dt {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--muted);
            margin-bottom: 3px;
        }

        .order-meta-strip dd {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--ink);
            overflow-wrap: break-word;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 16px;
        }

        .list-card,
        .detail-card {
            padding: 0;
            overflow: hidden;
        }

        .card-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            background: rgba(22,159,230,0.04);
        }

        .card-head h4 {
            margin: 0;
            font-size: 1.05rem;
        }

        .card-head p {
            margin: 6px 0 0;
            color: var(--muted);
        }

        .file-list,
        .comment-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .file-item,
        .comment-item {
            padding: 16px 18px;
            border-top: 1px solid var(--line);
        }

        .file-item:first-child,
        .comment-item:first-child {
            border-top: 0;
        }

        .file-actions,
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .file-actions .status.file-notice {
            display: block;
            width: 100%;
            white-space: normal;
            line-height: 1.45;
            border-radius: 14px;
            padding: 10px 12px;
            text-transform: none;
            letter-spacing: 0;
        }

        /* Table action column — used on orders and quotes list pages */
        .action-cell { white-space: nowrap; }

        .action-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
        }

        .action-group form { margin: 0; }
        .action-group .button { min-width: 0; white-space: nowrap; }
        .action-group .button.danger { color: #fff; }

        .button,
        button,
        input[type="submit"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 11px 16px;
            border-radius: 16px;
            border: 1px solid transparent;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: white;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .button.secondary,
        button.secondary {
            background: var(--surface);
            color: var(--ink);
            border-color: var(--line-strong);
        }

        .button.ghost,
        button.ghost {
            background: rgba(22,159,230,0.08);
            color: var(--brand-dark);
            border-color: rgba(22,159,230,0.16);
        }

        .button.danger,
        button.danger {
            background: linear-gradient(135deg, #bf5a57, #983a37);
        }

        form { margin: 0; }

        .form-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        label {
            display: grid;
            gap: 8px;
            color: var(--ink);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .field-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .field-meta.required {
            min-height: auto;
            padding: 0;
            background: transparent;
            color: #d43f3a;
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: 0;
        }

        .field-help {
            margin-top: -2px;
            min-height: 18px;
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.5;
            font-weight: 500;
        }

        .field-error {
            min-height: 18px;
            color: #b93a34;
            font-size: 0.86rem;
            line-height: 1.4;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            min-height: 46px;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid var(--line-strong);
            background: rgba(255,255,255,0.96);
            color: var(--ink);
            font: inherit;
        }

        input[type="date"] {
            appearance: none;
            -webkit-appearance: none;
            position: relative;
            padding-right: 44px;
        }

        input.is-invalid,
        select.is-invalid,
        textarea.is-invalid {
            border-color: rgba(185, 58, 52, 0.72);
            box-shadow: 0 0 0 4px rgba(185, 58, 52, 0.12);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .muted { color: var(--muted); }

        .empty-state {
            padding: 26px;
            text-align: center;
            color: var(--muted);
        }

        .pagination {
            display: flex;
            gap: 8px;
            justify-content: end;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            min-width: 40px;
            min-height: 40px;
            padding: 10px 12px;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,0.96);
            color: var(--ink);
        }

        .single-column {
            max-width: 720px;
        }

        /* Archive / list page filter bar */
        .filter-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-bar .filter-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Upload error shown on order submission / revision forms */
        .upload-error {
            margin-top: 8px;
            color: #b42318;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Order / quote submission form — specialized field layouts */
        .dimension-field,
        .paired-field,
        .field-stack {
            display: grid;
            gap: 8px;
            color: var(--ink);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .dimension-inputs {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            gap: 10px;
            align-items: center;
        }

        .dimension-divider {
            color: var(--muted);
            font-weight: 700;
            font-size: 1rem;
        }

        .paired-field-inputs {
            display: grid;
            grid-template-columns: minmax(110px, 0.8fr) minmax(0, 1.2fr);
            gap: 10px;
            align-items: start;
        }

        .upload-guidance {
            grid-column: 1 / -1;
            margin-top: -4px;
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .upload-guidance strong { color: var(--ink); }

        .required-mark {
            color: #b42318;
            font-weight: 800;
            margin-left: 2px;
        }

        .field-stack > span {
            color: var(--ink);
            font-weight: 700;
            font-size: 0.95rem;
        }

        [hidden] { display: none !important; }

        /* Invoice filter bar */
        .invoice-filterbar {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            align-items: end;
            margin-bottom: 18px;
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(248,251,253,0.9), rgba(255,255,255,0.98));
        }

        .invoice-filterbar .filter-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .invoice-filterbar .field-label {
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .invoice-filterbar .field-hint {
            grid-column: 1 / -1;
            color: var(--muted);
            font-size: 0.86rem;
            line-height: 1.5;
            padding: 2px 2px 0;
        }

        .invoice-filterbar input[type="date"] {
            min-height: 52px;
            border-radius: 16px;
            border: 1px solid rgba(17,31,45,0.12);
            background: #fff;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
            color: var(--ink);
            font-size: 0.98rem;
            font-weight: 600;
            padding: 0 14px;
        }

        .invoice-filterbar input[type="date"]::-webkit-date-and-time-value { text-align: left; }

        .invoice-filterbar .field-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        /* Payment checkout and result page */
        .payment-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .payment-card-header h3 { margin: 0 0 4px; font-size: 1.15rem; letter-spacing: -0.02em; }
        .payment-card-header p { margin: 0; color: var(--muted); font-size: 0.88rem; }

        .payment-amount-col { text-align: right; flex-shrink: 0; }

        .payment-amount-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .payment-amount-value {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--ink);
        }

        .payment-amount-note { font-size: 0.78rem; color: var(--muted); margin-top: 2px; }

        .payment-ref-strip {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 10px 14px;
            border-radius: 12px;
            background: var(--surface-soft);
            border: 1px solid var(--line);
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .payment-ref-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            white-space: nowrap;
        }

        .payment-ref-value {
            font-size: 0.85rem;
            font-family: monospace;
            color: var(--ink);
            word-break: break-all;
        }

        .payment-status-banner {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .payment-status-main { display: flex; gap: 14px; align-items: flex-start; }

        .payment-status-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .payment-status-icon.ok  { background: rgba(45,123,83,0.12);  color: #1d5639; }
        .payment-status-icon.fail { background: rgba(197,107,34,0.12); color: #7a4010; }

        .payment-detail-strip {
            display: flex;
            gap: 24px;
            align-items: flex-start;
            padding: 12px 16px;
            border-radius: 12px;
            background: var(--surface-soft);
            border: 1px solid var(--line);
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .payment-detail-item .di-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 3px;
            font-weight: 700;
        }

        .payment-detail-item .di-value { font-size: 0.85rem; }
        .payment-detail-item .di-value.mono { font-family: monospace; word-break: break-all; }

        .td-right, .th-right { text-align: right; }

        .footer {
            margin-top: 48px;
            background:
                linear-gradient(180deg, rgba(22, 159, 230, 0.06) 0%, rgba(255, 255, 255, 0.96) 100%),
                #fff;
            color: var(--ink);
            padding: 44px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr repeat(3, 1fr);
            gap: 24px;
        }

        .footer-card {
            padding: 22px;
            border-radius: 22px;
            background: var(--surface);
            border: 1.5px solid var(--line);
        }

        .footer-logo {
            height: 40px;
            width: auto;
            margin-bottom: 16px;
        }

        .footer-intro {
            margin: 0;
            color: var(--muted);
        }

        .footer h3 {
            margin-top: 0;
            margin-bottom: 14px;
            color: var(--ink);
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
            color: var(--ink);
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
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .footer a:hover {
            color: var(--brand);
        }

        .footer-bottom {
            margin-top: 28px;
            padding: 16px 0;
            background: linear-gradient(135deg, #169fe6 0%, #0d6ea3 100%);
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 0.92rem;
        }

        @media (max-width: 1080px) {
            .metric-grid,
            .portal-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .detail-grid,
            .info-grid,
            .form-grid,
            .footer-grid,
            .action-grid,
            .workspace-grid,
            .summary-grid {
                grid-template-columns: 1fr 1fr;
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
            .nav-links > a,
            .nav-item > a,
            .nav-parent-toggle {
                border-radius: 0;
                padding: 12px 14px;
            }
            .nav-parent-toggle {
                justify-content: space-between;
            }
            .nav-item:hover .sub-nav,
            .nav-item.open .sub-nav {
                display: block;
            }
            .sub-nav {
                position: static;
                min-width: 100%;
                box-shadow: none;
                background: rgba(255,255,255,0.98);
                border-top: 1px solid rgba(17,31,45,0.06);
            }
            .sub-nav a {
                padding: 12px 18px;
            }
            .hero-grid,
            .section-head {
                display: grid;
            }
            .hero-meta {
                justify-items: start;
                text-align: left;
            }

            .customer-nav-shell {
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .customer-nav-grid,
            .customer-nav-actions {
                width: 100%;
            }

            .customer-nav-actions {
                justify-content: stretch;
                flex-wrap: wrap;
            }

            .customer-action-menu,
            .customer-nav-actions > .customer-tab.account {
                flex: 1 1 100%;
            }

            .customer-action-menu summary,
            .customer-nav-actions > .customer-tab.account {
                width: 100%;
                justify-content: center;
            }

            .customer-action-list {
                left: 0;
                right: 0;
            }
        }

        @media (max-width: 720px) {
            .topbar-inner {
                justify-content: center;
                text-align: center;
            }

            .account-chip {
                flex-wrap: wrap;
                justify-content: center;
            }

            .account-chip-actions {
                width: 100%;
                justify-content: center;
            }

            .page-content {
                padding: 18px 0 38px;
            }

            .customer-hero {
                padding: 20px;
            }

            .metric-grid,
            .portal-stat-grid,
            .detail-grid,
            .info-grid,
            .form-grid,
            .action-grid,
            .workspace-grid,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .footer-brand-block {
                grid-column: 1 / -1;
            }

            .order-meta-strip li {
                flex: 1 1 45%;
                border-right: none;
                border-bottom: 1px solid var(--line);
            }

            .order-meta-strip li:last-child {
                border-bottom: none;
            }

            table {
                min-width: 100%;
            }

            th,
            td {
                white-space: normal;
                word-break: break-word;
            }

            .file-actions,
            .actions {
                width: 100%;
            }

            .file-actions .button,
            .file-actions button,
            .actions .button,
            .actions button {
                flex: 1 1 100%;
            }

            .invoice-filterbar .field-actions {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-brand-block {
                grid-column: auto;
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

        html[data-theme="dark"] body.front-theme.public-theme .footer {
            background: #111821 !important;
            color: #e8ecf1 !important;
        }
        html[data-theme="dark"] body.front-theme.public-theme .footer .footer-brand-block,
        html[data-theme="dark"] body.front-theme.public-theme .footer .footer-column,
        html[data-theme="dark"] body.front-theme.public-theme .footer .footer-grid > div {
            background: #1a2029 !important;
            border-color: #2a3441 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
        }
        html[data-theme="dark"] body.front-theme.public-theme .footer h4,
        html[data-theme="dark"] body.front-theme.public-theme .footer strong {
            color: #e8ecf1 !important;
        }
        html[data-theme="dark"] body.front-theme.public-theme .footer a,
        html[data-theme="dark"] body.front-theme.public-theme .footer .footer-links li,
        html[data-theme="dark"] body.front-theme.public-theme .footer .footer-brand-block p {
            color: #9aa4b2 !important;
        }
        html[data-theme="dark"] body.front-theme.public-theme .footer a:hover {
            color: #4db8f7 !important;
        }
        html[data-theme="dark"] body.front-theme.public-theme .footer-logo {
            filter: brightness(0.9) !important;
        }
        /* Footer responsive — must use same high-specificity selector to override repeat(4,1fr) */
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
    <script>
        (function () {
            var saved = localStorage.getItem('1dollar-theme');
            if (saved === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>
<body class="front-theme public-theme customer-portal-theme">
    @php
        $currentPath = request()->path();
        $customerName = request()->attributes->get('customerUser')?->display_name ?? session('customer_user_name');
        $customerFirstName = request()->attributes->get('customerUser')?->first_name ?? '';
        $customerNav = [
            ['label' => 'Dashboard', 'href' => url('/dashboard.php'), 'match' => ['dashboard.php']],
            ['label' => 'Orders', 'href' => url('/view-orders.php'), 'match' => ['new-order.php', 'vector-order.php', 'view-orders.php', 'view-order-detail.php', 'edit-order.php', 'disapprove-order.php', 'download.php', 'preview.php']],
            ['label' => 'Quotes', 'href' => url('/view-quotes.php'), 'match' => ['quote.php', 'vector_quote.php', 'vector-quote.php', 'digitizing_quote.php', 'digitizing-quote.php', 'view-quotes.php', 'view-quote-detail.php', 'edit-quote.php']],
            ['label' => 'Billing', 'href' => url('/view-billing.php'), 'match' => ['view-billing.php', 'payment.php', 'payment-proceed.php', 'successpay.php', 'referral-invoice.php']],
            ['label' => 'Paid Orders', 'href' => url('/view-archive-orders.php'), 'match' => ['view-paid-orders.php', 'view-archive-orders.php']],
            ['label' => 'Invoices', 'href' => url('/view-invoices.php'), 'match' => ['view-invoices.php', 'view-invoice-detail.php']],
        ];
        $customerStartLinks = [
            ['label' => 'Digitizing Order', 'href' => url('/new-order.php'), 'description' => 'Start a regular embroidery digitizing order.'],
            ['label' => 'Vector Order', 'href' => url('/vector-order.php'), 'description' => 'Submit a vector-only order directly.'],
            ['label' => 'Digitizing Quote', 'href' => url('/quote.php'), 'description' => 'Get digitizing pricing first before placing the order.'],
            ['label' => 'Vector Quote', 'href' => url('/vector-quote.php'), 'description' => 'Request vector pricing first before placing the order.'],
        ];
    @endphp

    <div class="site-frame">
        <div class="topbar">
            <div class="container topbar-inner">
                <a href="mailto:{{ $siteContext->supportEmail }}">Email Us: {{ $siteContext->supportEmail }}</a>
                <div class="topbar-links">
                    <div class="account-chip">
                        <div class="account-chip-meta">
                            <strong>{{ $customerName ?: 'Customer' }}</strong>
                        </div>
                        <div class="account-chip-actions">
                            <button type="button" class="account-chip-link" data-theme-toggle aria-label="Toggle dark mode" style="border:none;cursor:pointer;background:rgba(255,255,255,0.94);">
                                <span data-theme-icon>🌙</span>
                            </button>
                            <a class="account-chip-link" href="{{ url('/my-profile.php') }}">My Profile</a>
                            <a class="account-chip-link" href="{{ url('/logout.php') }}">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <header class="marketing-header">
            <div class="container marketing-header-shell">
                <a href="{{ url('/') }}" class="marketing-brand">
                    <img class="site-logo" src="{{ $legacyAssetBase }}/images/logo.png" alt="1 Dollar Digitizing">
                </a>

                <button class="marketing-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="public-navigation">Menu</button>

                <div class="marketing-actions">
                    <a class="hdr-btn hdr-btn--ghost" href="{{ url('/dashboard.php') }}">Dashboard</a>
                    <a class="hdr-btn hdr-btn--ghost" href="{{ url('/logout.php') }}">Logout</a>
                    <a class="hdr-btn hdr-btn--cta" href="{{ url('/book-a-meeting.php') }}">Book a Meeting</a>
                </div>

                <nav class="marketing-nav" id="public-navigation">
                    <div class="marketing-nav-list">
                        @foreach ($publicMenu as $item)
                            @php
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
            <div class="container customer-shell">
                <section class="customer-hero @yield('hero_class')">
                    @hasSection('eyebrow')
                        @yield('eyebrow')
                    @else
                        <span class="eyebrow">{{ $customerFirstName ? 'Welcome Back, '.$customerFirstName : 'Welcome Back' }}</span>
                    @endif
                </section>

                <nav class="customer-nav">
                    <div class="customer-nav-shell">
                        <div class="customer-nav-grid">
                            @foreach ($customerNav as $item)
                                @php
                                    $active = collect($item['match'])->contains(fn ($pattern) => str_contains($currentPath, $pattern));
                                @endphp
                                <a class="customer-tab {{ $active ? 'active' : '' }}" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                            @endforeach
                        </div>
                        <div class="customer-nav-actions">
                            <details class="customer-action-menu">
                                <summary class="customer-action primary">New Order</summary>
                                <div class="customer-action-list">
                                    @foreach ($customerStartLinks as $item)
                                        <a href="{{ $item['href'] }}">
                                            <strong>{{ $item['label'] }}</strong>
                                            <span>{{ $item['description'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </details>
                        </div>
                    </div>
                </nav>

                <div class="flash">
                    @if (session('impersonator_admin_id'))
                        <div class="alert">
                            You are viewing this account as support for {{ session('impersonation_target_name', $customerName) }}.
                            <form method="post" action="{{ url('/stop-simulated-session') }}" style="display:inline-flex; margin-left:12px;">
                                @csrf
                                <button type="submit" class="button ghost">Return To Admin</button>
                            </form>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-error">{{ $errors->first() }}</div>
                    @endif
                </div>

                @yield('content')
            </div>
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
                            @if ($siteContext->supportEmail !== '')
                                <li><a href="mailto:{{ $siteContext->supportEmail }}">{{ $siteContext->supportEmail }}</a></li>
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

    @include('shared.file-preview-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggle = document.querySelector('[data-nav-toggle]');
            var navigation = document.getElementById('public-navigation');
            var parentToggles = document.querySelectorAll('.nav-parent-toggle');
            var customerActionMenus = document.querySelectorAll('.customer-action-menu');
            var forms = document.querySelectorAll('[data-form-validation]');

            if (!toggle || !navigation) {
                // keep running for customer action menus below
            }

            if (toggle && navigation) {
                toggle.addEventListener('click', function () {
                    var isOpen = navigation.classList.toggle('open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            }

            parentToggles.forEach(function (button) {
                button.addEventListener('click', function () {
                    if (window.innerWidth > 860) {
                        return;
                    }

                    var item = button.closest('.nav-item');
                    var isOpen = item.classList.toggle('open');
                    button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });

            if (customerActionMenus.length) {
                document.addEventListener('click', function (event) {
                    customerActionMenus.forEach(function (menu) {
                        if (!menu.contains(event.target)) {
                            menu.removeAttribute('open');
                        }
                    });
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key !== 'Escape') {
                        return;
                    }

                    customerActionMenus.forEach(function (menu) {
                        menu.removeAttribute('open');
                    });
                });
            }

            forms.forEach(function (form) {
                var controls = Array.prototype.slice.call(form.querySelectorAll('input, select, textarea')).filter(function (control) {
                    return control.name && control.type !== 'hidden' && control.type !== 'submit' && control.type !== 'button';
                });

                function errorNode(control) {
                    var wrapper = control.closest('label') || control.parentElement;
                    return wrapper ? wrapper.querySelector('[data-field-error]') : null;
                }

                function renderError(control, valid, message) {
                    var node = errorNode(control);

                    control.classList.toggle('is-invalid', !valid);
                    control.setAttribute('aria-invalid', valid ? 'false' : 'true');

                    if (node) {
                        node.textContent = valid ? '' : message;
                    }
                }

                function syncMatchValidity(control) {
                    var targetName = control.getAttribute('data-match');

                    if (!targetName) {
                        return;
                    }

                    var source = form.querySelector('[name="' + targetName + '"]');

                    if (!source) {
                        control.setCustomValidity('');
                        return;
                    }

                    if (control.value && source.value && control.value !== source.value) {
                        control.setCustomValidity(control.getAttribute('data-match-message') || 'This field must match.');
                        return;
                    }

                    control.setCustomValidity('');
                }

                function validateControl(control) {
                    if (control.disabled) {
                        return true;
                    }

                    syncMatchValidity(control);

                    var valid = control.checkValidity();
                    renderError(control, valid, valid ? '' : control.validationMessage);

                    return valid;
                }

                controls.forEach(function (control) {
                    control.addEventListener('blur', function () {
                        validateControl(control);
                    });

                    control.addEventListener('input', function () {
                        if (control.classList.contains('is-invalid') || control.hasAttribute('data-match')) {
                            validateControl(control);
                        }
                    });

                    control.addEventListener('change', function () {
                        validateControl(control);
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
    @include('shared.file-preview-script')
    <script>
        (function () {
            var btn = document.querySelector('[data-theme-toggle]');
            var icon = document.querySelector('[data-theme-icon]');
            if (!btn) return;

            function updateIcon() {
                var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                if (icon) icon.textContent = isDark ? '☀️' : '🌙';
            }

            updateIcon();

            btn.addEventListener('click', function () {
                var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                if (isDark) {
                    document.documentElement.removeAttribute('data-theme');
                    localStorage.removeItem('1dollar-theme');
                } else {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('1dollar-theme', 'dark');
                }
                updateIcon();
            });
        })();
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
