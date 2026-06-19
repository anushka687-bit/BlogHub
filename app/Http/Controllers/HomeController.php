<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestBlogs = Blog::published()
            ->with(['user', 'tags'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(6)
            ->get();

        $trendingBlogs = Blog::published()
    ->with(['user', 'tags'])
    ->withCount(['likes', 'allComments as comments_count'])
    ->orderByRaw('(views + likes_count * 3 + comments_count * 2) DESC')
    ->take(4)
    ->get();

        $featuredAuthors = User::has('blogs')
            ->withCount('blogs')
            ->orderByDesc('blogs_count')
            ->take(4)
            ->get();

        $categories = Blog::published()
            ->select('category')
            ->groupBy('category')
            ->selectRaw('category, count(*) as total')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        return view('home', compact('latestBlogs', 'trendingBlogs', 'featuredAuthors', 'categories'));
    }
}