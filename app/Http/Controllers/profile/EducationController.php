<?php

namespace App\Http\Controllers\Profile;

use App\Http\Requests\EducationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Education;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{

    public function index()
    {
        $educations = Education::where('user_id', Auth::id())->get();

        return view('content.profile.education', compact('educations'));
    }

    public function save(EducationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        Education::updateOrCreate(
            [
                'id' => $request->education_id, // hidden input
                'user_id' => Auth::id(),
            ],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Education saved successfully.'
        ]);
    }


    public function show($id)
    {
        $education = Education::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json($education);
    }

    public function delete($id)
    {
        $education = Education::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $education->delete();

        return response()->json([
            'success' => true,
            'message' => 'Education deleted successfully.'
        ]);
    }
}
