<?php

namespace App\Http\Controllers;

use App\Models\Special;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpecialController extends Controller
{
  /**
   * Display a listing of the specials.
   */
  public function index()
  {
    $specials = Special::all();
    $employees = User::all();

    return view('forms.special.index', compact('specials', 'employees'));
  }

  /**
   * Show the form for creating a new special.
   */
  public function create()
  {
    $employees = User::select('id', 'first_name', 'employee_id')->get();
    $sections = Section::all();

    return view('forms.special.create', compact('employees', 'sections'));
  }

  /**
   * Store a newly created special in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'sp_section' => 'required',
      'special_subject' => 'required',
      'activity_title' => 'required',
      'user_id.*' => 'required',
      'special_purpose.*' => 'required',
    ]);

    // Generate Reference Number (SP-YYYYMMDD-####)
    $latest = DB::table('tbl_special')->latest('id_special')->first();
    $nextId = $latest ? $latest->special_id + 1 : 1;
    $ref = 'SP-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

    // Insert main special record
    $specialId = DB::table('tbl_special')->insertGetId([
      'special_ref' => $ref,
      'sp_section' => $request->sp_section,
      'special_subject' => $request->special_subject,
      'training_type' => $request->training_type,
      'activity_title' => $request->activity_title,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Insert related employees
    foreach ($request->user_id as $index => $user_id) {
      DB::table('tbl_special_staff')->insert([
        'id_special' => $specialId,
        'user_id' => $user_id,
        'special_purpose' => $request->special_purpose[$index],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    return redirect()->back()->with('success', 'Special Request Successfully Saved!');
  }

  /**
   * Show the form for editing a special.
   */
  public function edit($id)
  {
    $special = Special::findOrFail($id);
    return view('forms.special.edit', compact('special'));
  }

  /**
   * Update the specified special in storage.
   */
  public function update(Request $request, $id)
  {
    $special = Special::findOrFail($id);

    $data = $request->validate([
      'user_id' => 'required|string',
      'special_subject' => 'required|string',
      'special_from_date' => 'nullable|date',
      'special_to_date' => 'nullable|date',
      'special_date_request' => 'nullable|date',
      'special_purpose' => 'nullable|string',
      'special_number' => 'nullable|string',
      'special_date_approve' => 'nullable|date',
      'special_approve_by' => 'nullable|string',
      'special_to' => 'nullable|string',
      'special_from' => 'nullable|string',
      'special_requested_by' => 'nullable|string',
      'status' => 'nullable|string',
      'file_image' => 'nullable|string',
      'sp_section' => 'nullable|string',
      'sp_venue' => 'nullable|string',
      'training_type' => 'nullable|string',
      'pdf_file' => 'nullable|string',
    ]);

    $special->update($data);

    return redirect()->route('forms.special.index')->with('success', 'Special record updated successfully.');
  }

  /**
   * Remove the specified special from storage.
   */
  public function destroy($id)
  {
    $special = Special::findOrFail($id);
    $special->delete();

    return redirect()->route('forms.special.index')->with('success', 'Special record deleted successfully.');
  }
}
