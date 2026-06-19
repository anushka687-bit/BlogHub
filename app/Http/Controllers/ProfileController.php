<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();

        $totalBlogs = $user->blogs()->count();
        $totalLikes = \App\Models\Like::whereIn('blog_id', $user->blogs()->pluck('id'))->count();
        $totalComments = \App\Models\Comment::whereIn('blog_id', $user->blogs()->pluck('id'))->count();

        return view('profile.edit', compact('user', 'totalBlogs', 'totalLikes', 'totalComments'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? null;

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('avatars', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}