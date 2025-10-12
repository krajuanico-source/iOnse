<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemNumber;
use App\Models\Applicant;
use App\Models\Division;

class UnfilledPositionsController extends Controller
{
  public function index()
  {
    $itemNumbers = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus'])
      ->where('stature', 'unfilled')
      ->get();

    return view('content.planning.unfilled_positions.index', compact('itemNumbers'));
  }

  public function show($id)
  {
    $item = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus', 'applicants'])
      ->findOrFail($id);

    $applicants = $item->applicants;

    // This view will be loaded inside the modal
    return view('content.planning.unfilled_positions.show', compact('item', 'applicants'));
  }
  public function storeApplicant(Request $request, $id)
  {
    $request->validate([
      'first_name'     => 'required|string|max:100',
      'middle_name'    => 'nullable|string|max:100',
      'last_name'      => 'required|string|max:100',
      'extension_name' => 'nullable|string|max:50',
      'sex'            => 'required|in:Male,Female,Other',
      'date_of_birth'  => 'required|date',
      'date_applied'   => 'required|date',
      'status'         => 'nullable|string',
      'remarks'        => 'nullable|string|max:255',
      'date_hired'     => 'nullable|date',
      'mobile_no'      => 'nullable|string|max:20', // add mobile number
      'email'          => 'nullable|email|max:255',  // add email
    ]);


    $item = ItemNumber::findOrFail($id);

    // Generate username: first letter of first + first letter of middle + last + extension
    $firstInitial  = strtoupper(substr($request->first_name, 0, 1));
    $middleInitial = $request->middle_name ? strtoupper(substr($request->middle_name, 0, 1)) : '';
    $lastName      = strtoupper($request->last_name);
    $extension     = $request->extension_name ? strtoupper($request->extension_name) : '';

    $username = strtolower($firstInitial . $middleInitial . $lastName . $extension);

    // Generate default password (you can customize this)
    $password = bcrypt('dswd12345678'); // hashed password

    // Merge username and password into request data
    $data = $request->all();
    $data['username'] = $username;
    $data['password'] = $password;

    $item->applicants()->create($data);

    return redirect()
      ->route('unfilled_positions.applicants', $id)
      ->with('success', 'Applicant added successfully! Username: ' . $username);
  }

  public function applicants($id)
  {
    $item = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus', 'fundSource', 'applicants'])
      ->findOrFail($id);

    $applicants = $item->applicants;

    // load divisions for the dropdown
    $divisions = Division::orderBy('name')->get();

    return view('content.planning.unfilled_positions.applicants', compact('item', 'applicants', 'divisions'));
  }
}
