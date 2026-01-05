<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\GovernmentId;
use Illuminate\Http\Request;

class GovernmentIdController extends Controller
{
    public function index()
    {
        $governmentIds = GovernmentId::where('user_id', auth()->id())->get();
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
            'philsys' => 'nullable|string|max:50',
            'gov_issued_id' => 'nullable|string|max:100',
            'id_number' => 'nullable|string|max:100',
            'date_issuance' => 'nullable|date',
            'place_issuance' => 'nullable|string|max:150',
        ]);

        $data['user_id'] = auth()->id();
        $gov = GovernmentId::create($data);

        return response()->json([
            'message' => 'Government ID created successfully',
            'id' => $gov->id
        ]);
    }

    public function update(Request $request, $id)
    {
        $gov = GovernmentId::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->only([
            'sss_id', 'gsis_id', 'pagibig_id', 'philhealth_id',
            'tin', 'philsys', 'gov_issued_id', 'id_number',
            'date_issuance', 'place_issuance'
        ]);

        $gov->update($data);

        return response()->json(['message' => 'Government ID updated successfully']);
    }

    public function destroy($id)
    {
        $gov = GovernmentId::where('user_id', auth()->id())->findOrFail($id);
        $gov->delete();

        return response()->json(['message' => 'Government ID deleted successfully']);
    }
}
