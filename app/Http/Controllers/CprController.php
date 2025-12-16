<?php

namespace App\Http\Controllers;

use App\Models\Cpr;
use Illuminate\Http\Request;

class CprController extends Controller
{
  /**
   * Display a list of CPRs.
   */
  public function index()
  {
    $cprs = Cpr::latest()->get();
    return view('forms.cpr.index', compact('cprs'));
  }

  /**
   * Show modal/form to create a CPR.
   */
  public function create()
  {
    return view('forms.cpr.create');
  }

  /**
   * Store a new CPR record.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'rating_period_start' => 'required',
      'semester' => 'required',
    ]);

    Cpr::create([
      'rating_period_start' => substr($request->rating_period_start, 0, 7), // keeps only YYYY-MM
      'semester' => $request->semester,
      'status' => 'Active',
    ]);

    return back()->with('success', 'CPR created successfully.');
  }

  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'rating_period_start' => 'required',
      'semester' => 'required',
      'status' => 'required',
    ]);

    $cpr = Cpr::findOrFail($id);

    $cpr->update([
      'rating_period_start' => substr($request->rating_period_start, 0, 7), // fixes SQL error
      'semester' => $request->semester,
      'status' => $request->status
    ]);

    return back()->with('success', 'CPR updated successfully.');
  }

  /**
   * Show a single CPR record.
   */
  public function show(Cpr $cpr)
  {
    return view('forms.cpr.show', compact('cpr'));
  }

  /**
   * Show form for editing a CPR.
   */
  public function edit(Cpr $cpr)
  {
    return view('forms.cpr.edit', compact('cpr'));
  }

  /**
   * Delete a CPR record.
   */
  public function destroy(Cpr $cpr)
  {
    $cpr->delete();

    return redirect()->route('forms.cpr.index')
      ->with('success', 'CPR deleted successfully.');
  }

  public function updateRatings(Cpr $cpr)
  {
    // Load related employees and their ratings
    $employees = $cpr->employees; // requires employees() relationship in Cpr model

    // Return a view (Blade) to update ratings
    return view('forms.cpr.update_ratings', compact('cpr', 'employees'));
  }
}
