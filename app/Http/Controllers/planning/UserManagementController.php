<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('content.planning.user-management');
    }

    public function list(Request $request)
{
    if ($request->ajax()) {
        // Only fetch users with HR-PLANNING role
        $users = User::where('role', 'HR-PLANNING')->latest()->get();

        return datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('name', function($user){
                $fullName = trim(
                    $user->first_name.' '.($user->middle_name?$user->middle_name.' ':'').
                    $user->last_name.($user->extension_name?' '.$user->extension_name:'')
                );
                return strtoupper($fullName);
            })
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm">Edit</a>';
                $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->editColumn('role', fn($row)=>$row->role ?? 'USER')
            ->editColumn('is_active', fn($row)=>$row->is_active?'Active':'Inactive')
            ->rawColumns(['action'])
            ->make(true);
    }
}


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'first_name' => strtoupper($request->first_name),
            'middle_name' => strtoupper($request->middle_name),
            'last_name' => strtoupper($request->last_name),
            'extension_name' => strtoupper($request->extension_name),
            'role' => strtoupper($request->role),
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json(['success' => 'User created successfully!']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'role' => 'required',
        ]);

        $user->update([
            'first_name' => strtoupper($request->first_name),
            'middle_name' => strtoupper($request->middle_name),
            'last_name' => strtoupper($request->last_name),
            'extension_name' => strtoupper($request->extension_name),
            'role' => strtoupper($request->role),
            'is_active' => $request->has('is_active'),
        ]);

        if (!empty($request->password)) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return response()->json(['success' => 'User updated successfully!']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => 'User deleted successfully!']);
    }
}
