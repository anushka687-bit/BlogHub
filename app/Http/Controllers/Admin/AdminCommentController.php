<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class AdminCommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'blog'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')->with('success', 'Comment deleted successfully.');
    }
}