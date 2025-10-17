<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\WorkExperience;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WorkExperienceController extends Controller
{
    public function index()
    {
        $experiences = WorkExperience::where('user_id', Auth::id())->get();
        return view('content.profile.work-experience', compact('experiences'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'position_title' => 'required|string|max:255',
        ]);

        $data = $request->only([
            'date_from',
            'date_to',
            'position_title',
            'department_agency',
            'monthly_salary',
            'salary_grade',
            'status_of_appointment',
            'govt_service',
        ]);

        $data['user_id'] = Auth::id();

        WorkExperience::updateOrCreate(
            ['id' => $request->work_experience_id, 'user_id' => Auth::id()],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Work experience saved successfully.'
        ]);
    }


    public function update(Request $request, $id)
    {
        $experience = WorkExperience::findOrFail($id);
        $experience->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        WorkExperience::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
