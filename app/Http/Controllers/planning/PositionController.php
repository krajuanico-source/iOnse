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
  /**
   * Display list of positions with related models
   */
  public function index()
  {
    $positions = Position::with(['salaryGrade', 'employmentStatus', 'requirements'])->get();
    $salaryGrades = SalaryGrade::all();
    $employmentStatuses = EmploymentStatus::all();
    // $qualifications = Qualification::all();

    return view('content.planning.position', compact(
      'positions',
      'salaryGrades',
      'employmentStatuses'
    ));
  }

  /**
   * Store a new position with qualifications (optional)
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'position_name'   => 'required|string|max:255',
      'abbreviation'    => 'required|string|max:50',
      'salary_grade_id' => 'nullable|exists:salary_grades,id',
      'employment_status_id' => 'nullable|exists:employment_statuses,id',
    ]);

    $validated['position_name'] = strtoupper($validated['position_name']);
    $validated['abbreviation']  = strtoupper($validated['abbreviation']);

    $position = Position::create($validated);

    // Attach qualifications if provided
    if ($request->has('qualifications')) {
      $position->qualifications()->sync($request->qualifications);
    }

    return response()->json(['success' => true, 'message' => 'Position created successfully.']);
  }

  /**
   * Update a position and sync qualifications
   */
  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'position_name'   => 'required|string|max:255',
      'abbreviation'    => 'required|string|max:50',
      'salary_grade_id' => 'nullable|exists:salary_grades,id',
      'employment_status_id' => 'nullable|exists:employment_statuses,id',
    ]);

    $validated['position_name'] = strtoupper($validated['position_name']);
    $validated['abbreviation']  = strtoupper($validated['abbreviation']);

    $position = Position::findOrFail($id);
    $position->update($validated);

    // Update qualifications
    if ($request->has('qualifications')) {
      $position->qualifications()->sync($request->qualifications);
    }

    return response()->json(['success' => true, 'message' => 'Position updated successfully.']);
  }

  /**
   * Delete a position
   */
  public function destroy($id)
  {
    $position = Position::findOrFail($id);
    $position->delete();

    return response()->json(['success' => true, 'message' => 'Position deleted successfully.']);
  }
  public function updateStatus(Request $request, $id)
  {
    $request->validate([
      'status' => 'required|in:On going Hiring,Close Hiring,For Examination,For Interview'
    ]);

    $position = Position::findOrFail($id);
    $position->status = $request->status;
    $position->save();

    return redirect()->back()->with('success', 'Position status updated successfully!');
  }
}
