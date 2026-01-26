<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\GovernmentIdRequest;
use App\Models\GovernmentId;
use Illuminate\Http\Request;

class GovernmentIdController extends Controller
{
    public function index()
    {
        $governmentIds = GovernmentId::where('user_id', auth()->id())->get();
        return view('content.profile.government-ids', compact('governmentIds'));
    }

    public function store(GovernmentIdRequest $request)
        {
            // Merge the authenticated user ID
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            $gov = GovernmentId::create($data);

            return response()->json([
                'message' => 'Government ID created successfully',
                'id' => $gov->id,
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
