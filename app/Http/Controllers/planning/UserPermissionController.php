<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::all();
        $modules = Module::all();
        $actions = ['view', 'create', 'edit', 'delete', 'approve'];

        return view('content.planning.user-permission', compact('users', 'modules', 'actions'));
    }

    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->getPermissionNames()); // ✅ Spatie returns permission names
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $permissionName = $request->permission_name;

        if (!Permission::where('name', $permissionName)->exists()) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        if ($request->granted) {
            $user->givePermissionTo($permissionName);
        } else {
            $user->revokePermissionTo($permissionName);
        }

        return response()->json(['success' => 'Permission updated']);
    }
}
