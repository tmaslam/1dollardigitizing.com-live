@extends('public.layout')

@section('title', 'Contact 1 Dollar Digitizing | Get a Quote or Support')
@section('meta_description', 'Contact 1 Dollar Digitizing for embroidery digitizing quotes, file support, or billing help. Call us or fill out our online form — we reply within a few hours.')

@section('content')
    @php
        $contactFaqs = [
            'How quickly can you deliver my digitized file?' => 'Standard turnaround is 24 hours. We also have Priority (12-hour) and Super Rush (8-hour) options for when a deadline is tight — pick the one that fits your schedule when you submit.',
            'What file formats do you provide?' => 'All the major machine formats: DST (Tajima), PES (Brother), EXP (Melco), VP3 (Pfaff), JEF (Janome), XXX (Singer), HUS, SEW, and more. Vector work comes as AI, EPS, SVG, or PDF. Just let us know what you\'re running.',
            'Do you offer free revisions?' => 'Yes. If the file doesn\'t stitch correctly because of something on our end, we fix it free. Just send us the stitch-out photo and tell us what\'s wrong — we\'ll sort it.',
            'How do I get a quote?' => 'Fill out the form on this page, or sign up and use the quote tool. Upload your artwork — JPG, PNG, PDF, AI all work — include the size and garment type, and we\'ll have a price back to you within a few hours.',
            'What types of payment do you accept?' => 'Visa, MasterCard, Amex, Discover, and PayPal. You pay after approving the quote. We process through Stripe and 2Checkout — card details aren\'t stored on our end.',
        ];
    @endphp

    <section class="page-header">
        <div class="container">
            <div>
                <span class="theme-badge">Contact Us</span>
                <h1>Ask a <span>Question</span></h1>
                <p>Send us your artwork and project details using the form below. We’ll come back with a price and turnaround estimate — usually within a few hours.</p>
                <div class="theme-header-actions">
                    <a class="button primary" href="{{ url('/sign-up.php') }}">Request A Quote</a>
                    @if ($siteContext->phoneNumber)<a class="button secondary" href="tel:{{ $siteContext->phoneForTel() }}">Call Us</a>@endif
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Reach Us Directly</h2>
                    <p>Got a question about an order, a file that needs fixing, or a job with a tight deadline? Call or email — we're reachable during business hours and respond to most emails the same day.</p>

                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="contact-method-icon">📞</div>
                            <div>
                                <h3>Phone</h3>
                                <p>@if ($siteContext->phoneNumber)<a href="tel:{{ $siteContext->phoneForTel() }}">{{ $siteContext->phoneNumber }}</a><br>@endif Mon-Fri 9AM-6PM PST</p>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="contact-method-icon">✉️</div>
                            <div>
                                <h3>Email</h3>
                                <p><a href="mailto:{{ $siteContext->supportEmail }}">{{ $siteContext->supportEmail }}</a><br>We reply within 24 hours</p>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="contact-method-icon">📍</div>
                            <div>
                                <h3>Address</h3>
                                <p>{{ $siteContext->companyAddress ?: '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <form method="post" action="{{ url('/contact-us.php') }}" class="contact-form" data-validate-form novalidate id="contact-form">
                        @csrf
                        <input type="text" name="website_url" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;">
                        <h3 class="form-title">Ask a Question</h3>

                        @if (session('success'))
                            <div class="alert success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert">{{ $errors->first() }}</div>
                        @endif

                        <div class="form-group">
                            <label class="form-label" for="contact-name">Full Name *</label>
                            <input id="contact-name" type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact-email">Email Address *</label>
                            <input id="contact-email" type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact-company">Company</label>
                            <input id="contact-company" type="text" name="company" class="form-input" value="{{ old('company') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact-phone">Phone Number</label>
                            <input id="contact-phone" type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact-subject">Subject *</label>
                            <input id="contact-subject" type="text" name="subject" class="form-input" value="{{ old('subject') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact-message">Project Details *</label>
                            <textarea id="contact-message" name="message" class="form-textarea" required>{{ old('message') }}</textarea>
                        </div>

                        @include('shared.turnstile')

                        <button type="submit" class="button primary button-block">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked <span>Questions</span></h2>
            </div>

            <div class="marketing-faq-list faq-list-wide">
                @foreach ($contactFaqs as $question => $answer)
                    <details class="marketing-faq-item">
                        <summary>
                            <span class="faq-question">{{ $question }}</span>
                            <span class="faq-toggle-icon" aria-hidden="true">
                                <span class="faq-toggle-plus">+</span>
                                <span class="faq-toggle-minus">−</span>
                            </span>
                        </summary>
                        <div class="faq-answer">
                            <p>{{ $answer }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    @php
        $contactFaqSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => array_map(function ($question, $answer) {
                return [
                    '@type' => 'Question',
                    'name'  => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $answer,
                    ],
                ];
            }, array_keys($contactFaqs), array_values($contactFaqs)),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $contactFaqSchema !!}</script>
@endsection
