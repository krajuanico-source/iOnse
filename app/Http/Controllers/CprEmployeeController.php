<?php

namespace App\Http\Controllers;

use App\Models\CprEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cpr;
use Illuminate\Support\Facades\Storage;


class CprEmployeeController extends Controller
{
  public function index()
  {
    // Get all CPRs with their employees
    $cprs = Cpr::with('employees')->latest()->get();

    $userId = Auth::id();

    return view('forms.cpremployee.index', compact('cprs', 'userId'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|integer',
      'cpr_id'      => 'required|integer',
      'rating'      => 'required|numeric|min:0|max:100',
      'cpr_file'    => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
    ]);

    if ($request->hasFile('cpr_file')) {
      $validated['cpr_file'] = $request->file('cpr_file')
        ->store('cpr_file', 'public');
    }

    CprEmployee::create($validated);

    return back()->with('success', 'CPR Employee added successfully.');
  }

  public function destroy($id)
  {
    $record = CprEmployee::findOrFail($id);
    $record->delete();

    return back()->with('success', 'CPR Employee deleted successfully.');
  }
  public function requestActivation(Request $request)
  {
    $request->validate([
      'cpr_id' => 'required|exists:cprs,id',
    ]);

    $cpr = Cpr::findOrFail($request->cpr_id);

    // Mark as Requested for HR
    $cpr->status = 'Requested';
    $cpr->save();

    return response()->json([
      'message' => 'Activation request sent to HR successfully!'
    ]);
  }

  public function update(Request $request, $cprId)
  {
    $validated = $request->validate([
      'employee_id' => 'required|integer',
      'rating'      => 'required|numeric|min:0|max:100',
      'cpr_file'    => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
    ]);

    $cprEmployee = CprEmployee::firstOrNew([
      'cpr_id'      => $cprId,
      'employee_id' => $validated['employee_id'],
    ]);

    // ✅ Replace file only if a new one is uploaded
    if ($request->hasFile('cpr_file')) {

      // delete old file
      if (
        $cprEmployee->cpr_file &&
        Storage::disk('public')->exists($cprEmployee->cpr_file)
      ) {
        Storage::disk('public')->delete($cprEmployee->cpr_file);
      }

      // store new file
      $cprEmployee->cpr_file = $request->file('cpr_file')
        ->store('cpr_file', 'public');
    }

    $cprEmployee->rating = $validated['rating'];
    $cprEmployee->save();

    return response()->json([
      'success' => true,
      'message' => 'CPR rating updated successfully.'
    ]);
  }

  public function updateRatings(Cpr $cpr)
  {
    $employees = $cpr->employees; // all ratings for this CPR
    return view('cprs.update_ratings', compact('cpr', 'employees'));
  }
}
