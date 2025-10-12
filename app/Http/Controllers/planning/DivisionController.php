<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Section;

class DivisionController extends Controller
{
  public function index()
  {
    $divisions = Division::all();
    return view('content.planning.division', compact('divisions'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:50'
    ]);

    // Convert to uppercase before storing
    $validated['name'] = strtoupper($validated['name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);

    $division = Division::create($validated);

    return response()->json([
      'success' => true,
      'division' => $division
    ]);
  }

  public function update(Request $request, $id)
  {
    $division = Division::findOrFail($id);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:50'
    ]);

    // Convert to uppercase before updating
    $validated['name'] = strtoupper($validated['name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);

    $division->update($validated);

    return response()->json(['success' => true]);
  }

  public function getSections($id)
  {
    $sections = Section::where('division_id', $id)->get(['id', 'name']);
    return response()->json($sections);
  }
  public function destroy($id)
  {
    $division = Division::findOrFail($id);
    $division->delete();

    return response()->json(['success' => true]);
  }
}
