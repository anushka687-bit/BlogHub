<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalBlogs = $user->blogs()->count();
        $publishedBlogs = $user->blogs()->where('status', 'published')->count();
        $draftBlogs = $user->blogs()->where('status', 'draft')->count();

        $totalLikes = \App\Models\Like::whereIn('blog_id', $user->blogs()->pluck('id'))->count();
        $totalComments = \App\Models\Comment::whereIn('blog_id', $user->blogs()->pluck('id'))->count();

        $recentBlogs = $user->blogs()
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBlogs',
            'publishedBlogs',
            'draftBlogs',
            'totalLikes',
            'totalComments',
            'recentBlogs'
        ));
    }
}