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
            'sss_id'                => 'nullable|string|max:50',
            'gsis_id'               => 'nullable|string|max:50',
            'pagibig_id'            => 'nullable|string|max:50',
            'philhealth_id'         => 'nullable|string|max:50',
            'tin'                   => 'nullable|string|max:50',
            'philsys'               => 'nullable|string|max:50',
            'gov_issued_id'         => 'nullable|string|max:100',
            'id_number'             => 'nullable|string|max:100',
            'date_issuance'         => 'nullable|date',
            'place_issuance'        => 'nullable|string|max:150',
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
   public function updateAll(Request $request)
{
    // Delete removed IDs
    $deletedIds = array_filter(explode(',', $request->deleted_government_ids ?? ''));
    if(!empty($deletedIds)) {
        GovernmentId::whereIn('id', $deletedIds)->delete();
    }

    // Update existing IDs
    foreach ($request->government_ids ?? [] as $govData) {
        if(!empty($govData['id'])) {
            $gov = GovernmentId::find($govData['id']);
            if($gov) {
                $gov->update([
                    'sss_id'            => $govData['sss_id'] ?? null,
                    'gsis_id'           => $govData['gsis_id'] ?? null,
                    'pagibig_id'        => $govData['pagibig_id'] ?? null,
                    'philhealth_id'     => $govData['philhealth_id'] ?? null,
                    'tin'               => $govData['tin'] ?? null,
                    'philsys'           => $govData['philsys'] ?? null,
                    'gov_issued_id'     => $govData['gov_issued_id'] ?? null,
                    'id_number'         => $govData['id_number'] ?? null,
                    'date_issuance'     => $govData['date_issuance'] ?? null,
                    'place_issuance'    => $govData['place_issuance'] ?? null,
                ]);
            }
        }
    }

    return response()->json(['message' => 'Government IDs updated successfully!']);
}

}
