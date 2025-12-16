<?php

namespace App\Http\Controllers;

use App\Models\ItemNumber;
use App\Models\Position;
use App\Models\EmploymentStatus;
use App\Models\FundSOurce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemNumberController extends Controller
{
  public function index()
  {
    $itemNumbers = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus', 'fundSource'])->get();
    $positions = Position::with(['salaryGrade', 'employmentStatus'])->get();
    $employmentStatuses = EmploymentStatus::all();
    $salaryGrades = \App\Models\SalaryGrade::all();
    $fundSources  = \App\Models\FundSource::all(); // ✅ fetch fund sources

    return view('content.planning.item-numbers', compact(
      'itemNumbers',
      'positions',
      'employmentStatuses',
      'salaryGrades',
      'fundSources'
    ));
  }

  /**
   * Generate the next item number
   */
  public function getNextNumber($statusId, $positionId)
  {
    $status   = EmploymentStatus::findOrFail($statusId);
    $position = Position::findOrFail($positionId);

    // Get latest item number for this status+position
    $lastItem = ItemNumber::where('employment_status_id', $statusId)
      ->where('position_id', $positionId)
      ->orderBy('id', 'desc')
      ->first();

    // Extract last sequence number
    $lastNumber = 0;
    if ($lastItem && preg_match('/-(\d{6})$/', $lastItem->item_number, $matches)) {
      $lastNumber = (int) $matches[1];
    }

    // Increment
    $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

    // 🔹 Build item number
    if (strtoupper($status->abbreviation) === 'OSEC-DSWDB') {
      // Permanent → use only OSEC-DSWDB
      $itemNumber = $status->abbreviation . '-' . strtoupper($position->position_name) . '-' . $nextNumber;
    } else {
      // Others → always prepend FO XI
      $itemNumber = 'FO XI-' . $status->abbreviation . '-' . strtoupper($position->position_name) . '-' . $nextNumber;
    }

    return response()->json(['item_number' => $itemNumber]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'item_number'          => 'required|unique:item_numbers,item_number',
      'position_id'          => 'required|exists:positions,id',
      'employment_status_id' => 'required|exists:employment_statuses,id',
      'salary_grade_id'      => 'required|exists:salary_grades,id',
      'fund_source_id'       => 'required|exists:fund_sources,id',
      'date_posting'         => 'nullable|date',
      'date_end_submission'  => 'nullable|date|after_or_equal:date_posting',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    ItemNumber::create([
      'item_number'          => $request->item_number,
      'position_id'          => $request->position_id,
      'salary_grade_id'      => $request->salary_grade_id,
      'employment_status_id' => $request->employment_status_id,
      'fund_source_id'       => $request->fund_source_id,
      'date_posting'         => $request->date_posting,
      'date_end_submission'  => $request->date_end_submission,
      'status'               => 'active',
      'stature'              => $request->stature ?? 'unfilled',
    ]);

    return response()->json(['success' => true, 'message' => 'Item Number added successfully.']);
  }

  public function edit($id)
  {
    $itemNumber = ItemNumber::findOrFail($id);
    return response()->json($itemNumber);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'item_number' => 'required|unique:item_numbers,item_number,' . $id,
      'position_id' => 'required|exists:positions,id',
      'employment_status_id' => 'required|exists:employment_statuses,id',
      'salary_grade_id' => 'required|exists:salary_grades,id',
      'status' => 'required|in:active,inactive',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $itemNumber = ItemNumber::findOrFail($id);
    $itemNumber->update([
      'item_number' => $request->item_number,
      'position_id' => $request->position_id,
      'employment_status_id' => $request->employment_status_id,
      'salary_grade_id' => $request->salary_grade_id,
      'status' => $request->status,
    ]);

    return response()->json(['success' => true, 'message' => 'Item Number updated successfully.']);
  }
  public function print($id)
  {
    $item = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus', 'fundSource'])->findOrFail($id);

    return Pdf::loadView('content.planning.item-numbers.print', compact('item'))
      ->setPaper('a4', 'portrait')
      ->stream("Notice_of_Vacancy_{$item->item_number}.pdf");
  }

  public function updateStatus(Request $request, $id)
  {
    $request->validate([
      'status' => 'required|in:On going Hiring,Close Hiring,For Examination,For Interview,Filled',
    ]);

    $item = ItemNumber::findOrFail($id);
    $item->status = $request->status;

    // Automatically update stature if status is Filled
    if ($request->status === 'Filled') {
      $item->stature = 'filled';
    }

    $item->save();

    return redirect()->back()->with('success', 'Item Number status updated successfully!');
  }
}
