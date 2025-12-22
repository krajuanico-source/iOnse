<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;

class UserManagementController extends Controller
{
   public function index()
{
    // Get all divisions
    $divisions = Division::all();
    return view('content.planning.user-management', compact('divisions'));
}

    public function activate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = 1;
        $user->deactivation_reason = null; // clear previous reason
        $user->save();

        return response()->json(['success' => 'User has been activated successfully.']);
    }


// DataTable list
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['division', 'section'])->latest()->get();

            return datatables()->of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($user) {
                    $fullName = trim(
                        $user->first_name . ' ' .
                        ($user->middle_name ? $user->middle_name . ' ' : '') .
                        $user->last_name .
                        ($user->extension_name ? ' ' . $user->extension_name : '')
                    );
                    return strtoupper($fullName);
                })
                ->addColumn('division', fn($user) => $user->division?->name ?? '-')
                ->addColumn('section', fn($user) => $user->section?->name ?? '-')
                ->addColumn('is_active', fn($user) => $user->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', function ($user) {
                    $btn  = '<a href="javascript:void(0)" data-id="'.$user->id.'" class="edit btn btn-primary btn-sm">Edit</a> ';

                    if ($user->is_active) {
                        $btn .= '<a href="javascript:void(0)" data-id="'.$user->id.'" class="toggle-status btn btn-danger btn-sm">Deactivate</a>';
                    } else {
                        $btn .= '<a href="javascript:void(0)" data-id="'.$user->id.'" class="toggle-status btn btn-success btn-sm">Activate</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // AJAX: Get sections for HRMDD division
    public function getSections($divisionId)
    {
        $division = Division::with('sections')->find($divisionId);


        $sections = $division->sections->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
        ]);

        return response()->json($sections);
    }

    // DataTable list

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
            'division_id' => 'required',
            'section_id' => 'required',
        ]);

        User::create([
            'first_name' => strtoupper($request->first_name),
            'middle_name' => strtoupper($request->middle_name),
            'last_name' => strtoupper($request->last_name),
            'extension_name' => strtoupper($request->extension_name),
            'division_id' => $request->division_id,
            'section_id' => $request->section_id,
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
            'division_id' => 'required',
            'section_id' => 'required',
        ]);

        $user->update([
            'division_id' => $request->division_id,
            'section_id' => $request->section_id,
            'is_active' => $request->has('is_active'),
        ]);

        if (!empty($request->password)) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return response()->json(['success' => 'User updated successfully!']);
    }

    public function deactivate(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->is_active = 0; // deactivate
        $user->deactivation_reason = $request->reason; // save remarks
        $user->save();

        return response()->json(['success' => 'User has been deactivated successfully.']);
    }

}
