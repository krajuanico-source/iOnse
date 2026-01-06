<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    // Show the User Permissions page
    public function index()
    {
        $users = User::all();

        // Get all permissions and group them by module
        $allPermissions = Permission::all()->pluck('name')->toArray();

        $modules = [];
        foreach ($allPermissions as $perm) {
            // permission format: module.action
            [$moduleSlug, $action] = explode('.', $perm);
            $modules[$moduleSlug]['slug'] = $moduleSlug;
            $modules[$moduleSlug]['actions'][$action] = $perm;
        }

        $actions = ['view', 'create', 'edit']; // fixed actions

        return view('content.planning.user-permission', compact('users', 'modules', 'actions'));
    }

    // Get current permissions for a user
    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->getPermissionNames()->toArray());
    }

    // Get roles for a user
    public function getUserRoles($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->getRoleNames()->toArray());
    }

    // Update user permissions
    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $user = User::findOrFail($request->user_id);
        $permissions = $request->permissions ?? [];

        // Create missing permissions if they don't exist
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }

        // Sync permissions
        $user->syncPermissions($permissions);

        return response()->json([
            'success' => 'Permissions updated successfully!',
            'synced_permissions' => $permissions,
        ]);
    }
}
