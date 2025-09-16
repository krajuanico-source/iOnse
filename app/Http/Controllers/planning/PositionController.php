<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Requirement;
use App\Models\Qualification;
use App\Models\SalaryGrade;
use App\Models\EmploymentStatus;

class PositionController extends Controller
{

  public function index()
  {
    $positions = Position::with(['salaryGrade', 'employmentStatus'])->get();
    $salaryGrades = SalaryGrade::all();
    $employmentStatuses = EmploymentStatus::all();
    $qualifications = Qualification::all(); // ✅ required

    return view('content.planning.position', compact(
      'positions',
      'salaryGrades',
      'employmentStatuses',
      'qualifications'
    ));
  }


  public function store(Request $request)
  {
    $validated = $request->validate([
      'position_name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:50',
      'item_no' => 'required|string|max:50',
      'salary_grade_id' => 'required|exists:salary_grades,id',
      'employment_status_id' => 'required|exists:employment_statuses,id',
      'status' => 'required|in:active,inactive',
      'qualifications' => 'nullable|array',
      'qualifications.*' => 'exists:qualifications,id',
    ]);

    $validated['position_name'] = strtoupper($validated['position_name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);
    $validated['item_no'] = strtoupper($validated['item_no']);

    $position = Position::create($validated);

    if ($request->has('qualifications')) {
      $position->qualifications()->sync($request->qualifications);
    }

    return response()->json(['success' => true]);
  }

  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'position_name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:50',
      'item_no' => 'required|string|max:50',
      'salary_grade_id' => 'required|exists:salary_grades,id',
      'employment_status_id' => 'required|exists:employment_statuses,id',
      'status' => 'required|in:active,inactive',
      'qualifications' => 'nullable|array',
      'qualifications.*' => 'exists:qualifications,id',
    ]);

    $validated['position_name'] = strtoupper($validated['position_name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);
    $validated['item_no'] = strtoupper($validated['item_no']);

    $position = Position::findOrFail($id);
    $position->update($validated);

    if ($request->has('qualifications')) {
      $position->qualifications()->sync($request->qualifications);
    }

    return response()->json(['success' => true]);
  }

  public function destroy($id)
  {
    $position = Position::findOrFail($id);
    $position->delete();

    return response()->json(['success' => true]);
  }
}
