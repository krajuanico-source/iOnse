<?php

namespace App\Http\Controllers;

use App\Models\Ethnicity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EthnicityController extends Controller
{
  // Show form
  public function create()
  {
    $row = Ethnicity::where('user_id', Auth::user()->employee_id ?? null)->first();

    // Household options (0–20)
    $householdOptions = range(0, 20);

    // Living condition options
    $livingOptions = [
      "Owned",
      "Living with parents",
      "Living with relatives",
      "Rental",
      "Others"
    ];

    return view('forms.ethnicity.create', compact('row', 'householdOptions', 'livingOptions'));
  }

  // Store or update
  public function store(Request $request)
  {
    $employeeId = Auth::user()->employee_id ?? null;
    if (!$employeeId) {
      return redirect()->back()->with('error', 'Your employee ID is missing.');
    }

    // Existing record
    $row = Ethnicity::where('user_id', $employeeId)->first();

    // Helper functions
    $toNull = fn($value) => ($value === 'Other' || $value === '' ? null : $value);
    $toZero = fn($value) => ($value === 'Other' || $value === '' ? 0 : $value);

    // Handle ethnicity: use 'ethnicity_other' if 'Other' selected
    $ethnicityValue = $request->ethnicity === 'Other' ? $request->ethnicity_other : $request->ethnicity;

    // Handle living condition: use 'living_condition_other' if 'Other' selected
    $livingConditionValue = $request->living_condition === 'Other' ? $request->living_condition_other : $request->living_condition;
    $ethnicityValue = $request->ethnicity === 'Other' ? $request->ethnicity_other : $request->ethnicity;

    $data = [
      'user_id' => $employeeId,
      'ethnicity' => $toNull($ethnicityValue),
      'ethnicity_other' => $request->ethnicity === 'Other' ? $request->ethnicity_other : null,
      'household_count' => $toZero($request->household_count),
      'zero_above' => $toZero($request->zero_above),
      'six_above' => $toZero($request->six_above),
      'eighteen_above' => $toZero($request->eighteen_above),
      'forty_six_above' => $toZero($request->forty_six_above),
      'sixty_above' => $toZero($request->sixty_above),
      'children_still_studying' => $toZero($request->children_still_studying),
      'special_needs' => $toZero($request->special_needs),
      'description' => $request->description,
      'living_condition' => $request->$livingConditionValue,
      'living_condition_other' => $request->living_condition === 'Other' ? $request->living_condition_other : null,
      'updated_on' => now(),
    ];

    if ($row) {
      $row->update($data);
      return redirect()->route('forms.ethnicity.create')->with('success', 'Ethnicity record updated.');
    } else {
      $data['created_on'] = now();
      Ethnicity::create($data);
      return redirect()->route('forms.ethnicity.create')->with('success', 'Ethnicity record added.');
    }
  }
}
