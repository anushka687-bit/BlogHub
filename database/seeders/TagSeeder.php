<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'AI', 'Travel', 'Food',
            'Fitness', 'Productivity', 'Startup', 'Design', 'Tutorial', 'News',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag]);
        }
    }
}