<?php

namespace App\Http\Controllers;

use App\Models\Medical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalController extends Controller
{
  public function create()
  {
    $employeeId = Auth::user()->employee_id ?? null;
    $row = Medical::where('user_id', $employeeId)->first();

    // Options
    $bloodTypes = ["O+", "O-", "A+", "A-", "B+", "B-", "AB+", "AB-", "Prefer not to say"];
    $yesNoUnknown = ["Yes", "No", "Unknown"];
    $yesNo = ["Yes", "No"];

    $disabilityTypes = [
      "Deaf or Hard of Hearing",
      "Intellectual Disability",
      "Learning Disability",
      "Mental Disability",
      "Orthopedic Disability",
      "Physical Disability",
      "Psychosocial Disability",
      "Speech and Language Impairment",
      "Visual Disability",
      "Not Applicable"
    ];

    $disabilityCauses = [
      "Acquired",
      "Chronic Illness",
      "Comorbidity",
      "Congenital/Inborn",
      "Injury",
      "Rare Disease",
      "Not Applicable"
    ];

    return view('forms.medical.create', compact(
      'row',
      'bloodTypes',
      'yesNoUnknown',
      'yesNo',
      'disabilityTypes',
      'disabilityCauses'
    ));
  }
  public function store(Request $request)
  {
    $employeeId = Auth::user()->employee_id ?? null;
    if (!$employeeId) {
      return redirect()->back()->with('error', 'Your employee ID is missing.');
    }

    $row = Medical::where('user_id', $employeeId)->first();

    $toNull = fn($value) => ($value === '' ? null : $value);

    // Questions that require a "specific" input if Yes
    $requiresSpecific = ['autoimmune', 'cancer', 'mental_health', 'health_condition'];

    $data = [
      'user_id' => $employeeId,
      'blood_type' => $toNull($request->blood_type),
      'qualified_blood_donation' => $toNull($request->qualified_blood_donation),
      'blood_donation' => $toNull($request->blood_donation),
      'asthma' => $toNull($request->asthma),
      'autoimmune' => $toNull($request->autoimmune),
      'cancer' => $toNull($request->cancer),
      'diabetes_mellitus' => $toNull($request->diabetes_mellitus),
      'heart_disease' => $toNull($request->heart_disease),
      'hiv_aids' => $toNull($request->hiv_aids),
      'hypertension' => $toNull($request->hypertension),
      'kidney_disease' => $toNull($request->kidney_disease),
      'liver_disease' => $toNull($request->liver_disease),
      'mental_health' => $toNull($request->mental_health),
      'seizures' => $toNull($request->seizures),
      'health_condition' => $toNull($request->health_condition),
      'maintenance_med' => $toNull($request->maintenance_med),
      'disability_type' => $toNull($request->disability_type),
      'disability_cause' => $toNull($request->disability_cause),
      'created_on' => $row ? $row->created_on : now(),
      'updated_on' => now(),
    ];

    // Handle the "specific" inputs
    foreach ($requiresSpecific as $field) {
      $data[$field . '_other'] = $request->$field === 'Yes' ? $toNull($request->{$field . '_other'}) : null;
    }

    if ($row) {
      $row->update($data);
    } else {
      Medical::create($data);
    }

    return redirect()->route('forms.medical.create')->with('success', 'Medical record saved.');
  }
}
