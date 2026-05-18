@extends('layouts.admin')

@section('title', 'New Blog Post | Admin')
@section('page_heading', 'New Blog Post')
@section('page_subheading', 'Create a new blog post. Fill in all SEO fields before publishing.')

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
    .image-preview-wrap { margin-top: 10px; }
    .image-preview-wrap img { max-height: 160px; border-radius: 12px; border: 1px solid var(--line); object-fit: cover; }
    textarea { resize: vertical; min-height: 100px; }
    .form-actions { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    @media(max-width:720px) { .field-row { grid-template-columns: 1fr; } }
</style>

<form method="post" action="{{ url('/v/blogs') }}" enctype="multipart/form-data" class="blog-form" id="blog-form">
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
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Enter a clear, descriptive title">
            </div>
            <div class="field-row">
                <div class="field-half">
                    <label class="field-label" for="slug">URL Slug <span style="font-weight:400">(auto-generated if blank)</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. embroidery-digitizing-tips">
                    <span class="field-hint">Will appear as /blog/your-slug</span>
                </div>
                <div class="field-half">
                    <label class="field-label" for="author_name">Author Name *</label>
                    <input type="text" id="author_name" name="author_name" value="{{ old('author_name', '1 Dollar Digitizing') }}" required>
                </div>
            </div>
            <div class="field-row">
                <div class="field-half">
                    <label class="field-label" for="category">Category</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="e.g. Embroidery Tips">
                </div>
                <div class="field-half">
                    <label class="field-label" for="tags">Tags <span style="font-weight:400">(comma-separated)</span></label>
                    <input type="text" id="tags" name="tags" value="{{ old('tags') }}" placeholder="e.g. digitizing, embroidery, DST">
                </div>
            </div>
            <div class="field-full">
                <label class="field-label" for="excerpt">Excerpt / Short Description * <span class="char-counter" id="excerpt-counter"></span></label>
                <textarea id="excerpt" name="excerpt" rows="3" maxlength="500" required placeholder="A 1–2 sentence summary that appears on the blog listing page and in search results.">{{ old('excerpt') }}</textarea>
                <span class="field-hint">Max 500 characters. This text may also show as the meta description if left blank below.</span>
            </div>
        </div>
    </div>

    {{-- HERO IMAGE --}}
    <div class="form-card">
        <div class="form-card-head"><h3>Hero Image</h3></div>
        <div class="form-card-body">
            <div class="field-full">
                <label class="field-label" for="hero_image">Hero Image * <span style="font-weight:400">(JPG, PNG, WebP — max 5 MB)</span></label>
                <input type="file" id="hero_image" name="hero_image" accept="image/jpeg,image/png,image/webp,image/gif" required>
                <div class="image-preview-wrap" id="hero-preview" style="display:none;">
                    <img id="hero-preview-img" src="" alt="Hero preview">
                </div>
            </div>
            <div class="field-full">
                <label class="field-label" for="hero_image_alt">Hero Image Alt Text * <span class="char-counter" id="alt-counter"></span></label>
                <input type="text" id="hero_image_alt" name="hero_image_alt" value="{{ old('hero_image_alt') }}" required maxlength="200"
                       placeholder="Describe the image for screen readers and Google Image Search">
                <span class="field-hint">Be specific: "Embroidery digitizing example — custom logo on polo shirt"</span>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="form-card">
        <div class="form-card-head"><h3>Post Content *</h3></div>
        <div class="form-card-body">
            <textarea id="content" name="content" rows="20">{{ old('content') }}</textarea>
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
                        <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft — not visible on site</option>
                        <option value="published" @selected(old('status') === 'published')>Published — live on site</option>
                    </select>
                </div>
                <div class="field-half">
                    <label class="field-label" for="published_at">Publish Date <span style="font-weight:400">(defaults to now when published)</span></label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}">
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
                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" required maxlength="70"
                       placeholder="50–60 characters ideal for Google — include your primary keyword">
                <span class="field-hint">Target: 50–60 characters. Currently: <span id="meta-title-len">0</span> chars.</span>
            </div>
            <div class="field-full">
                <label class="field-label" for="meta_description">
                    Meta Description * <span class="char-counter" id="meta-desc-counter"></span>
                </label>
                <textarea id="meta_description" name="meta_description" rows="3" required maxlength="200"
                          placeholder="140–160 characters. Summarise the post and include your target keyword.">{{ old('meta_description') }}</textarea>
                <span class="field-hint">Target: 140–160 characters. Currently: <span id="meta-desc-len">0</span> chars.</span>
            </div>
            <div class="field-full">
                <label class="field-label" for="og_image">OG / Social Share Image <span style="font-weight:400">(optional — uses hero image if blank, JPG/PNG, max 5 MB)</span></label>
                <input type="file" id="og_image" name="og_image" accept="image/jpeg,image/png,image/webp">
                <span class="field-hint">Recommended: 1200×630 px. Shown when the post is shared on Facebook, Twitter/X, LinkedIn.</span>
                <div class="image-preview-wrap" id="og-preview" style="display:none;">
                    <img id="og-preview-img" src="" alt="OG image preview">
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="form-card">
        <div class="form-card-body">
            <div class="form-actions">
                <button type="submit">Save Post</button>
                <a class="button secondary" href="{{ url('/v/blogs') }}">Cancel</a>
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
    images_reuse_filename: false,
    setup: function (editor) {
        editor.on('init', function () {
            editor.getContainer().style.borderRadius = '14px';
            editor.getContainer().style.border = '1px solid rgba(24,34,45,0.26)';
        });
    },
    content_style: 'body { font-family: "Segoe UI", Roboto, sans-serif; font-size: 16px; line-height: 1.7; color: #182a3e; max-width: none; padding: 20px; }',
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ url('/v/blog-image-upload') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}');
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

// Character counters
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

// Image preview
function initImagePreview(inputId, previewWrapId, previewImgId) {
    var input = document.getElementById(inputId);
    var wrap = document.getElementById(previewWrapId);
    var img = document.getElementById(previewImgId);
    if (!input || !wrap || !img) return;
    input.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                wrap.style.display = '';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}
initImagePreview('hero_image', 'hero-preview', 'hero-preview-img');
initImagePreview('og_image', 'og-preview', 'og-preview-img');

// Auto-generate slug from title
document.getElementById('title').addEventListener('blur', function () {
    var slugInput = document.getElementById('slug');
    if (slugInput.value.trim() !== '') return;
    var slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/[\s]+/g, '-')
        .replace(/-+/g, '-');
    slugInput.value = slug;
});
</script>
@endsection
