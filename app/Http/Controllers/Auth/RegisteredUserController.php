<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone_number' => ['nullable', 'string', 'digits:10'],
                'age' => ['nullable', 'numeric'],
                'gender' => ['nullable', 'string', 'in:male,female,other'],
                'profile_image' => ['nullable', 'image', 'max:2048'],
                'bio' => ['nullable', 'string'],
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            if (isset($errors['password'])) {
                foreach ($errors['password'] as $error) {
                    if (str_contains(strtolower($error), 'confirm') || str_contains(strtolower($error), 'match')) {
                        $errors['password_confirmation'] = ['The password confirmation does not match.'];
                    }
                }
            }
            throw ValidationException::withMessages($errors);
        }

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'age' => $request->age,
            'gender' => $request->gender,
            'profile_image' => $profileImagePath,
            'bio' => $request->bio,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
