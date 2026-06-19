<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BlogLiked extends Notification
{
    use Queueable;

    public function __construct(public User $liker, public Blog $blog)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'like',
            'message' => $this->liker->name . ' liked your blog "' . $this->blog->title . '"',
            'blog_id' => $this->blog->id,
            'blog_slug' => $this->blog->slug,
            'user_id' => $this->liker->id,
        ];
    }
}