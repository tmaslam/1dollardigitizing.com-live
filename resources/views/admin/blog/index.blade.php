@extends('layouts.admin')

@section('title', 'Blog Posts | Admin')
@section('page_heading', 'Blog Posts')
@section('page_subheading', 'Create, edit, and manage all blog posts published on the site.')

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="section-head">
                <div>
                    <h3>All Posts</h3>
                    <p class="section-copy">{{ $blogs->total() }} post{{ $blogs->total() !== 1 ? 's' : '' }} total</p>
                </div>
                <a class="button" href="{{ url('/v/blogs/create') }}">+ New Post</a>
            </div>

            @if (session('success'))
                <div class="alert success" style="margin-bottom:16px;">{{ session('success') }}</div>
            @endif

            <form method="get" action="{{ url('/v/blogs') }}" class="filter-grid" style="margin-bottom:18px;">
                <label>
                    Search
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title…">
                </label>
                <label>
                    Status
                    <select name="status">
                        <option value="">All</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    </select>
                </label>
                <div>
                    <button type="submit">Filter</button>
                    @if(request()->hasAny(['search','status']))
                        <a class="button secondary" href="{{ url('/v/blogs') }}">Clear</a>
                    @endif
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th class="action-col">Actions</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Published</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td class="action-col">
                                    <div class="action-row">
                                        <a class="button secondary" href="{{ url('/v/blogs/'.$blog->id.'/edit') }}">Edit</a>

                                        @if ($blog->status === 'published')
                                            {{-- Unpublish: direct POST, no modal needed --}}
                                            <form method="post" action="{{ url('/v/blogs/'.$blog->id.'/toggle-publish') }}"
                                                  onsubmit="return confirm('Unpublish «{{ addslashes($blog->title) }}» and set it back to draft?');">
                                                @csrf
                                                <button type="submit" class="btn-unpublish">Unpublish</button>
                                            </form>
                                        @else
                                            {{-- Publish: open schedule modal --}}
                                            <button type="button" class="btn-publish"
                                                    onclick="openPublishModal({{ $blog->id }}, '{{ addslashes($blog->title) }}')">
                                                Publish
                                            </button>
                                        @endif

                                        @if ($blog->slug)
                                            <a class="button secondary" href="{{ url('/blog/'.$blog->slug) }}" target="_blank">View</a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $blog->title }}</strong>
                                    @if ($blog->slug)
                                        <br><span class="muted" style="font-size:0.8rem;">/blog/{{ $blog->slug }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $isScheduled = $blog->status === 'published' && $blog->published_at && $blog->published_at->isFuture();
                                    @endphp
                                    @if ($isScheduled)
                                        <span class="badge" style="background:#dbeafe;color:#1e40af;">Scheduled</span>
                                    @elseif ($blog->status === 'published')
                                        <span class="badge" style="background:#d1f5e0;color:#155724;">Published</span>
                                    @else
                                        <span class="badge" style="background:#fff3cd;color:#856404;">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $blog->category ?: '—' }}</td>
                                <td>{{ $blog->author_name ?: '—' }}</td>
                                <td>
                                    @if ($blog->published_at)
                                        {{ $blog->published_at->format('M j, Y') }}
                                        @if ($blog->published_at->isFuture())
                                            <br><span class="muted" style="font-size:0.75rem;">{{ $blog->published_at->format('g:i A') }} (scheduled)</span>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">No blog posts found. <a href="{{ url('/v/blogs/create') }}">Create your first post →</a></div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($blogs->hasPages())
                <div style="margin-top:18px;">{{ $blogs->links() }}</div>
            @endif
        </div>
    </section>

    {{-- Publish / Schedule modal --}}
    <div id="publishModal" class="pub-modal-backdrop" style="display:none;" onclick="if(event.target===this)closePublishModal()">
        <div class="pub-modal">
            <div class="pub-modal-header">
                <h3 class="pub-modal-title">Publish Article</h3>
                <button type="button" class="pub-modal-close" onclick="closePublishModal()" aria-label="Close">&times;</button>
            </div>

            <p class="pub-modal-sub" id="pubModalSub">Choose when to publish this article.</p>

            <form method="post" id="publishForm" action="">
                @csrf

                <div class="pub-modal-options">
                    <label class="pub-option pub-option--now">
                        <input type="radio" name="publish_when" value="now" checked onchange="toggleSchedule(this)">
                        <span class="pub-option-body">
                            <strong>Publish Now</strong>
                            <span>Article goes live immediately on the website.</span>
                        </span>
                    </label>

                    <label class="pub-option pub-option--later">
                        <input type="radio" name="publish_when" value="later" onchange="toggleSchedule(this)">
                        <span class="pub-option-body">
                            <strong>Schedule for Later</strong>
                            <span>Pick a future date and time — article will appear automatically.</span>
                        </span>
                    </label>
                </div>

                <div id="scheduleFields" style="display:none; margin-top:16px;">
                    <label class="pub-date-label">
                        Publish Date &amp; Time
                        <input type="datetime-local" name="publish_at" id="publishAtInput" class="pub-date-input">
                    </label>
                    <p class="pub-date-hint">All times are server local time ({{ now()->format('T') }}).</p>
                </div>

                <div class="pub-modal-actions">
                    <button type="button" class="button secondary" onclick="closePublishModal()">Cancel</button>
                    <button type="submit" class="button pub-submit-btn" id="pubSubmitBtn">Publish Now</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .btn-publish {
            padding: 6px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.82rem;
            font-weight: 600;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
            transition: opacity .15s;
        }
        .btn-publish:hover { opacity: .88; }

        .btn-unpublish {
            padding: 6px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.82rem;
            font-weight: 600;
            background: linear-gradient(135deg, #d97706, #b45309);
            color: #fff;
            transition: opacity .15s;
        }
        .btn-unpublish:hover { opacity: .88; }

        /* Modal backdrop */
        .pub-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(10, 28, 50, 0.55);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .pub-modal {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(10, 28, 50, 0.22);
            width: 100%;
            max-width: 480px;
            padding: 28px 28px 24px;
        }

        .pub-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .pub-modal-title {
            margin: 0;
            font-size: 1.15rem;
            color: #182a3e;
        }

        .pub-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: #7a8fa6;
            cursor: pointer;
            padding: 0 4px;
        }
        .pub-modal-close:hover { color: #182a3e; }

        .pub-modal-sub {
            font-size: 0.85rem;
            color: #526071;
            margin: 0 0 18px;
        }

        .pub-modal-options {
            display: grid;
            gap: 10px;
        }

        .pub-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1.5px solid rgba(22,159,230,0.15);
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }
        .pub-option:has(input:checked) {
            border-color: #169fe6;
            background: rgba(22,159,230,0.05);
        }
        .pub-option input[type="radio"] { margin-top: 3px; flex-shrink: 0; }

        .pub-option-body { display: flex; flex-direction: column; gap: 2px; }
        .pub-option-body strong { font-size: 0.9rem; color: #182a3e; }
        .pub-option-body span { font-size: 0.80rem; color: #7a8fa6; }

        .pub-date-label {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #182a3e;
        }

        .pub-date-input {
            padding: 9px 12px;
            border-radius: 8px;
            border: 1.5px solid rgba(22,159,230,0.25);
            font-size: 0.88rem;
            color: #182a3e;
            width: 100%;
            box-sizing: border-box;
        }
        .pub-date-input:focus { outline: none; border-color: #169fe6; }

        .pub-date-hint {
            font-size: 0.75rem;
            color: #9ab0c4;
            margin: 6px 0 0;
        }

        .pub-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 22px;
        }

        .pub-submit-btn { background: linear-gradient(135deg, #169fe6, #0d6ea3); }
    </style>

    <script>
        var blogsBaseUrl = '{{ url("/v/blogs") }}';

        function openPublishModal(id, title) {
            document.getElementById('publishForm').action = blogsBaseUrl + '/' + id + '/toggle-publish';
            document.getElementById('pubModalSub').textContent = 'Choose when to publish "' + title + '".';

            // Reset to "now" state
            var nowRadio = document.querySelector('input[name="publish_when"][value="now"]');
            if (nowRadio) { nowRadio.checked = true; }
            document.getElementById('scheduleFields').style.display = 'none';
            document.getElementById('pubSubmitBtn').textContent = 'Publish Now';
            document.getElementById('publishAtInput').value = '';

            document.getElementById('publishModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closePublishModal() {
            document.getElementById('publishModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function toggleSchedule(radio) {
            var isLater = radio.value === 'later';
            document.getElementById('scheduleFields').style.display = isLater ? 'block' : 'none';
            document.getElementById('pubSubmitBtn').textContent = isLater ? 'Schedule' : 'Publish Now';

            if (isLater && !document.getElementById('publishAtInput').value) {
                // Pre-fill with tomorrow at 9 AM
                var d = new Date();
                d.setDate(d.getDate() + 1);
                d.setHours(9, 0, 0, 0);
                var pad = n => String(n).padStart(2, '0');
                document.getElementById('publishAtInput').value =
                    d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) +
                    'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
            }
        }

        // Esc key closes modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePublishModal();
        });
    </script>
@endsection
