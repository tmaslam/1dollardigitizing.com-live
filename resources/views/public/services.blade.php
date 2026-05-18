@extends('public.layout')

@section('title', 'Embroidery Digitizing Services from $1 | '.$siteContext->displayLabel())
@section('meta_description', 'Embroidery digitizing services from $1: logo digitizing, 3D puff, applique, chain stitch, vector art and more. All machine formats, free revisions included.')

@section('content')
    @php
        $serviceCards = [
            [
                'title' => 'Custom <span>Embroidery Digitizing</span>',
                'summary' => 'Hand-built stitch files for logos, text, and artwork. Correct density, proper underlay, optimized paths — built for your machine, not just exported from software.',
                'features' => [
                    'DST, PES, EXP, VP3, JEF and more',
                    'Built for your specific machine',
                    '24-hour standard delivery',
                    'Free edits if it doesn\'t stitch right',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/Embroidery-Digitizings-1.png?v=2'),
                'href' => url('/embroidery-digitizing.php'),
                'image_fit' => 'contain',
                'image_class' => 'service-card-image-stretch',
            ],
            [
                'title' => '<span>3D Puff</span> Embroidery',
                'summary' => 'Puff files built to hold shape — correct foam spec, tight satin coverage, clean edges. No blowout on the sides, no thread breaks mid-run.',
                'features' => [
                    'Correct foam height for design size',
                    'Clean satin coverage with no gaps',
                    'Structured caps and outerwear',
                    'No extra charge for puff',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/3D-puff.webp?v=2'),
                'href' => url('/3d-puff-embroidery-digitizing.php'),
                'image_fit' => 'cover',
            ],
            [
                'title' => '<span>Applique Embroidery</span>',
                'summary' => 'Applique files with precise tackdown stitches and clean borders. Chain stitch for western, vintage, and specialty work that needs real texture and flow.',
                'features' => [
                    'Accurate placement and tackdown',
                    'Works with multiple fabric types',
                    'Lower stitch count for large designs',
                    'Chain stitch for decorative work',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/Applique-Embroidery-Digitizing.webp?v=2'),
                'href' => url('/applique-embroidery-digitizing.php'),
                'image_fit' => 'cover',
            ],
            [
                'title' => '<span>Photo</span> Digitizing',
                'summary' => 'Photos converted into embroidery stitch-by-stitch. Faces, pets, portraits — built to be readable at real production sizes, not just in the simulation.',
                'features' => [
                    'Portraits and memorial designs',
                    'High density for fine detail',
                    'Preview before final delivery',
                    'Custom size on request',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/Photo-Digitizing.jpg?v=2'),
                'href' => url('/photo-digitizing.php'),
                'image_fit' => 'cover',
                'image_class' => 'service-card-image-photo',
            ],
            [
                'title' => '<span>Vector Art</span> Services',
                'summary' => 'Your logo redrawn as a proper vector file. Clean paths, correct color separations, ready for print shops, sign makers, or screen printers.',
                'features' => [
                    'AI, EPS, SVG, PDF output',
                    'Logo redraws and cleanup',
                    'Unlimited scalability',
                    'Print and cut-ready',
                ],
                'price' => '$6 per hour',
                'image' => url('/images/Vector-Art.webp?v=2'),
                'href' => url('/vector-art.php'),
            ],
            [
                'title' => '<span>Chain Stitch</span> Embroidery',
                'summary' => 'Old-school chain stitch with the looped texture flat embroidery can\'t replicate. Popular for western wear, vintage brands, and anything where character matters.',
                'features' => [
                    'Authentic looped texture',
                    'Western, vintage, decorative work',
                    'Smooth flowing curves',
                    'Works on most commercial machines',
                ],
                'price' => 'Starting at $1.50 per 1,000 stitches',
                'image' => url('/images/chain-stitch-embroidery.jpg?v=2'),
                'href' => url('/chain-stitch-embroidery-digitizing.php'),
                'image_fit' => 'cover',
            ],
            [
                'title' => '<span>Hat & Cap</span> Digitizing',
                'summary' => 'Cap embroidery fails when the digitizing ignores the curve. We build files that account for the cap\'s shape — correct underlay, proper tension, no gaps when it comes off the hoop.',
                'features' => [
                    'Structured and unstructured caps',
                    'Flat, 3D puff, and snapback styles',
                    'Underlay built for cap fabric',
                    'Front, side, and back placement',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/hat-cap-digitizing.jpg?v=2'),
                'href' => url('/hat-cap-digitizing.php'),
                'image_fit' => 'contain',
            ],
            [
                'title' => '<span>Left Chest</span> Logo Digitizing',
                'summary' => 'Left chest is the most common placement and still the one that gets cut corners. We digitize for the fabric you\'re actually using — polo, woven, fleece — not just a generic stitch file.',
                'features' => [
                    'Optimized for 3.5" standard area',
                    'Fabric-specific underlay settings',
                    'Works on polo, woven, performance',
                    'Fine detail preserved at small sizes',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/left-chest-logo-digitizing.jpg?v=2'),
                'href' => url('/left-chest-digitizing.php'),
                'image_fit' => 'contain',
            ],
            [
                'title' => '<span>Patch</span> Digitizing',
                'summary' => 'Patch digitizing is different from garment digitizing. The backing is rigid, the border needs to be last, and the sequence has to hold on felt, twill, and organza without lifting or warping.',
                'features' => [
                    'Felt, twill, and organza backing',
                    'Merrowed edge planning built in',
                    'Correct stitch sequence for patches',
                    'Iron-on, sew-on, and Velcro types',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/patch-digitizing.jpg?v=2'),
                'href' => url('/patch-digitizing.php'),
                'image_fit' => 'contain',
            ],
            [
                'title' => '<span>Jacket Back</span> Digitizing',
                'summary' => 'Large format back designs pull, shift, and warp when the digitizing isn\'t built for the size. We section fills correctly and sequence colors for production efficiency on wool, nylon, fleece, and leather.',
                'features' => [
                    'Handles 10"–13" design areas',
                    'Fill sectioning prevents fabric pull',
                    'Color sequencing for faster runs',
                    'Wool, nylon, fleece, leather settings',
                ],
                'price' => 'Starting at $1 per 1,000 stitches',
                'image' => url('/images/jacket-back-digitizing.jpg?v=2'),
                'href' => url('/jacket-back-digitizing.php'),
                'image_fit' => 'contain',
            ],
        ];
    @endphp

    <section class="page-header services-hero-flat">
        <div class="container">
            <div class="services-page-header">
                <h1><span>Embroidery Digitizing</span> Services — Starting at $1</h1>
                <p>Six services. All hand-digitized, not auto-generated. Built for apparel decorators, screen printers, and embroidery shops that need files to work the first time.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header" style="text-align:center;margin-bottom:2rem;">
                <h2>Our Full Range of <span>Digitizing Services</span></h2>
                <p>Every service is hand-digitized by experienced specialists — not auto-generated. Pick the one that fits your project.</p>
            </div>
            <div class="services-grid">
                @foreach ($serviceCards as $service)
                    <div class="service-card">
                        <div class="service-card-img-wrap">
                            <img
                                class="{{ trim((($service['image_fit'] ?? '') === 'contain' ? 'service-card-image-contain ' : '').($service['image_class'] ?? '')) }}"
                                src="{{ $service['image'] }}"
                                alt="{{ strip_tags($service['title']) }}"
                                @if (($service['image_class'] ?? '') === 'service-card-image-photo')
                                    style="height:auto;width:100%;object-fit:contain;object-position:center top;padding:0;background:#ffffff;"
                                @endif
                            >
                        </div>
                        <div class="service-card-content">
                            <h3>{!! $service['title'] !!}</h3>
                            <p>{{ $service['summary'] }}</p>
                            <ul class="service-features">
                                @foreach ($service['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                            <span class="price-tag">{{ $service['price'] }}</span>
                            <a class="inline-link" href="{{ $service['href'] }}">Learn more →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container public-center-wrap">
            <div class="template-cta-card">
                <h2>Not Sure What Service You Need?</h2>
                <p>Send us the artwork and tell us what you're making. We'll tell you what fits and what it'll cost.</p>
                <div class="theme-header-actions">
                    <a href="{{ url('/contact-us.php') }}" class="button secondary">Get Your Free Quote</a>
                </div>
            </div>
        </div>
    </section>
@endsection
