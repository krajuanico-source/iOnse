<?php

namespace App\Http\Controllers;

use App\Models\SoloParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoloParentController extends Controller
{
  public function create()
  {
    $row = SoloParent::where('user_id', Auth::user()->employee_id ?? null)->first();

    $circumstances = [
      "Death of the husband/wife",
      "Legal Separation",
      "Spouse left",
      "Unwed",
      "Others",
      "Not Applicable"
    ];

    return view('forms.solo_parent.create', compact('row', 'circumstances'));
  }

  public function store(Request $request)
  {
    $employeeId = Auth::user()->employee_id ?? null;
    if (!$employeeId) {
      return redirect()->back()->with('error', 'Your employee ID is missing.');
    }

    $row = SoloParent::where('user_id', $employeeId)->first();

    $data = [
      'user_id' => $employeeId,
      'circumstance' => $request->circumstance === 'Others' ? null : $request->circumstance,
      'circumstance_other' => $request->circumstance === 'Others' ? $request->circumstance_other : null,
      'updated_on' => now(),
    ];

    if ($row) {
      $row->update($data);
    } else {
      $data['created_on'] = now();
      SoloParent::create($data);
    }

    return redirect()->route('forms.solo_parent.create')->with('success', 'Solo Parent information saved.');
  }
}
