<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_users_can_register_with_optional_fields(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $avatar = \Illuminate\Http\UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '1234567890',
            'age' => '30',
            'gender' => 'male',
            'profile_image' => $avatar,
            'bio' => 'This is my personal bio.',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = \App\Models\User::where('email', 'johndoe@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('1234567890', $user->phone_number);
        $this->assertEquals(30, $user->age);
        $this->assertEquals('male', $user->gender);
        $this->assertEquals('This is my personal bio.', $user->bio);
        $this->assertNotNull($user->profile_image);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($user->profile_image);
    }

    public function test_registration_fails_with_invalid_optional_fields(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '12345', // Must be 10 digits
            'age' => 'not-a-number', // Must be numeric
            'gender' => 'invalid-gender', // Must be male, female, or other
        ]);

        $response->assertSessionHasErrors(['phone_number', 'age', 'gender']);
        $this->assertGuest();
    }

    public function test_registration_fails_when_passwords_do_not_match_and_displays_on_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'mismatch-pwd',
        ]);

        $response->assertSessionHasErrors(['password', 'password_confirmation']);
        $this->assertGuest();
    }
}
