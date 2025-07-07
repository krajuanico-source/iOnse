<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $query = User::with([
      'division:id,abbreviation',
      'section:id,abbreviation',
      'employmentStatus:id,abbreviation'
    ]);

    if ($request->has('search')) {
      $query->where(function ($q) use ($request) {
        $q->where('first_name', 'like', "%{$request->search}%")
          ->orWhere('last_name', 'like', "%{$request->search}%")
          ->orWhere('employee_id', 'like', "%{$request->search}%");
      });
    }

    return response()->json($query->get());
  }

    public function store(Request $request)
{
    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'employment_status' => 'required|exists:employment_statuses,id',
        'division' => 'required|exists:divisions,id',
        'section' => 'required|exists:sections,id',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
    ]);

    // Auto-generate employee ID again in store (to avoid tampering)
    $latestUser = User::latest('id')->first();
    $latestId = $latestUser ? $latestUser->id + 1 : 1;
    $employeeId = '11-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

    $middleInitial = substr($request->middle_name, 0, 1);
    $empIdLast4 = substr($employeeId, -4);
    $username = strtolower(substr($request->first_name, 0, 1) . $middleInitial . $request->last_name . $empIdLast4);

    User::create([
        'employee_id' => $employeeId,
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
        'extension_name' => $request->extension_name,
        'employment_status_id' => $request->employment_status,
        'division_id' => $request->division,
        'section_id' => $request->section,
        'username' => $username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('employee.registration-form')->with('success', 'Employee registered successfully!');
}


  public function show($id)
  {
    $user = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return response()->json($user);
  }

  public function update(Request $request, $id)
  {
    $user = User::findOrFail($id);

    $data = $request->validate([
      'employee_id' => 'required|unique:users,employee_id,' . $id,
      'first_name' => 'required',
      'middle_name' => 'nullable',
      'last_name' => 'required',
      'extension_name' => 'nullable',
      'username' => 'required|unique:users,username,' . $id,
      'password' => 'nullable|min:6',
      'employment_status_id' => 'nullable|exists:employment_statuses,id',
      'division_id' => 'nullable|exists:divisions,id',
      'section_id' => 'nullable|exists:sections,id',
    ]);

    if (!empty($data['password'])) {
      $data['password'] = Hash::make($data['password']);
    } else {
      unset($data['password']);
    }

    $user->update($data);

    return redirect()->route('employee.view', $id)->with('success', 'Employee updated successfully.');
  }


  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully.']);
  }
  public function bladeIndex()
  {
    $employees = User::with(['division', 'section', 'employmentStatus'])->get();
    return view('content.planning.list-of-employee', compact('employees'));
  }
  public function apiIndex()
  {
    $employees = User::with([
      'division:id,abbreviation',
      'section:id,abbreviation',
      'employmentStatus:id,abbreviation'
    ])->get();

    return response()->json($employees);
  }
  public function showEmployeeView($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.view-employee', compact('employee'));
  }

  public function editEmployeeView($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.edit-employee', compact('employee'));
  }
  public function edit($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.edit-employee', compact('employee'));
  }

  public function create()
{
    $employmentStatuses = EmploymentStatus::all();
    $divisions = Division::all();

    do {
    $latestUser = User::latest('id')->first();
    $latestId = $latestUser ? $latestUser->id + 1 : 1;
    $generatedEmployeeId = '11-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
    } while (User::where('employee_id', $generatedEmployeeId)->exists());

    return view('content.planning.registration-form', compact(
        'employmentStatuses',
        'divisions',
        'generatedEmployeeId'
    ));
}
    public function showImportForm()
    {
        return view('content.planning.import-form');
    }
    public function importEmployees(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new EmployeesImport, $request->file('file'));

        return redirect()->back()->with('success', 'Employees imported successfully!');
    }

}
