<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // ✅ Required import
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use App\Models\SalaryGrade;
use App\Models\Position;

class UserController extends Controller
{
  public function index()
  {
    $employees = User::with(['division', 'section', 'employmentStatus'])->get();
    return view('content.planning.list-of-employee', compact('employees'));
  }
  public function bladeIndex()
  {
    // Example: fetch employees and return the Blade view
    $employees = \App\Models\User::with(['division', 'section', 'employmentStatus'])->get();

    return view('content.planning.list-of-employee', compact('employees'));
  }
  public function create()
  {
    // ✅ Generate the next employee ID
    $latestUser = User::where('employee_id', 'like', '11-%')
      ->orderByDesc('id')
      ->first();

    if ($latestUser && preg_match('/11-(\d+)/', $latestUser->employee_id, $matches)) {
      $number = intval($matches[1]) + 1;
    } else {
      $number = 1;
    }

    $generatedEmployeeId = '11-' . str_pad($number, 4, '0', STR_PAD_LEFT);

    // ✅ Load data for dropdowns
    $employmentStatuses = EmploymentStatus::all();
    $divisions = Division::all();
    $salaryGrades = SalaryGrade::all();
    $positions = Position::all();

    return view('content.planning.registration-form', compact(
      'generatedEmployeeId',
      'employmentStatuses',
      'divisions',
      'salaryGrades',
      'positions'
    ));
  }

  public function active()
  {
    $employees = User::where('status', 'Active')
      ->with(['division', 'section', 'employmentStatus'])
      ->get();

    return view('content.planning.list-of-employee', compact('employees'));
  }

  public function retired()
  {
    $employees = User::where('status', 'Retired')
      ->with(['division', 'section', 'employmentStatus'])
      ->get();

    return view('content.planning.list-of-employee', compact('employees'));
  }

  public function resigned()
  {
    $employees = User::where('status', 'Resigned')
      ->with(['division', 'section', 'employmentStatus'])
      ->get();

    return view('content.planning.list-of-employee', compact('employees'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'employee_id' => 'required|unique:users,employee_id',
      'first_name' => 'required',
      'last_name' => 'required',
      'employment_status' => 'required|exists:employment_statuses,id',
      'division' => 'required|exists:divisions,id',
      'section' => 'required|exists:sections,id',
      'password' => 'required|confirmed|min:6',
    ]);

    // ✅ Capitalize inputs
    $firstName = ucwords(strtolower(trim($request->first_name)));
    $middleName = ucwords(strtolower(trim($request->middle_name)));
    $lastName = ucwords(strtolower(trim($request->last_name)));
    $extensionName = ucwords(strtolower(trim($request->extension_name)));

    // ✅ Username generation
    $middleInitial = substr($middleName, 0, 1);
    $empIdLast4 = substr($request->employee_id, -4);
    $username = strtolower(substr($firstName, 0, 1) . $middleInitial . $lastName . $empIdLast4);

    // ✅ Create user
    User::create([
      'employee_id' => $request->employee_id,
      'first_name' => $firstName,
      'middle_name' => $middleName,
      'last_name' => $lastName,
      'extension_name' => $extensionName,
      'gender' => $request->gender,
      'employment_status_id' => $request->employment_status,
      'division_id' => $request->division,
      'section_id' => $request->section,
      'username' => $username,
      'email' => strtolower($request->email),
      'password' => Hash::make($request->password),
    ]);

    return redirect()->route('employee.registration-form')->with('success', 'Employee registered successfully!');
  }

  public function show($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.employee-view', compact('employee'));
  }

  public function edit($id)
  {
    $employee = User::findOrFail($id);
    $divisions = Division::all();
    $sections = Section::where('division_id', $employee->division_id)->get();
    $employmentStatuses = EmploymentStatus::all();

    return view('content.planning.employee-edit', compact('employee', 'divisions', 'sections', 'employmentStatuses'));
  }

  public function update(Request $request, $id)
  {
    $employee = User::findOrFail($id);

    $request->validate([
      'employee_id' => 'required|unique:users,employee_id,' . $employee->id,
      'first_name' => 'required',
      'last_name' => 'required',
      'employment_status' => 'required',
      'division' => 'required',
      'section' => 'required',
      'password' => 'nullable|min:6',
    ]);

    // ✅ Capitalize inputs
    $firstName = ucwords(strtolower(trim($request->first_name)));
    $middleName = ucwords(strtolower(trim($request->middle_name)));
    $lastName = ucwords(strtolower(trim($request->last_name)));
    $extensionName = ucwords(strtolower(trim($request->extension_name)));

    // ✅ Regenerate username if employee_id changed
    if ($employee->employee_id != $request->employee_id) {
      $middleInitial = substr($middleName, 0, 1);
      $empIdLast4 = substr($request->employee_id, -4);
      $username = strtolower(substr($firstName, 0, 1) . $middleInitial . $lastName . $empIdLast4);
    } else {
      $username = $employee->username;
    }

    // ✅ Update employee
    $employee->update([
      'employee_id' => $request->employee_id,
      'first_name' => $firstName,
      'middle_name' => $middleName,
      'last_name' => $lastName,
      'extension_name' => $extensionName,
      'gender' => $request->gender,
      'employment_status_id' => $request->employment_status,
      'division_id' => $request->division,
      'section_id' => $request->section,
      'username' => $username,
      'email' => strtolower($request->email),
    ]);

    if (!empty($request->password)) {
      $employee->update(['password' => Hash::make($request->password)]);
    }

    return redirect()->route('employee.list-of-employee')->with('success', 'Employee updated successfully!');
  }

  public function getSections(Request $request)
  {
    $divisionId = $request->division_id;
    $sections = Section::where('division_id', $divisionId)->get();
    return response()->json($sections);
  }
  public function assignRole(Request $request, $id)
  {
    $request->validate([
      'role' => 'required|string|max:50',
    ]);

    $employee = \App\Models\User::findOrFail($id);
    $employee->role = $request->role;
    $employee->save();

    return redirect()->back()->with('success', 'Role assigned successfully!');
  }

  public function updateEmployment(Request $request, $id)
  {
    $user = User::findOrFail($id);

    $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'username' => 'required|string|max:255',
      'status' => 'required|in:active,inactive',
      'role' => 'required|string',
      'employment_status_id' => 'nullable|exists:employment_statuses,id',
      'section_id' => 'nullable|exists:sections,id',
      'division_id' => 'nullable|exists:divisions,id',
    ]);

    $user->update([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'username' => $request->username,
      'status' => $request->status,
      'role' => $request->role,
      'employment_status_id' => $request->employment_status_id,
      'section_id' => $request->section_id,
      'division_id' => $request->division_id,
    ]);

    return redirect()->back()->with('success', 'User details updated successfully!');
  }
}
