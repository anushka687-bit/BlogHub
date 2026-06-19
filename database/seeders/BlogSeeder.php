<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(8)->create();
        $tags = Tag::all();

        Blog::factory(25)
            ->recycle($users)
            ->create()
            ->each(function (Blog $blog) use ($users, $tags) {
                $blog->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Pick a random subset of users to like this blog (no duplicates)
                $likers = $users->random(min(rand(0, 6), $users->count()));

                foreach ($likers as $liker) {
                    Like::firstOrCreate([
                        'user_id' => $liker->id,
                        'blog_id' => $blog->id,
                    ]);
                }

                Comment::factory()->count(rand(0, 4))
                    ->recycle($users)
                    ->create(['blog_id' => $blog->id]);
            });
    }
}