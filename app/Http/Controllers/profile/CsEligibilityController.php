<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CsEligibility;
use Illuminate\Support\Facades\Auth;

class CsEligibilityController extends Controller
{
    public function index()
    {
        $eligibilities = CsEligibility::where('user_id', Auth::id())->get();
        return view('content.profile.cs-eligibility', compact('eligibilities'));
    }

        public function store(Request $request)
        {

            CsEligibility::create([
                'user_id' => auth()->id(),
                'eligibility' => $request->eligibility,
                'rating' => $request->rating,
                'exam_date' => $request->exam_date,
                'exam_place' => $request->exam_place,
                'license_number' => $request->license_number,
                'license_validity' => $request->license_validity,
            ]);

            return response()->json(['success' => true]);
        }

    public function show($id)
    {
        return response()->json(CsEligibility::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $eligibility = CsEligibility::findOrFail($id);
        $eligibility->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        CsEligibility::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
    
}
