<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Education;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    /**
     * Display educational background of logged-in user
     */
    public function index()
    {
        $educations = Education::where('user_id', Auth::id())->get();

        return view('content.profile.education', compact('educations'));
    }

    /**
     * Validation rules
     */
    private function rules()
    {
        return [
            'level_of_education'   => 'required|string|max:255',
            'school_name'          => 'required|string|max:255',
            'degree_course'        => 'nullable|string|max:255',
            'from'                 => 'required|date',
            'to'                   => 'nullable|date|after_or_equal:from',
            'highest_level_earned' => 'nullable|string|max:255',
            'year_graduated'       => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'scholarship_honors'   => 'nullable|string|max:255',
        ];
    }

    /**
     * Store or update education
     */
public function save(Request $request)
{
    $validated = $request->validate([
        'level_of_education'   => 'required|string|max:255',
        'school_name'          => 'required|string|max:255',
        'degree_course'        => 'nullable|string|max:255',
        'from'                 => 'required|digits:4|integer|min:1900|max:' . date('Y'),
        'to'                   => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
        'highest_level_earned' => 'nullable|string|max:255',
        'year_graduated'       => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
        'scholarship_honors'   => 'nullable|string|max:255',
    ]);

    $validated['user_id'] = Auth::id();

    Education::updateOrCreate(
        [
            'id' => $request->education_id, // 👈 use hidden field
            'user_id' => Auth::id(),
        ],
        $validated
    );

    return response()->json([
        'success' => true,
        'message' => 'Education saved successfully.'
    ]);
}

    /**
     * Show education record for editing
     */
    public function show($id)
    {
        $education = Education::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json($education);
    }

    /**
     * Delete education record
     */
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
