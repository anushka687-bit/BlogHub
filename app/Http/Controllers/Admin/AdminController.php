<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBlogs = Blog::count();
        $totalLikes = Like::count();
        $totalComments = Comment::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $draftBlogs = Blog::where('status', 'draft')->count();

        $newestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBlogs',
            'totalLikes',
            'totalComments',
            'publishedBlogs',
            'draftBlogs',
            'newestUsers'
        ));
    }
}