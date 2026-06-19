<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Blog;
use App\Models\Comment;
use App\Notifications\CommentReceived;
use App\Notifications\CommentReplied;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Blog $blog): JsonResponse
    {
        $validated = $request->validated();

        $comment = Comment::create([
            'blog_id' => $blog->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $comment->load('user');

        if ($comment->parent_id) {
            $parent = Comment::find($comment->parent_id);
            if ($parent && $parent->user_id !== Auth::id()) {
                $parent->user->notify(new CommentReplied(Auth::user(), $blog));
            }
        } elseif ($blog->user_id !== Auth::id()) {
            $blog->user->notify(new CommentReceived(Auth::user(), $blog));
        }

        return response()->json([
            'success' => true,
            'html' => view('blogs.partials.comment', ['comment' => $comment, 'blog' => $blog])->render(),
            'comments_count' => $blog->allComments()->count(),
            'is_reply' => !is_null($comment->parent_id),
            'parent_id' => $comment->parent_id,
        ]);
    }

    public function update(StoreCommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $comment->update(['comment' => $request->validated()['comment']]);

        return response()->json([
            'success' => true,
            'comment' => $comment->comment,
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $blog = $comment->blog;
        $comment->delete();

        return response()->json([
            'success' => true,
            'comments_count' => $blog->allComments()->count(),
        ]);
    }
}