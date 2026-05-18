@extends('layouts.admin')

@section('title', 'Edit Post | Admin')
@section('page_heading', 'Edit Blog Post')
@section('page_subheading', 'Update content, SEO fields, and publish settings.')

@section('content')
<style>
    .blog-form { display: grid; gap: 22px; }
    .form-card { background: var(--panel); border: 1px solid rgba(255,255,255,0.66); border-radius: 26px; box-shadow: var(--shadow); backdrop-filter: blur(14px); overflow: hidden; }
    .form-card-head { padding: 16px 22px; border-bottom: 1px solid rgba(24,34,45,0.1); background: rgba(15,95,102,0.06); }
    .form-card-head h3 { margin: 0; font-size: 1rem; letter-spacing: -0.02em; }
    .form-card-body { padding: 22px; display: grid; gap: 18px; }
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .field-full { display: grid; gap: 6px; }
    .field-half { display: grid; gap: 6px; }
    .field-label { font-size: 0.84rem; font-weight: 700; color: var(--muted); }
    .field-hint { font-size: 0.78rem; color: var(--muted); margin-top: 4px; }
    .char-counter { font-size: 0.78rem; font-weight: 700; color: var(--muted); }
    .char-counter.warn { color: var(--warning); }
    .char-counter.ok { color: #2a7a4b; }
    .current-image { display: flex; align-items: flex-start; gap: 14px; padding: 14px; background: rgba(255,255,255,0.6); border: 1px solid var(--line); border-radius: 14px; }
    .current-image img { width: 120px; height: 80px; object-fit: cover; border-radius: 10px; flex-shrink: 0; }
    .current-image-info { font-size: 0.84rem; color: var(--muted); line-height: 1.5; }
    textarea { resize: vertical; min-height: 100px; }
    .form-actions { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    @media(max-width:720px) { .field-row { grid-template-columns: 1fr; } }
</style>

<form method="post" action="{{ url('/v/blogs/'.$blog->id.'/edit') }}" enctype="multipart/form-data" class="blog-form" id="blog-form">
    @csrf

    @if ($errors->any())
        <div class="alert">
            <strong>Please fix the following errors:</strong>
            <ul style="margin:8px 0 0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- BASIC INFO --}}
    <div class="form-card">
        <div class="form-card-head"><h3>Post Details</h3></div>
        <div class="form-card-body">
            <div class="field-full">
                <label class="field-label" for="title">Post Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $blog->title) }}" required>
            </div>
            <div class="field-row">
                <div class="field-half">
                    <label class="field-label" for="slug">URL Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $blog->slug) }}">
                    <span class="field-hint">Public URL: /blog/{{ $blog->slug ?: 'your-slug' }}</span>
                </div>
                <div class="field-half">
                    <label class="field-label" for="author_name">Author Name *</label>
                    <input type="text" id="author_name" name="author_name" value="{{ old('author_name', $blog->author_name) }}" required>
                </div>
            </div>
            <div class="field-row">
                <div class="field-half">
                    <label class="field-label" for="category">Category</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $blog->category) }}" placeholder="e.g. Embroidery Tips">
                </div>
                <div class="field-half">
                    <label class="field-label" for="tags">Tags <span style="font-weight:400">(comma-separated)</span></label>
                    <input type="text" id="tags" name="tags" value="{{ old('tags', $blog->tags) }}" placeholder="e.g. digitizing, embroidery, DST">
                </div>
            </div>
            <div class="field-full">
                <label class="field-label" for="excerpt">Excerpt / Short Description * <span class="char-counter" id="excerpt-counter"></span></label>
                <textarea id="excerpt" name="excerpt" rows="3" maxlength="500" required>{{ old('excerpt', $blog->excerpt) }}</textarea>
            </div>
        </div>
    </div>

    {{-- HERO IMAGE --}}
    <div class="form-card">
        <div class="form-card-head"><h3>Hero Image</h3></div>
        <div class="form-card-body">
            @if ($blog->hero_image)
                <div class="current-image">
                    <img src="{{ $blog->heroImageUrl() }}" alt="{{ $blog->hero_image_alt }}">
                    <div class="current-image-info">
                        <strong>Current hero image</strong><br>
                        Upload a new file below to replace it.
                    </div>
                </div>
            @endif
            <div class="field-full">
                <label class="field-label" for="hero_image">{{ $blog->hero_image ? 'Replace Hero Image' : 'Hero Image *' }} <span style="font-weight:400">(JPG, PNG, WebP — max 5 MB)</span></label>
                <input type="file" id="hero_image" name="hero_image" accept="image/jpeg,image/png,image/webp,image/gif" {{ $blog->hero_image ? '' : 'required' }}>
                <div id="hero-preview" style="display:none;margin-top:10px;">
                    <img id="hero-preview-img" src="" alt="Preview" style="max-height:160px;border-radius:12px;border:1px solid var(--line);object-fit:cover;">
                </div>
            </div>
            <div class="field-full">
                <label class="field-label" for="hero_image_alt">Hero Image Alt Text * <span class="char-counter" id="alt-counter"></span></label>
                <input type="text" id="hero_image_alt" name="hero_image_alt" value="{{ old('hero_image_alt', $blog->hero_image_alt) }}" required maxlength="200">
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="form-card">
        <div class="form-card-head"><h3>Post Content *</h3></div>
        <div class="form-card-body">
            <textarea id="content" name="content" rows="20">{{ old('content', $blog->content) }}</textarea>
        </div>
    </div>

    {{-- PUBLISH SETTINGS --}}
    <div class="form-card">
        <div class="form-card-head"><h3>Publish Settings</h3></div>
        <div class="form-card-body">
            <div class="field-row">
                <div class="field-half">
                    <label class="field-label" for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="draft" @selected(old('status', $blog->status) === 'draft')>Draft — not visible on site</option>
                        <option value="published" @selected(old('status', $blog->status) === 'published')>Published — live on site</option>
                    </select>
                </div>
                <div class="field-half">
                    <label class="field-label" for="published_at">Publish Date</label>
                    <input type="datetime-local" id="published_at" name="published_at"
                           value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- SEO --}}
    <div class="form-card">
        <div class="form-card-head"><h3>SEO &amp; Open Graph</h3></div>
        <div class="form-card-body">
            <div class="field-full">
                <label class="field-label" for="meta_title">
                    Meta Title * <span class="char-counter" id="meta-title-counter"></span>
                </label>
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" required maxlength="70">
                <span class="field-hint">Target: 50–60 characters. Currently: <span id="meta-title-len">0</span> chars.</span>
            </div>
            <div class="field-full">
                <label class="field-label" for="meta_description">
                    Meta Description * <span class="char-counter" id="meta-desc-counter"></span>
                </label>
                <textarea id="meta_description" name="meta_description" rows="3" required maxlength="200">{{ old('meta_description', $blog->meta_description) }}</textarea>
                <span class="field-hint">Target: 140–160 characters. Currently: <span id="meta-desc-len">0</span> chars.</span>
            </div>
            <div class="field-full">
                <label class="field-label" for="og_image">OG / Social Share Image <span style="font-weight:400">(optional — uses hero image if blank)</span></label>
                @if ($blog->og_image)
                    <div class="current-image" style="margin-bottom:10px;">
                        <img src="{{ $blog->ogImageUrl() }}" alt="Current OG image">
                        <div class="current-image-info"><strong>Current OG image</strong><br>Upload a new file to replace it.</div>
                    </div>
                @endif
                <input type="file" id="og_image" name="og_image" accept="image/jpeg,image/png,image/webp">
                <span class="field-hint">Recommended: 1200×630 px.</span>
                <div id="og-preview" style="display:none;margin-top:10px;">
                    <img id="og-preview-img" src="" alt="OG preview" style="max-height:160px;border-radius:12px;border:1px solid var(--line);object-fit:cover;">
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="form-card">
        <div class="form-card-body">
            <div class="form-actions">
                <button type="submit">Update Post</button>
                @if ($blog->slug)
                    <a class="button secondary" href="{{ url('/blog/'.$blog->slug) }}" target="_blank">View Live</a>
                @endif
                <a class="button secondary" href="{{ url('/v/blogs') }}">Back to List</a>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6',
    suffix: '.min',
    height: 560,
    menubar: true,
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media | removeformat | code fullscreen',
    images_upload_url: '{{ url('/v/blog-image-upload') }}',
    images_upload_credentials: true,
    automatic_uploads: true,
    file_picker_types: 'image',
    content_style: 'body { font-family: "Segoe UI", Roboto, sans-serif; font-size: 16px; line-height: 1.7; color: #182a3e; max-width: none; padding: 20px; }',
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ url('/v/blog-image-upload') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.upload.onprogress = function (e) { if (e.lengthComputable) progress(e.loaded / e.total * 100); };
            xhr.onload = function () {
                if (xhr.status !== 200) { reject('Upload failed: ' + xhr.status); return; }
                var json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location !== 'string') { reject('Invalid response'); return; }
                resolve(json.location);
            };
            xhr.onerror = function () { reject('Network error'); };
            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        });
    },
});

