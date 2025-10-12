<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BasicInformationController extends Controller
{
    public function index()
    {
        // Get the logged-in user
        $employee = Auth::user();

        if (!$employee) {
            abort(403, 'Unauthorized access');
        }

        return view('content.profile.basic-information', compact('employee'));
    }

public function update(Request $request)
{
    $employee = auth()->user();

    // ✅ Validate inputs (optional but recommended)
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'password'   => 'nullable|min:8', // Password is optional
    ]);

    // ✅ Update basic info
    $employee->employee_id = $request->employee_id;
    $employee->username = $request->username;
    $employee->first_name = $request->first_name;
    $employee->middle_name = $request->middle_name;
    $employee->last_name = $request->last_name;
    $employee->extension_name = $request->extension_name;
    $employee->birthday = $request->birthday;
    $employee->place_of_birth = $request->place_of_birth;
    $employee->gender = $request->gender;
    $employee->civil_status = $request->civil_status;
    $employee->height = $request->height;
    $employee->weight = $request->weight;
    $employee->blood_type = $request->blood_type;
    $employee->tel_no = $request->tel_no;
    $employee->mobile_no = $request->mobile_no;

    // ✅ Permanent Address
    $employee->perm_region = $request->perm_region;
    $employee->perm_province = $request->perm_province;
    $employee->perm_city = $request->perm_city;
    $employee->perm_barangay = $request->perm_barangay;
    $employee->perm_street = $request->perm_street;
    $employee->perm_house_no = $request->perm_house_no;
    $employee->perm_zipcode = $request->perm_zipcode;

    // ✅ Residence Address
    $employee->res_region = $request->res_region;
    $employee->res_province = $request->res_province;
    $employee->res_city = $request->res_city;
    $employee->res_barangay = $request->res_barangay;
    $employee->res_street = $request->res_street;
    $employee->res_house_no = $request->res_house_no;
    $employee->res_zipcode = $request->res_zipcode;

    // ✅ Handle profile image upload
    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $employee->profile_image = $path;
    }

    // ✅ Handle password update with bcrypt
    if ($request->filled('password')) {
        $employee->password = Hash::make($request->password);
    }

    $employee->save();

    return redirect()->back()->with('success', 'Profile updated successfully!');
}


}
