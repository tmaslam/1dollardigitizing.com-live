<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Support\AdminNavigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::query()
            ->when(
                $request->filled('search'),
                fn ($q) => $q->where('title', 'like', '%'.$request->input('search').'%')
            )
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where('status', $request->input('status'))
            )
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '');
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog.index', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'blogs'     => $blogs,
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.blog.create', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|max:220|unique:blogs,slug',
            'excerpt'          => 'required|string|max:500',
            'content'          => 'required|string',
            'hero_image'       => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'hero_image_alt'   => 'required|string|max:200',
            'author_name'      => 'required|string|max:150',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string|max:500',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'required|string|max:70',
            'meta_description' => 'required|string|max:200',
            'og_image'         => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at'     => 'nullable|date',
        ]);

        $slug = $validated['slug'] ?: Blog::generateSlug($validated['title']);

        $heroPath = $request->file('hero_image')->store('blog-images', 'public_storage');

        $ogPath = null;
        if ($request->hasFile('og_image')) {
            $ogPath = $request->file('og_image')->store('blog-images', 'public_storage');
        }

        $publishedAt = $validated['published_at'] ?? null;
        if ($validated['status'] === 'published' && ! $publishedAt) {
            $publishedAt = now();
        }

        Blog::create([
            'title'            => $validated['title'],
            'slug'             => $slug,
            'excerpt'          => $validated['excerpt'],
            'content'          => purify($validated['content']),
            'hero_image'       => $heroPath,
            'hero_image_alt'   => $validated['hero_image_alt'],
            'author_name'      => $validated['author_name'],
            'category'         => $validated['category'] ?? null,
            'tags'             => $validated['tags'] ?? null,
            'status'           => $validated['status'],
            'meta_title'       => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'og_image'         => $ogPath,
            'published_at'     => $publishedAt,
            'date'             => now()->format('Y-m-d'),
            'decription'       => substr($validated['excerpt'], 0, 255),
        ]);

        return redirect()->to(url('/v/blogs'))
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(Request $request, Blog $blog)
    {
        return view('admin.blog.edit', [
            'adminUser' => $request->attributes->get('adminUser'),
            'navCounts' => AdminNavigation::counts(),
            'blog'      => $blog,
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|max:220|unique:blogs,slug,'.$blog->id,
            'excerpt'          => 'required|string|max:500',
            'content'          => 'required|string',
            'hero_image'       => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'hero_image_alt'   => 'required|string|max:200',
            'author_name'      => 'required|string|max:150',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string|max:500',
            'status'           => 'required|in:draft,published',
            'meta_title'       => 'required|string|max:70',
            'meta_description' => 'required|string|max:200',
            'og_image'         => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at'     => 'nullable|date',
        ]);

        $slug = $validated['slug'] ?: Blog::generateSlug($validated['title'], $blog->id);

        $heroPath = $blog->hero_image;
        if ($request->hasFile('hero_image')) {
            if ($blog->hero_image) {
                Storage::disk('public_storage')->delete($blog->hero_image);
            }
            $heroPath = $request->file('hero_image')->store('blog-images', 'public_storage');
        }

        $ogPath = $blog->og_image;
        if ($request->hasFile('og_image')) {
            if ($blog->og_image) {
                Storage::disk('public_storage')->delete($blog->og_image);
            }
            $ogPath = $request->file('og_image')->store('blog-images', 'public_storage');
        }

        $publishedAt = $validated['published_at'] ?? $blog->published_at;
        if ($validated['status'] === 'published' && ! $publishedAt) {
            $publishedAt = now();
        }

        $blog->update([
            'title'            => $validated['title'],
            'slug'             => $slug,
            'excerpt'          => $validated['excerpt'],
            'content'          => purify($validated['content']),
            'hero_image'       => $heroPath,
            'hero_image_alt'   => $validated['hero_image_alt'],
            'author_name'      => $validated['author_name'],
            'category'         => $validated['category'] ?? null,
            'tags'             => $validated['tags'] ?? null,
            'status'           => $validated['status'],
            'meta_title'       => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'og_image'         => $ogPath,
            'published_at'     => $publishedAt,
            'decription'       => substr($validated['excerpt'], 0, 255),
        ]);

        return redirect()->to(url('/v/blogs'))
            ->with('success', 'Blog post updated successfully.');
    }

    public function togglePublish(Request $request, Blog $blog)
    {
        if ($blog->status === 'published') {
            $blog->update(['status' => 'draft', 'published_at' => null]);

            return redirect()->to(url('/v/blogs'))
                ->with('success', '"'.$blog->title.'" has been unpublished and reverted to draft.');
        }

        $validated = $request->validate([
            'publish_at' => 'nullable|date',
        ]);

        $publishAt = $validated['publish_at']
            ? \Carbon\Carbon::parse($validated['publish_at'])
            : now();

        $blog->update(['status' => 'published', 'published_at' => $publishAt]);

        $message = $publishAt->isFuture()
            ? '"'.$blog->title.'" scheduled to publish on '.$publishAt->format('M j, Y \a\t g:i A').'.'
            : '"'.$blog->title.'" is now live on the website.';

        return redirect()->to(url('/v/blogs'))->with('success', $message);
    }

    public function destroy(Blog $blog)
    {
        $blog->update(['end_date' => now()->format('Y-m-d H:i:s')]);

        return redirect()->to(url('/v/blogs'))
            ->with('success', 'Blog post deleted.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $path = $request->file('file')->store('blog-images', 'public_storage');

        return response()->json([
            'location' => Storage::disk('public_storage')->url($path),
        ]);
    }
}
