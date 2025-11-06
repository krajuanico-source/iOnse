<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\GovernmentId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovernmentIdController extends Controller
{
    public function index()
    {
        // ✅ Return a collection instead of a single record
        $governmentIds = GovernmentId::where('user_id', Auth::id())->get();
        return view('content.profile.government-ids', compact('governmentIds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sss_id' => 'nullable|string|max:50',
            'gsis_id' => 'nullable|string|max:50',
            'pagibig_id' => 'nullable|string|max:50',
            'philhealth_id' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'gov_issued_id' => 'nullable|string|max:100',
            'id_number' => 'nullable|string|max:100',
            'date_issuance' => 'nullable|date',
            'place_issuance' => 'nullable|string|max:150',
        ]);

        // ✅ Create a new record (since your Blade expects multiple records)
        GovernmentId::create($data + ['user_id' => Auth::id()]);

        return response()->json(['message' => 'Government ID added successfully']);
    }

    public function update(Request $request, $id)
    {
        $governmentId = GovernmentId::findOrFail($id);
        $governmentId->update($request->all());

        return response()->json(['message' => 'Government ID updated successfully']);
    }

    public function destroy($id)
    {
        GovernmentId::findOrFail($id)->delete();
        return response()->json(['message' => 'Government ID deleted successfully']);
    }
}
