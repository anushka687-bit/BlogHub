<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        $categories = ['Technology', 'Lifestyle', 'Travel', 'Food', 'Business', 'Health', 'Education'];
        $title = fake()->sentence(6);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Blog::generateUniqueSlug($title),
            'cover_image' => null,
            'short_description' => fake()->paragraph(2),
            'content' => '<p>' . implode('</p><p>', fake()->paragraphs(6)) . '</p>',
            'category' => fake()->randomElement($categories),
            'status' => fake()->randomElement(['published', 'published', 'published', 'draft']),
            'views' => fake()->numberBetween(0, 5000),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'published']);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'draft']);
    }
}