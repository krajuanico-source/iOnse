<?php

namespace App\Http\Controllers;

use App\Models\RequestForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class RequestFormController extends Controller
{
  // List all request forms
  public function index()
  {
    $requests = RequestForm::orderBy('req_num', 'DESC')->get();
    return view('forms.request_forms.index', compact('requests'));
  }

  // Show create form
  public function create()
  {
    return view('forms.request_forms.create');
  }
  public function store(Request $request)
  {
    // Get employee ID of logged-in user
    $employeeId = Auth::user()->employee_id ?? null;

    if (!$employeeId) {
      return redirect()->back()->with('error', 'Your employee ID is missing.');
    }

    // Validate inputs (remove empid from user input)
    $validated = $request->validate([
      'req_date'          => 'nullable|date',
      'req_doc'           => 'nullable|string',
      'req_period'        => 'nullable|string',
      'req_purpose'       => 'nullable|string',
      'req_specify'       => 'nullable|string',
      'req_mode'          => 'nullable|string',
      'req_status'        => 'nullable|string',
      'req_date_released' => 'nullable|date',
      'req_incharge'      => 'nullable|string',
      'req_date_recieved' => 'nullable|date',
      'req_released_by'   => 'nullable|string',
      'scan_file'         => 'nullable|file',
    ]);

    // Inject employee ID
    $validated['empid'] = $employeeId;

    // File upload
    if ($request->hasFile('scan_file')) {
      $validated['scan_file'] = $request->file('scan_file')->store('request_files');
    }

    // Insert to DB
    RequestForm::create($validated);

    return redirect()->route('forms.request_forms.index')
      ->with('success', 'Request added successfully!');
  }


  // Edit request
  public function edit($id)
  {
    $requestForm = RequestForm::findOrFail($id);
    return view('forms.request_forms.edit', compact('requestForm'));
  }

  // Update request
  public function update(Request $request, $id)
  {
    $requestForm = RequestForm::findOrFail($id);

    $validated = $request->validate([
      'empid' => 'nullable|string',
      'req_date' => 'nullable|date',
      'req_doc' => 'nullable|string',
      'req_period' => 'nullable|string',
      'req_purpose' => 'nullable|string',
      'req_specify' => 'nullable|string',
      'req_mode' => 'nullable|string',
      'req_status' => 'nullable|string',
      'req_date_released' => 'nullable|date',
      'req_incharge' => 'nullable|string',
      'req_date_recieved' => 'nullable|date',
      'req_released_by' => 'nullable|string',
      'scan_file' => 'nullable|file',
    ]);

    if ($request->hasFile('scan_file')) {
      $validated['scan_file'] = $request->file('scan_file')->store('request_files');
    }

    $requestForm->update($validated);

    return redirect()->route('forms.request_forms.index')->with('success', 'Request updated successfully!');
  }

  // Delete request
  public function destroy($id)
  {
    $requestForm = RequestForm::findOrFail($id);
    $requestForm->delete();

    return redirect()->route('forms.request_forms.index')->with('success', 'Request deleted successfully!');
  }
  // Show specific request form
  public function show($req_num)
  {
    $requestForm = RequestForm::findOrFail($req_num);
    return view('forms.request_forms.show', compact('requestForm'));
  }
  public function print($req_num)
  {
    // Load request form with related employee data
    $requestForm = RequestForm::with([
      'employee.division',
      'employee.section'
    ])->findOrFail($req_num);

    // Load additional dropdown data if needed
    $employmentStatuses = \App\Models\EmploymentStatus::all();
    $divisions = \App\Models\Division::all();
    $sections = \App\Models\Section::all();

    // Get authenticated user
    $user = Auth::user();

    // Pass all data to the Blade view
    return view('forms.request_forms.pdf', compact(
      'requestForm',
      'user',
      'employmentStatuses',
      'divisions',
      'sections'
    ));
  }
}
