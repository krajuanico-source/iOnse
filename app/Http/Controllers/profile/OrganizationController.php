<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
        public function index(Request $request)
        {
            if ($request->ajax()) {
                $data = \App\Models\Organization::where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->latest()
                    ->get();
                return datatables()->of($data)->make(true);
            }

            // 👇 Add this line to pass organizations to the Blade
            $organizations = \App\Models\Organization::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->latest()
                ->get();

            return view('content.profile.organization', compact('organizations'));
        }


    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_name' => 'required|string|max:255',
        ]);

        Organization::create($data + ['user_id' => Auth::id()]);

        return response()->json(['message' => 'Organization added successfully']);
    }

    public function show($id)
    {
        $organization = Organization::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($organization);
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'organization_name' => 'required|string|max:255',
        ]);

        $organization->update($data);

        return response()->json(['message' => 'Organization updated successfully']);
    }

    public function destroy($id)
    {
        Organization::where('user_id', Auth::id())->findOrFail($id)->delete();
        return response()->json(['message' => 'Organization deleted successfully']);
    }
}
