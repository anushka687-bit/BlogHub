<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'cover_image',
        'short_description',
        'content',
        'category',
        'status',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function comments()
{
    return $this->hasMany(Comment::class)->latest();
}

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag');
    }

    public function isLikedBy($userId): bool
    {
        if (!$userId) {
            return false;
        }
        return $this->likes->contains('user_id', $userId);
    }

    public function coverImageUrl(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : 'https://placehold.co/800x450/0d6efd/ffffff?text=BlogHub';
    }

    public function readingTime(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($words / 200));
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeTrending($query)
{
    return $query
        ->withCount(['likes', 'allComments as comments_count'])
        ->orderByRaw('(views + likes_count * 3 + comments_count * 2) DESC');
}

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count;
            $count++;
        }

        return $slug;
    }
}