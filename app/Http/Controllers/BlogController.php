<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = Blog::query()
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '');
            })
            ->when(
                $request->filled('category'),
                fn ($q) => $q->where('category', $request->input('category'))
            )
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Blog::query()
            ->where('status', 'published')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '');
            })
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('public.blog.index', [
            'posts'      => $posts,
            'categories' => $categories,
        ]);
    }

    public function show(string $slug)
    {
        $post = Blog::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '');
            })
            ->firstOrFail();

        $related = Blog::query()
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '');
            })
            ->when(
                $post->category,
                fn ($q) => $q->where('category', $post->category)
            )
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('public.blog.show', [
            'post'    => $post,
            'related' => $related,
        ]);
    }
}
