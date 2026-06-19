<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentReplied extends Notification
{
    use Queueable;

    public function __construct(public User $replier, public Blog $blog)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'reply',
            'message' => $this->replier->name . ' replied to your comment on "' . $this->blog->title . '"',
            'blog_id' => $this->blog->id,
            'blog_slug' => $this->blog->slug,
            'user_id' => $this->replier->id,
        ];
    }
}