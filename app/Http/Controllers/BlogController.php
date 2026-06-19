<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->with(['user', 'tags'])->withCount(['likes', 'comments']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        $blogs = $query->latest()->paginate(9)->withQueryString();

        $categories = Blog::published()->select('category')->distinct()->pluck('category');

        return view('blogs.index', compact('blogs', 'categories'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');

        $blogs = Blog::published()
            ->with(['user', 'tags'])
            ->withCount(['likes', 'comments'])
            ->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->take(12)
            ->get();

        return response()->json([
            'html' => view('blogs.partials.cards', compact('blogs'))->render(),
            'count' => $blogs->count(),
        ]);
    }

    public function byCategory(Request $request)
    {
        $category = $request->get('category', '');

        $query = Blog::published()->with(['user', 'tags'])->withCount(['likes', 'comments']);

        if ($category !== '' && $category !== 'all') {
            $query->where('category', $category);
        }

        $blogs = $query->latest()->take(12)->get();

        return response()->json([
            'html' => view('blogs.partials.cards', compact('blogs'))->render(),
            'count' => $blogs->count(),
        ]);
    }

    public function create()
    {
        $categories = ['Technology', 'Lifestyle', 'Travel', 'Food', 'Business', 'Health', 'Education'];
        $tags = Tag::orderBy('name')->get();

        return view('blogs.create', compact('categories', 'tags'));
    }

    public function store(StoreBlogRequest $request)
    {
        $validated = $request->validated();

        $coverPath = $request->file('cover_image')->store('blogs', 'public');

        $blog = Blog::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => Blog::generateUniqueSlug($validated['title']),
            'cover_image' => $coverPath,
            'short_description' => $validated['short_description'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'status' => $validated['status'],
        ]);

        $this->syncTags($blog, $validated['tags'] ?? '');

        return redirect()->route('my-blogs')->with('success', 'Blog created successfully.');
    }

    public function show(Blog $blog)
    {
        if ($blog->status !== 'published' && (!Auth::check() || (Auth::id() !== $blog->user_id && !Auth::user()->is_admin))) {
            abort(404);
        }

        $blog->increment('views');
        $blog->load(['user', 'tags', 'comments.user', 'comments.replies.user', 'likes']);

        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->withCount(['likes', 'comments'])
            ->take(3)
            ->get();

        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);

        $categories = ['Technology', 'Lifestyle', 'Travel', 'Food', 'Business', 'Health', 'Education'];
        $tags = Tag::orderBy('name')->get();
        $selectedTags = $blog->tags->pluck('name')->implode(', ');

        return view('blogs.edit', compact('blog', 'categories', 'tags', 'selectedTags'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $this->authorize('update', $blog);

        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'status' => $validated['status'],
        ];

        if ($validated['title'] !== $blog->title) {
            $data['slug'] = Blog::generateUniqueSlug($validated['title']);
        }

        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }

        $blog->update($data);

        $this->syncTags($blog, $validated['tags'] ?? '');

        return redirect()->route('my-blogs')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);

        if ($blog->cover_image) {
            Storage::disk('public')->delete($blog->cover_image);
        }

        $blog->delete();

        return redirect()->route('my-blogs')->with('success', 'Blog deleted successfully.');
    }

    public function publish(Blog $blog)
    {
        $this->authorize('update', $blog);

        $blog->update(['status' => 'published']);

        return redirect()->route('my-blogs')->with('success', 'Blog published successfully.');
    }

    public function myBlogs()
    {
        $blogs = Auth::user()->blogs()
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('blogs.my-blogs', compact('blogs'));
    }

    private function syncTags(Blog $blog, string $tagsString): void
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        $tagIds = [];

        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(['name' => $name]);
            $tagIds[] = $tag->id;
        }

        $blog->tags()->sync($tagIds);
    }
}