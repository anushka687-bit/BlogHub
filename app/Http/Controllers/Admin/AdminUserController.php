<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount('blogs')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function toggleBlock(User $user)
    {
        if ($user->is_admin) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot block an admin user.');
        }

        $user->update(['is_blocked' => !$user->is_blocked]);

        $status = $user->is_blocked ? 'blocked' : 'unblocked';

        return redirect()->route('admin.users.index')->with('success', "User {$status} successfully.");
    }
}