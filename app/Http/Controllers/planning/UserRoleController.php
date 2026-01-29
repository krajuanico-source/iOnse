<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    // Show form to edit a user's role
    public function edit(User $user)
    {
        $roles = Role::all(); // get all roles
        return view('users.edit-role', compact('user', 'roles'));
    }

    // Assign role to user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Assign (replace existing roles)
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Role assigned successfully!');
    }
}
