<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@bloghub.test',
            'is_admin' => true,
        ]);

        $this->call([
            TagSeeder::class,
            BlogSeeder::class,
        ]);
    }
}