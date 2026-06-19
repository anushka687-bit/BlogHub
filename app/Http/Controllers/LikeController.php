<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Like;
use App\Notifications\BlogLiked;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Blog $blog): JsonResponse
    {
        $userId = Auth::id();

        $existingLike = Like::where('user_id', $userId)->where('blog_id', $blog->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            Like::create(['user_id' => $userId, 'blog_id' => $blog->id]);
            $liked = true;

            if ($blog->user_id !== $userId) {
                $blog->user->notify(new BlogLiked(Auth::user(), $blog));
            }
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $blog->likes()->count(),
        ]);
    }
}