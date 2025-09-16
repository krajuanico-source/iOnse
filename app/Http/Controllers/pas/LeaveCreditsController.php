<?php

namespace App\Http\Controllers\pas;

use App\Http\Controllers\Controller;
use App\Models\LeaveCredits;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LeaveCreditsController extends Controller
{

  public function index()
  {

    $leavecredits = LeaveCredits::with('users')->get();
    $users = User::all();

    return view('content.pas.leavecredits', compact('leavecredits', 'users'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
    'id'=> 'required|exists:users,employee_id',
    'last_name_id'=> 'required|exists:users,last_name',
    'first_name_id'=> 'required|exists:users,first_name',
    'middle_name_id'=> 'required|exists:users,middle_name',
    'extension_name_id'=> 'required|exists:users,extension_name',
    ]);

    // Convert values to uppercase before saving
    $validated['last_name_id'] = strtoupper($validated['last_name_id']);
    $validated['first_name_id'] = strtoupper($validated['first_name_id']);
    $validated['middle_name_id'] = strtoupper($validated['middle_name_id']);
    $validated['extension_name_id'] = strtoupper($validated['extension_name_id']);


    LeaveCredits::create($validated);

     return response()->json(['success' => true]);
  }

}
