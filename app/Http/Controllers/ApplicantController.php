<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApplicantController extends Controller
{
  public function updateStatus(Request $request, $id)
  {
    $applicant = Applicant::findOrFail($id);

    $applicant->status = $request->status;
    $applicant->remarks = $request->remarks;
    $applicant->save();

    // ✅ If applicant is hired, create a user record
    if ($request->status === 'Hired') {
      // Check if user already exists (avoid duplicate insert)
      $existingUser = User::where('email', $applicant->email ?? null)
        ->orWhere(function ($query) use ($applicant) {
          $query->where('first_name', $applicant->first_name)
            ->where('last_name', $applicant->last_name);
        })
        ->first();

      if (!$existingUser) {
        // Generate next Employee ID (11-xxxx)
        $latest = User::where('employee_id', 'like', '11-%')
          ->orderByDesc('employee_id')
          ->first();

        if ($latest && preg_match('/11-(\d+)/', $latest->employee_id, $matches)) {
          $nextNumber = str_pad($matches[1] + 1, 4, '0', STR_PAD_LEFT);
        } else {
          $nextNumber = '0001';
        }

        $employee_id = '11-' . $nextNumber;

        // Create new User record
        User::create([
          'employee_id' => $employee_id,
          'first_name' => $applicant->first_name,
          'middle_name' => $applicant->middle_name,
          'last_name' => $applicant->last_name,
          'extension_name' => $applicant->extension_name,
          'gender' => $applicant->sex,
          'email' => $applicant->email ?? strtolower($applicant->first_name . '.' . $applicant->last_name . '@example.com'),
          'username' => strtolower($applicant->first_name . '.' . $applicant->last_name),
          'password' => Hash::make('password123'),
          'status' => 'Active',
          'employment_status_id' => 1, // Default (Probationary)
          'division_id' => $request->division_id,
          'section_id' => $request->section_id,
        ]);
      }
    }

    return redirect()->back()->with('success', 'Applicant status updated successfully!');
  }
}
