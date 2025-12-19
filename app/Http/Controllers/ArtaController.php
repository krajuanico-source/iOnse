<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arta;
use App\Models\Position;
use App\Models\Division;
use App\Models\Section;
use App\Models\OfficeLocation;
use Illuminate\Support\Facades\Storage;

class ArtaController extends Controller
{
  public function index()
  {
    // Get the latest ARTA record or null
    $arta = Arta::latest()->first();

    $positions = Position::all();
    $divisions = Division::all();
    $sections = Section::all();
    $assignments = OfficeLocation::all();

    return view('forms.arta.index', compact('arta', 'positions', 'divisions', 'sections', 'assignments'));
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'arta_date' => 'required|date',
      'arta_type' => 'required|string|max:255',
      'arta_specify' => 'nullable|string|max:255',
      'arta_specify_other' => 'nullable|string|max:255',
      'arta_purpose' => 'required|string|max:255',
      'arta_mode' => 'required|string|max:50',
      'arta_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    if ($data['arta_specify'] !== 'Others (please specify)') {
      $data['arta_specify_other'] = null;
    }

    if ($request->hasFile('arta_file')) {
      $data['arta_file'] = $request->file('arta_file')->store('arta_files');
    }

    Arta::create($data);

    return redirect()->route('forms.arta.index')->with('success', 'ARTA record created successfully.');
  }


  // Edit ARTA
  public function edit(Arta $arta)
  {
    return view('arta.edit', [
      'arta' => $arta,
      'positions' => Position::all(),
      'divisions' => Division::all(),
      'sections' => Section::all(),
      'assignments' => OfficeLocation::all(),
    ]);
  }

  // Update ARTA
  public function update(Request $request, Arta $arta)
  {
    $data = $request->validate([
      'arta_date' => 'required|date',
      'arta_type' => 'required|string|max:255',
      'arta_specify' => 'nullable|string|max:255',
      'arta_specify_other' => 'nullable|string|max:255',
      'arta_purpose' => 'required|string|max:255',
      'arta_mode' => 'required|string|max:50',
      'arta_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Handle "Others" field
    if ($data['arta_specify'] !== 'Others (please specify)') {
      $data['arta_specify_other'] = null;
    }

    // Handle file upload
    if ($request->hasFile('arta_file')) {
      if ($arta->arta_file) {
        Storage::delete($arta->arta_file); // delete old file
      }
      $data['arta_file'] = $request->file('arta_file')->store('arta_files');
    }

    $arta->update($data);

    return redirect()->route('forms.arta.index')->with('success', 'ARTA record updated successfully.');
  }
}
