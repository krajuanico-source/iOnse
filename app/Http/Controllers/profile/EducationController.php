<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Education;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    // Show the Educational Background page
    public function index()
    {
        $educations = Education::where('user_id', Auth::id())->get();
        return view('content.profile.education', compact('educations'));
    }

    // Save or Update Education
    public function save(Request $request)
    {
        $request->validate([
            'level_of_education' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
        ]);

        $data = $request->only([
            'level_of_education',
            'school_name',
            'degree_course',
            'from',
            'to',
            'highest_level_earned',
            'year_graduated',
            'scholarship_honors',
        ]);

        $data['user_id'] = Auth::id();

        // Update or create
        Education::updateOrCreate(
            ['id' => $request->education_id, 'user_id' => Auth::id()],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Education saved successfully.']);
    }

    // Show single education record for editing
    public function show($id)
    {
        $education = Education::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($education);
    }

    // Delete education
    public function delete($id)
    {
        $education = \App\Models\Education::findOrFail($id);
        $education->delete();

        return response()->json(['message' => 'Education deleted successfully']);
    }
    public function update(Request $request, $id)
{
    $education = \App\Models\Education::findOrFail($id);

    $education->update([
        'level_of_education' => $request->level_of_education,
        'school_name' => $request->school_name,
        'degree_course' => $request->degree_course,
        'from' => $request->from,
        'to' => $request->to,
        'highest_level_earned' => $request->highest_level_earned,
        'year_graduated' => $request->year_graduated,
        'scholarship_honors' => $request->scholarship_honors,
    ]);

    return response()->json(['success' => true]);
}
}
