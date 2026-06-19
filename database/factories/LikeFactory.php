<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'blog_id' => Blog::factory(),
        ];
    }
}