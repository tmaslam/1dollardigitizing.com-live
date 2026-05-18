@extends('public.layout')

@section('title', 'Embroidery Digitizing & Vector Art | '.$siteContext->displayLabel())
@section('meta_description', 'Embroidery digitizing from $1 per 1,000 stitches. 24-hour delivery, all machine formats, free revisions. Trusted by 10,000+ businesses since 2005.')
@section('canonical', url('/'))
@section('meta_image', url('/images/1dollar-Digitizing.webp'))

@section('content')
    @php
        $ctaUrl = session()->has('customer_user_id') ? url('/quote.php') : url('/sign-up.php');
        $heroFeatures = [
            ['icon' => '💰', 'title' => '$1.00', 'subtitle' => 'per 1k stitches'],
            ['icon' => '⏰', 'title' => '24h', 'subtitle' => 'Standard Turnaround'],
            ['icon' => '✓', 'title' => '100%', 'subtitle' => 'Satisfaction Guaranteed'],
        ];
        $stats = [
            ['number' => '2005', 'label' => 'Founded'],
            ['number' => '10K+', 'label' => 'Happy Customers'],
            ['number' => '1M+', 'label' => 'Designs Completed'],
        ];
        $services = [
            ['title' => 'Custom Embroidery Digitizing', 'summary' => 'Hand-built stitch files from real digitizers. Optimized paths, correct density, proper underlay — files that run clean on your machine the first time.', 'image' => url('/images/Embroidery-Digitizings-1.png?v=2'), 'href' => url('/embroidery-digitizing.php'), 'price' => '$1 / 1k stitches', 'image_fit' => 'cover', 'image_class' => 'service-card-image-stretch'],
            ['title' => '3D Puff Embroidery', 'summary' => 'Puff digitizing that actually holds its shape. Correct foam specs, tight satin coverage, clean edges — no blowout, no thread breaks.', 'image' => url('/images/3D-puff.webp?v=2'), 'href' => url('/3d-puff-embroidery-digitizing.php'), 'price' => 'Cap embroidery', 'image_fit' => 'cover'],
            ['title' => 'Applique Embroidery', 'summary' => 'Applique files built for precise fabric placement and clean tackdown. Chain stitch for western, vintage, and decorative work that needs real character.', 'image' => url('/images/Applique-Embroidery-Digitizing.webp?v=2'), 'href' => url('/applique-embroidery-digitizing.php'), 'price' => 'Specialty stitching', 'image_fit' => 'cover'],
            ['title' => 'Photo Digitizing', 'summary' => 'We convert photos into embroidery that actually looks like the subject. Faces, pets, portraits — built stitch-by-stitch for readable detail at real production sizes.', 'image' => url('/images/Photo-Digitizing.jpg?v=2'), 'href' => url('/photo-digitizing.php'), 'price' => 'Portrait embroidery', 'image_fit' => 'cover', 'image_class' => 'service-card-image-photo'],
            ['title' => 'Vector Art Services', 'summary' => 'Your logo redrawn in clean, scalable vector format. Ready for print shops, screen printers, sign makers, and anything else that needs an actual vector file.', 'image' => url('/images/Vector-Art.webp?v=2'), 'href' => url('/vector-art.php'), 'price' => '$6 / hour', 'image_fit' => 'contain'],
            ['title' => 'Chain Stitch Embroidery', 'summary' => 'Old-school chain stitch with the texture and flow that flat embroidery can\'t replicate. Popular for western wear, vintage brands, and decorative applications.', 'image' => url('/images/chain-stitch-embroidery.jpg?v=2'), 'href' => url('/chain-stitch-embroidery-digitizing.php'), 'price' => 'Vintage embroidery', 'image_fit' => 'cover'],
        ];
        $features = [
            ['icon' => '💰', 'title' => 'Pricing That Makes Sense', 'summary' => '$1 per 1,000 stitches. $6 minimum. No setup fees, no complexity surcharges, no surprises on the invoice.'],
            ['icon' => '⚡', 'title' => 'Turnaround That Works for Real Shops', 'summary' => '24 hours standard. 12-hour and 8-hour rush available when a deadline doesn\'t care about your schedule.'],
            ['icon' => '✓', 'title' => 'We Fix It If It\'s Wrong', 'summary' => 'If the file doesn\'t stitch right because of our digitizing, we\'ll fix it. Free. No argument, no runaround.'],
            ['icon' => '🎨', 'title' => 'Every Format Your Machine Reads', 'summary' => 'DST, PES, EXP, VP3, JEF, XXX — whatever your machine needs, we deliver it. Just tell us the brand.'],
        ];
        $testimonials = [
            ['initials' => 'MR', 'avatar_class' => 'avatar-blue', 'name' => 'Mike Rodriguez', 'role' => 'Owner, Hill Country Embroidery', 'quote' => 'Been using these guys for my shop for about 3 years now. Turnaround is consistently fast and the files run clean on our Tajima machines.'],
            ['initials' => 'SL', 'avatar_class' => 'avatar-amber', 'name' => 'Sarah Lin', 'role' => 'Production Manager, Pacific Promotions', 'quote' => 'Switched from a local digitizer who kept missing deadlines. These folks hit the 24-hour mark every single time. Pricing is straightforward too.'],
            ['initials' => 'DW', 'avatar_class' => 'avatar-green', 'name' => 'Dave Williams', 'role' => 'Owner, Team Spirit Apparel, Ohio', 'quote' => 'The $1 first design deal got me to try them. Quality was good so I stuck around. Now they are doing all our youth sports league orders.'],
            ['initials' => 'JK', 'avatar_class' => 'avatar-rose', 'name' => 'James Kim', 'role' => 'Head Digitizer, Atlanta Custom Caps', 'quote' => 'We do a lot of 3D puff hats. The puff files from here stitch out clean without thread breaks and the turnaround stays dependable.'],
            ['initials' => 'AP', 'avatar_class' => 'avatar-violet', 'name' => 'Amanda Perez', 'role' => 'Home-Based Business, Florida', 'quote' => 'I was skeptical about the pricing but the files sewed perfectly. Customer service actually answers the phone too.'],
            ['initials' => 'TB', 'avatar_class' => 'avatar-teal', 'name' => 'Tom Benson', 'role' => 'Owner, Wild West Wear, Montana', 'quote' => 'These guys are the most consistent we have used. The chain stitch work on our western shirts comes out great every time.'],
        ];
        $faqs = [
            ['question' => 'How quickly can you deliver my digitized file?', 'answer' => "Standard is 24 hours. If that's not fast enough, we have 12-hour and 8-hour rush options. The file comes straight to your email, ready to drop on your machine."],
            ['question' => 'What file formats do you provide?', 'answer' => "Tell us what machine you're running and we'll send the right format. DST, PES, EXP, JEF, VP3, XXX — we cover all of them. Vector art goes out as AI, EPS, SVG, or PDF depending on what you need it for."],
            ['question' => 'How much does embroidery digitizing cost?', 'answer' => '$1 per 1,000 stitches, $6 minimum. That\'s it for standard work. 3D puff is included at no extra charge, which a lot of services tack on separately. If your design is unusually complex, we\'ll flag that in the quote before anything starts.'],
            ['question' => "What if I'm not satisfied with the digitized file?", 'answer' => "If the file doesn't stitch correctly because of our digitizing — not because of a machine issue or fabric issue — we fix it. Free, no questions asked. We've been doing this since 2005. Our reputation depends on getting it right."],
            ['question' => 'Do you offer a first-time customer discount?', 'answer' => "New customers get their first hat or left chest logo digitized for $1 flat (up to 10,000 stitches). No code needed. Just mention it when you submit your first quote request and we'll apply it."],
            ['question' => 'How do I send you my design?', 'answer' => 'Upload it through the quote form — JPG, PNG, PDF all work fine. If you have an AI or EPS file, even better. The higher the resolution the better, but we work with what you have and let you know if we need something cleaner.'],
            ['question' => 'Can you digitize photos for embroidery?', 'answer' => 'Yes. Portraits, pets, memorial patches — we do them. It takes more time than a logo because every element has to be interpreted stitch by stitch. You\'ll get a preview before the final file so you can see how it\'s coming out.'],
            ['question' => 'What payment methods do you accept?', 'answer' => 'Visa, MasterCard, American Express, Discover, and PayPal. Payment is collected after you approve the quote. We don\'t store card details on our end — payments go through a hosted provider.'],
        ];
    @endphp

    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div>
                    <div class="hero-badge">In business since 2005 • 10,000+ shops trust us</div>
                    <h1>Embroidery Digitizing &amp; Vector Art — <span>done right, every time</span></h1>
                    <p>We've been digitizing for embroidery shops, apparel decorators, and screen printers since 2005. No auto-digitizing shortcuts. No offshore handoffs. Just experienced digitizers who know how stitch files actually behave on real machines — and build them accordingly.</p>

                    <div class="hero-buttons">
                        <a href="{{ $ctaUrl }}" class="button secondary">Get Your Free Quote</a>
                        <a href="{{ url('/book-a-meeting.php') }}" class="button primary">Book a Meeting</a>
                    </div>

                    <div class="hero-features">
                        @foreach ($heroFeatures as $feature)
                            <div class="hero-feature">
                                <div class="hero-feature-icon">{{ $feature['icon'] }}</div>
                                <div class="hero-feature-text">{{ $feature['title'] }}<span>{{ $feature['subtitle'] }}</span></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="hero-image">
                    <div class="hero-visual-card">
                        <img src="{{ url('/images/hero-image.jpg') }}?v=1" alt="Embroidery Digitizing Sample">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                @foreach ($stats as $stat)
                    <div class="stat-card">
                        <div class="stat-number">{{ $stat['number'] }}</div>
                        <div class="stat-label">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section" id="services">
        <div class="container">
            <div class="section-header home-services-header">
                <span class="section-label">Our services</span>
                <h2>What We <span>Digitize &amp; Convert</span></h2>
                <p>Pick the service that fits the job. Each one is hand-built by experienced digitizers — no auto shortcuts.</p>
            </div>

            <div class="services-grid">
                @foreach ($services as $service)
                    <article class="service-card">
                        @if (($service['image_class'] ?? '') === 'service-card-image-stretch')
                            <div class="service-card-img-wrap">
                        @endif
                        <img
                            class="{{ trim((($service['image_fit'] ?? '') === 'contain' ? 'service-card-image-contain ' : '').($service['image_class'] ?? '')) }}"
                            src="{{ $service['image'] }}"
                            alt="{{ $service['title'] }}"
                            loading="lazy"
                            @if (($service['image_class'] ?? '') === 'service-card-image-photo')
                                style="height:auto;width:100%;object-fit:contain;object-position:center top;padding:0;background:#ffffff;"
                            @endif
                        >
                        @if (($service['image_class'] ?? '') === 'service-card-image-stretch')
                            </div>
                        @endif
                        <div class="service-card-content">
                            <h3>{{ $service['title'] }}</h3>
                            <p>{{ $service['summary'] }}</p>
                            <span class="price-tag">{{ $service['price'] }}</span>
                            <a href="{{ $service['href'] }}" class="inline-link">Learn more →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-surface">
        <div class="container">
            <div class="home-benefits-header-v2">
                <h2>Why Shops Keep <span>Coming Back</span></h2>
                <p>We're not the only digitizing service out there. Here's what makes the difference after you've tried a few.</p>
            </div>

            <div class="home-benefits-row">
                @foreach ($features as $feature)
                    <div class="home-benefit-card-v2">
                        <div class="home-benefit-icon-v2">{{ $feature['icon'] }}</div>
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['summary'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <section class="section section-surface home-testimonials-section">
        <div class="container">
            <div class="section-header home-testimonials-header">
                <span class="section-label">What customers say</span>
                <h2>Trusted by embroidery and apparel <span>businesses across the globe</span></h2>
            </div>

            <div class="marketing-testimonial-rows">
                @foreach (array_chunk($testimonials, 3) as $testimonialRow)
                    <div class="marketing-testimonials">
                        @foreach ($testimonialRow as $testimonial)
                            <div class="marketing-testimonial-card">
                                <div class="marketing-stars">★★★★★</div>
                                <p>"{{ $testimonial['quote'] }}"</p>
                                <div class="marketing-testimonial-person">
                                    <div class="marketing-testimonial-avatar {{ $testimonial['avatar_class'] ?? '' }}">{{ $testimonial['initials'] }}</div>
                                    <div class="marketing-testimonial-meta">
                                        <strong>{{ $testimonial['name'] }}</strong>
                                        <span>{{ $testimonial['role'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section home-faq-section">
        <div class="container">
            <div class="section-header home-faq-header">
                <span class="section-label">FAQ</span>
                <h2>Common <span>Questions</span></h2>
                <p>Don't see what you need? Reach out — we usually respond the same day.</p>
                <a href="{{ url('/contact-us.php') }}" class="home-faq-contact-btn">♡ Contact us</a>
            </div>

            <div class="marketing-faq-list faq-list-home home-faq-list">
                @foreach ($faqs as $faq)
                    <details class="marketing-faq-item">
                        <summary>
                            <span class="faq-question">{{ $faq['question'] }}</span>
                            <span class="faq-toggle-icon" aria-hidden="true">
                                <span class="faq-toggle-plus">+</span>
                                <span class="faq-toggle-minus">−</span>
                            </span>
                        </summary>
                        <div class="faq-answer">
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    @php
        $faqPageSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => array_map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name'  => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $faq['answer'],
                    ],
                ];
            }, $faqs),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $faqPageSchema !!}</script>

    <style>
        .home-benefits-header-v2 {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 40px;
        }
        .home-benefits-header-v2 h2 {
            font-size: clamp(1.9rem, 3.5vw, 2.6rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: #0f172a;
            margin: 0 0 10px;
        }
        .home-benefits-header-v2 h2 span { color: #169fe6; }
        .home-benefits-header-v2 p {
            font-size: 1rem;
            line-height: 1.6;
            color: #64748b;
            margin: 0;
        }
        .home-benefits-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .home-benefit-card-v2 {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 28px 20px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        }
        .home-benefit-icon-v2 {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(37, 99, 235, 0.08);
            color: #169fe6;
            font-size: 1.3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .home-benefit-card-v2 h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 10px;
            line-height: 1.35;
        }
        .home-benefit-card-v2 p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #64748b;
            margin: 0;
        }
        @media (max-width: 960px) {
            .home-benefits-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 560px) {
            .home-benefits-row { grid-template-columns: 1fr; }
        }

    </style>
@endsection
