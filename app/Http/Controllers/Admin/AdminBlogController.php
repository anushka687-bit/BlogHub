<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function destroy(Blog $blog)
    {
        if ($blog->cover_image) {
            Storage::disk('public')->delete($blog->cover_image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }

    public function restore($id)
    {
        return redirect()->route('admin.blogs.index')->with('info', 'Restore is only available for soft-deleted blogs.');
    }
}