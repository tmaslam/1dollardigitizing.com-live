@extends('public.layout')

@section('title', 'Embroidery Digitizing Blog | Tips, Guides & Industry News')
@section('meta_description', 'Expert articles on embroidery digitizing, file formats, machine settings, and production tips. Practical guides for embroiderers and apparel decorators.')
@section('canonical', url('/blog'))

@section('content')

    <section class="page-header">
        <div class="container">
            <div>
                <span class="theme-badge">Blog</span>
                <h1>Embroidery Digitizing <span>Blog</span></h1>
                <p>Expert guides, production tips, and industry news for embroiderers and apparel decorators.</p>
                <div class="theme-header-actions">
                    <a class="button primary" href="{{ url('/sign-up.php') }}">Get a Quote</a>
                    <a class="button secondary" href="{{ url('/contact-us.php') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section style="background:#f3f7fb;border-bottom:1px solid rgba(22,159,230,0.12);">
            <div class="container" style="padding-top:18px;padding-bottom:18px;">
                <div class="blog-category-nav">
                    <a href="{{ url('/blog') }}" class="{{ request()->query('category') ? '' : 'active' }}">All Posts</a>
                    @foreach ($categories as $cat)
                        <a href="{{ url('/blog') }}?category={{ urlencode($cat) }}"
                           class="{{ request()->query('category') === $cat ? 'active' : '' }}">{{ $cat }}</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            @if ($posts->isEmpty())
                <div style="text-align:center;padding:60px 20px;color:#526071;">
                    <p style="font-size:1.1rem;">No posts published yet. Check back soon!</p>
                    <a class="button primary" href="{{ url('/our-services.php') }}" style="margin-top:18px;">Explore Our Services</a>
                </div>
            @else
                <div class="blog-grid">
                    @foreach ($posts as $post)
                        <article class="blog-card">
                            @if ($post->heroImageUrl())
                                <a href="{{ $post->publicUrl() }}" class="blog-card-image-link">
                                    <img src="{{ $post->heroImageUrl() }}"
                                         alt="{{ $post->hero_image_alt }}"
                                         class="blog-card-image"
                                         loading="lazy"
                                         width="640" height="360">
                                </a>
                            @endif
                            <div class="blog-card-body">
                                @if ($post->category)
                                    <a href="{{ url('/blog') }}?category={{ urlencode($post->category) }}" class="blog-card-category">{{ $post->category }}</a>
                                @endif
                                <h2 class="blog-card-title">
                                    <a href="{{ $post->publicUrl() }}">{{ $post->title }}</a>
                                </h2>
                                @if ($post->excerpt)
                                    <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 160) }}</p>
                                @endif
                                <div class="blog-card-meta">
                                    <span>{{ $post->readableDate() }}</span>
                                    @if ($post->author_name)
                                        <span>&middot;</span>
                                        <span>{{ $post->author_name }}</span>
                                    @endif
                                </div>
                                <a href="{{ $post->publicUrl() }}" class="blog-card-read-more">Read Article &rarr;</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($posts->hasPages())
                    <div class="blog-pagination">
                        {{ $posts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>

    <style>
        .blog-category-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .blog-category-nav a {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #0d6ea3;
            background: rgba(22, 159, 230, 0.08);
            border: 1px solid rgba(22, 159, 230, 0.16);
            transition: background 0.18s, color 0.18s;
        }
        .blog-category-nav a:hover,
        .blog-category-nav a.active {
            background: #169fe6;
            color: #fff;
            border-color: transparent;
        }
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 28px;
        }
        .blog-card {
            background: #fff;
            border-radius: 22px;
            border: 1px solid rgba(22, 159, 230, 0.10);
            box-shadow: 0 16px 38px rgba(12, 48, 89, 0.07);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .blog-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 50px rgba(12, 48, 89, 0.12);
        }
        .blog-card-image-link {
            display: block;
            overflow: hidden;
        }
        .blog-card-image {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        .blog-card:hover .blog-card-image {
            transform: scale(1.03);
        }
        .blog-card-body {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
        }
        .blog-card-category {
            display: inline-block;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #169fe6;
        }
        .blog-card-category:hover { color: #0d6ea3; }
        .blog-card-title {
            margin: 0;
            font-size: 1.12rem;
            line-height: 1.35;
            letter-spacing: -0.02em;
        }
        .blog-card-title a {
            color: #182a3e;
            text-decoration: none;
        }
        .blog-card-title a:hover { color: #169fe6; }
        .blog-card-excerpt {
            margin: 0;
            font-size: 0.9rem;
            color: #526071;
            line-height: 1.65;
            flex: 1;
        }
        .blog-card-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #8fa0b4;
        }
        .blog-card-read-more {
            display: inline-block;
            font-size: 0.88rem;
            font-weight: 800;
            color: #169fe6;
            margin-top: 4px;
        }
        .blog-card-read-more:hover { color: #0d6ea3; }
        .blog-pagination {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }
        @media (max-width: 960px) {
            .blog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 600px) {
            .blog-grid { grid-template-columns: 1fr; }
        }
    </style>

@endsection
