<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeeProfileController extends Controller
{
    // Show the blade with the dropdown
    public function index()
    {
        $employees = User::all(); // Fetch all employees
        return view('content.profile.view', compact('employees'));
    }

    // Return employee data as JSON
    public function fetchEmployee($id)
{
    $employee = User::with([
        'familyBackgrounds',
        'educations',
        'csEligibilities',
        'workExperiences',
        'voluntaryWorks',
        'learningAndDevelopments',
        'Skills',
        'nonAcademics',
        'organizations',
        'references',
        'governmentIds',
        'otherInformations'
    ])->find($id);

    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    return response()->json($employee);
}

}