function initCounter(inputId, counterId, lenId, min, max) {
    var el = document.getElementById(inputId);
    var counter = document.getElementById(counterId);
    var lenEl = document.getElementById(lenId);
    if (!el || !counter) return;
    function update() {
        var len = el.value.length;
        if (lenEl) lenEl.textContent = len;
        counter.textContent = len + ' chars';
        counter.className = 'char-counter';
        if (len >= min && len <= max) counter.classList.add('ok');
        else if (len > max) counter.classList.add('warn');
    }
    el.addEventListener('input', update);
    update();
}
initCounter('meta_title', 'meta-title-counter', 'meta-title-len', 50, 60);
initCounter('meta_description', 'meta-desc-counter', 'meta-desc-len', 140, 160);
initCounter('excerpt', 'excerpt-counter', null, 50, 500);
initCounter('hero_image_alt', 'alt-counter', null, 10, 200);

function initImagePreview(inputId, previewWrapId, previewImgId) {
    var input = document.getElementById(inputId);
    var wrap = document.getElementById(previewWrapId);
    var img = document.getElementById(previewImgId);
    if (!input || !wrap || !img) return;
    input.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) { img.src = e.target.result; wrap.style.display = ''; };
            reader.readAsDataURL(this.files[0]);
        }
    });
}
initImagePreview('hero_image', 'hero-preview', 'hero-preview-img');
initImagePreview('og_image', 'og-preview', 'og-preview-img');
</script>
@endsection
