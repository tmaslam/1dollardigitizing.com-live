@extends('public.layout')

@section('title', $post->meta_title ?: $post->title.' | 1 Dollar Digitizing Blog')
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('canonical', $post->publicUrl())
@section('meta_og_type', 'article')
@if ($post->ogImageUrl())
    @section('meta_image', $post->ogImageUrl())
@endif

@section('content')

    {{-- Hero Image --}}
    @if ($post->heroImageUrl())
        <div class="blog-post-hero">
            <img src="{{ $post->heroImageUrl() }}"
                 alt="{{ $post->hero_image_alt }}"
                 class="blog-post-hero-img"
                 width="1200" height="600">
        </div>
    @endif

    <section class="section">
        <div class="container">
            <div class="blog-post-layout">

                {{-- Main Content --}}
                <article class="blog-post-article" itemscope itemtype="https://schema.org/BlogPosting">
                    <meta itemprop="headline" content="{{ $post->meta_title ?: $post->title }}">
                    <meta itemprop="description" content="{{ $post->meta_description ?: $post->excerpt }}">
                    @if ($post->heroImageUrl())
                        <meta itemprop="image" content="{{ $post->heroImageUrl() }}">
                    @endif
                    @if ($post->published_at)
                        <meta itemprop="datePublished" content="{{ $post->published_at->toIso8601String() }}">
                    @endif
                    @if ($post->updated_at)
                        <meta itemprop="dateModified" content="{{ $post->updated_at->toIso8601String() }}">
                    @endif

                    {{-- Breadcrumb --}}
                    <nav class="blog-breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ url('/') }}">Home</a>
                        <span aria-hidden="true">/</span>
                        <a href="{{ url('/blog') }}">Blog</a>
                        @if ($post->category)
                            <span aria-hidden="true">/</span>
                            <a href="{{ url('/blog') }}?category={{ urlencode($post->category) }}">{{ $post->category }}</a>
                        @endif
                        <span aria-hidden="true">/</span>
                        <span aria-current="page">{{ Str::limit($post->title, 50) }}</span>
                    </nav>

                    {{-- Header --}}
                    <header class="blog-post-header">
                        @if ($post->category)
                            <a href="{{ url('/blog') }}?category={{ urlencode($post->category) }}" class="blog-post-category">{{ $post->category }}</a>
                        @endif
                        <h1 class="blog-post-title" itemprop="name">{{ $post->title }}</h1>
                        <div class="blog-post-meta">
                            @if ($post->author_name)
                                <span itemprop="author" itemscope itemtype="https://schema.org/Organization">
                                    <meta itemprop="name" content="{{ $post->author_name }}">
                                    By <strong>{{ $post->author_name }}</strong>
                                </span>
                                <span class="blog-meta-sep">&middot;</span>
                            @endif
                            <time datetime="{{ $post->published_at?->toIso8601String() ?? $post->created_at?->toIso8601String() }}">
                                {{ $post->readableDate() }}
                            </time>
                            @if ($post->getTagsArray())
                                <span class="blog-meta-sep">&middot;</span>
                                <span>{{ implode(', ', $post->getTagsArray()) }}</span>
                            @endif
                        </div>
                    </header>

                    {{-- Body --}}
                    <div class="blog-post-content" itemprop="articleBody">
                        {!! purify($post->content) !!}
                    </div>

                    {{-- Tags --}}
                    @if ($post->getTagsArray())
                        <div class="blog-post-tags">
                            <span class="blog-tags-label">Tags:</span>
                            @foreach ($post->getTagsArray() as $tag)
                                <span class="blog-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- CTA --}}
                    <div class="blog-post-cta">
                        <div>
                            <h3>Ready to digitize your design?</h3>
                            <p>Professional embroidery digitizing from $1 — 24-hour turnaround, all machine formats, free revisions.</p>
                        </div>
                        <div class="theme-header-actions" style="margin:0;flex-shrink:0;">
                            <a class="button primary" href="{{ url('/sign-up.php') }}">Get a Quote</a>
                            <a class="button secondary" href="{{ url('/contact-us.php') }}">Contact Us</a>
                        </div>
                    </div>
                </article>

                {{-- Sidebar --}}
                <aside class="blog-post-sidebar">
                    <div class="blog-sidebar-card">
                        <h4>Need Embroidery Digitizing?</h4>
                        <p>Starting at $1 per design. 24-hour delivery, all formats, free revisions.</p>
                        <a class="button primary" href="{{ url('/sign-up.php') }}" style="width:100%;justify-content:center;">Get Started</a>
                    </div>

                    @if ($related->isNotEmpty())
                        <div class="blog-sidebar-card">
                            <h4>Related Articles</h4>
                            <div class="blog-sidebar-related">
                                @foreach ($related as $item)
                                    <a href="{{ $item->publicUrl() }}" class="blog-sidebar-related-item">
                                        @if ($item->heroImageUrl())
                                            <img src="{{ $item->heroImageUrl() }}" alt="{{ $item->hero_image_alt }}" loading="lazy">
                                        @endif
                                        <div>
                                            <span class="blog-sidebar-related-title">{{ $item->title }}</span>
                                            <span class="blog-sidebar-related-date">{{ $item->readableDate() }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="blog-sidebar-card">
                        <h4>Our Services</h4>
                        <ul class="blog-sidebar-links">
                            <li><a href="{{ url('/embroidery-digitizing.php') }}">Embroidery Digitizing</a></li>
                            <li><a href="{{ url('/3d-puff-embroidery-digitizing.php') }}">3D Puff Digitizing</a></li>
                            <li><a href="{{ url('/applique-embroidery-digitizing.php') }}">Applique Digitizing</a></li>
                            <li><a href="{{ url('/chain-stitch-embroidery-digitizing.php') }}">Chain Stitch</a></li>
                            <li><a href="{{ url('/vector-art.php') }}">Vector Art</a></li>
                            <li><a href="{{ url('/photo-digitizing.php') }}">Photo Digitizing</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- JSON-LD BlogPosting Schema --}}
    @php
        $blogSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'BlogPosting',
            'headline' => $post->meta_title ?: $post->title,
            'description' => $post->meta_description ?: $post->excerpt,
            'image' => $post->heroImageUrl() ?? url('/images/logo.webp'),
            'datePublished' => $post->published_at?->toIso8601String() ?? $post->created_at?->toIso8601String(),
            'dateModified'  => $post->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name'  => $post->author_name ?: '1 Dollar Digitizing',
                'url'   => url('/'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name'  => '1 Dollar Digitizing',
                'url'   => url('/'),
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => url('/images/logo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => $post->publicUrl(),
            ],
            'url' => $post->publicUrl(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $breadcrumbSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => array_filter([
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',    'item' => url('/blog')],
                $post->category ? ['@type' => 'ListItem', 'position' => 3, 'name' => $post->category, 'item' => url('/blog').'?category='.urlencode($post->category)] : null,
                ['@type' => 'ListItem', 'position' => $post->category ? 4 : 3, 'name' => $post->title, 'item' => $post->publicUrl()],
            ]),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $blogSchema !!}</script>
    <script type="application/ld+json">{!! $breadcrumbSchema !!}</script>

    <style>
        .blog-post-hero {
            width: 100%;
            max-height: 520px;
            overflow: hidden;
        }
        .blog-post-hero-img {
            width: 100%;
            height: 520px;
            object-fit: cover;
            object-position: top center;
            display: block;
        }
        .blog-post-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 40px;
            align-items: start;
        }
        .blog-post-article {
            min-width: 0;
        }
        .blog-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            font-size: 0.84rem;
            color: #8fa0b4;
            margin-bottom: 24px;
        }
        .blog-breadcrumb a { color: #169fe6; }
        .blog-breadcrumb a:hover { color: #0d6ea3; }
        .blog-post-header { margin-bottom: 32px; }
        .blog-post-category {
            display: inline-block;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #169fe6;
            margin-bottom: 12px;
        }
        .blog-post-title {
            margin: 0 0 16px;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            line-height: 1.2;
            letter-spacing: -0.03em;
            color: #182a3e;
        }
        .blog-post-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #8fa0b4;
        }
        .blog-meta-sep { opacity: 0.5; }
        .blog-post-content {
            font-size: 1.05rem;
            line-height: 1.78;
            color: #2d3f52;
        }
        .blog-post-content h2 { font-size: 1.5rem; letter-spacing: -0.02em; margin: 2em 0 0.6em; color: #182a3e; }
        .blog-post-content h3 { font-size: 1.18rem; margin: 1.8em 0 0.5em; color: #182a3e; }
        .blog-post-content p { margin: 0 0 1.2em; }
        .blog-post-content ul, .blog-post-content ol { padding-left: 1.5em; margin: 0 0 1.2em; }
        .blog-post-content li { margin-bottom: 0.4em; }
        .blog-post-content img { max-width: 100%; height: auto; border-radius: 14px; margin: 1.4em 0; box-shadow: 0 14px 30px rgba(12,48,89,0.09); }
        .blog-post-content a { color: #169fe6; }
        .blog-post-content a:hover { color: #0d6ea3; }
        .blog-post-content blockquote { border-left: 4px solid #169fe6; margin: 1.4em 0; padding: 12px 20px; background: rgba(22,159,230,0.06); border-radius: 0 12px 12px 0; font-style: italic; color: #526071; }
        .blog-post-content table { width: 100%; border-collapse: collapse; margin: 1.4em 0; }
        .blog-post-content th, .blog-post-content td { padding: 10px 14px; border: 1px solid rgba(22,159,230,0.18); }
        .blog-post-content th { background: rgba(22,159,230,0.08); font-weight: 700; }
        .blog-post-tags {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(22,159,230,0.12);
        }
        .blog-tags-label { font-size: 0.82rem; font-weight: 700; color: #8fa0b4; text-transform: uppercase; letter-spacing: 0.08em; }
        .blog-tag { padding: 5px 12px; border-radius: 999px; background: rgba(22,159,230,0.09); border: 1px solid rgba(22,159,230,0.16); font-size: 0.82rem; color: #0d6ea3; font-weight: 600; }
        .blog-post-cta {
            margin-top: 40px;
            padding: 28px 30px;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(22,159,230,0.08), rgba(22,159,230,0.03));
            border: 1px solid rgba(22,159,230,0.16);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }
        .blog-post-cta h3 { margin: 0 0 8px; font-size: 1.1rem; color: #182a3e; }
        .blog-post-cta p { margin: 0; color: #526071; line-height: 1.6; font-size: 0.95rem; }

        /* Sidebar */
        .blog-post-sidebar { display: grid; gap: 20px; }
        .blog-sidebar-card {
            background: #fff;
            border: 1px solid rgba(22,159,230,0.12);
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 14px 28px rgba(12,48,89,0.07);
        }
        .blog-sidebar-card h4 { margin: 0 0 12px; font-size: 1rem; color: #182a3e; letter-spacing: -0.01em; }
        .blog-sidebar-card p { margin: 0 0 16px; font-size: 0.9rem; color: #526071; line-height: 1.6; }
        .blog-sidebar-links { margin: 0; padding: 0; list-style: none; display: grid; gap: 8px; }
        .blog-sidebar-links li a { font-size: 0.9rem; color: #169fe6; }
        .blog-sidebar-links li a:hover { color: #0d6ea3; }
        .blog-sidebar-related { display: grid; gap: 14px; }
        .blog-sidebar-related-item { display: flex; gap: 12px; align-items: flex-start; text-decoration: none; }
        .blog-sidebar-related-item img { width: 64px; height: 48px; object-fit: cover; border-radius: 8px; flex-shrink: 0; }
        .blog-sidebar-related-title { display: block; font-size: 0.88rem; color: #182a3e; line-height: 1.35; font-weight: 600; }
        .blog-sidebar-related-item:hover .blog-sidebar-related-title { color: #169fe6; }
        .blog-sidebar-related-date { display: block; font-size: 0.78rem; color: #8fa0b4; margin-top: 3px; }

        @media (max-width: 1024px) {
            .blog-post-layout { grid-template-columns: 1fr; }
            .blog-post-sidebar { display: none; }
        }
        @media (max-width: 640px) {
            .blog-post-hero-img { height: 260px; }
            .blog-post-cta { flex-direction: column; align-items: flex-start; }
        }
    </style>
@endsection
