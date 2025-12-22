<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

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
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'permission_name' => 'required|string|exists:permissions,name',
        'granted' => 'required|boolean',
    ]);

    $user = User::findOrFail($request->user_id);

    // Clear permission cache
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    if ($request->granted) {
        $user->givePermissionTo($request->permission_name);
    } else {
        $user->revokePermissionTo($request->permission_name);
    }

    return response()->json(['success' => 'Permission updated']);
}
}
