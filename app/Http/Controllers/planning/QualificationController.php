<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Qualification;

class QualificationController extends Controller
{
  /**
   * Display all qualifications (or filter by position).
   */
  public function index(Request $request)
  {
    if ($request->has('position_id')) {
      $qualifications = Qualification::where('position_id', $request->position_id)->get();
    } else {
      $qualifications = Qualification::all();
    }

    return view('content.planning.qualification', compact('qualifications'));
  }

  /**
   * Store a new qualification.
   */
  public function store(Request $request)
  {
    $request->validate([
      'position_id' => 'required|exists:positions,id',
      'title'       => 'required|string|max:255|unique:qualifications,title',
      'description' => 'nullable|string',
    ]);

    Qualification::create([
      'position_id' => $request->position_id,
      'title'       => strtoupper($request->title),
      'description' => $request->description ? strtoupper($request->description) : null,
    ]);

    return redirect()->back()->with('success', 'Qualification added.');
  }

  /**
   * Update an existing qualification.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'title'       => 'required|string|max:255|unique:qualifications,title,' . $id,
      'description' => 'nullable|string',
    ]);

    $qualification = Qualification::findOrFail($id);

    $qualification->update([
      'title'       => strtoupper($request->title),
      'description' => $request->description ? strtoupper($request->description) : null,
    ]);

    return response()->json(['success' => true]);
  }

  /**
   * Delete a qualification.
   */
  public function destroy($id)
  {
    Qualification::findOrFail($id)->delete();
    return response()->json(['success' => true]);
  }
}
