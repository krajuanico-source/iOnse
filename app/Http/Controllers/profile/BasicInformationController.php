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
        $employee = User::with([
            'permRegion', 'permProvince', 'permCity', 'permBarangay',
            'resRegion', 'resProvince', 'resCity', 'resBarangay'
        ])->findOrFail(Auth::id());

        return view('content.profile.basic-information', compact('employee'));
    }

   public function update(Request $request)
{
    $employee = auth()->user();

    // Validate basic fields
    $request->validate([
        'first_name'   => 'required|string|max:255',
        'last_name'    => 'required|string|max:255',
        'username'     => 'required|string|max:255|unique:users,username,' . $employee->id,
        'employee_id'  => 'required|string|max:50|unique:users,employee_id,' . $employee->id,
        'password'     => 'nullable|min:8',
        'citizenship'  => 'required|string|in:Filipino,Dual Citizenship',
        'citizenship_type' => 'nullable|string|in:by_birth,by_naturalization',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // limit 10MB
    ]);

    // Basic Information
    $employee->first_name = $request->first_name;
    $employee->middle_name = $request->middle_name;
    $employee->last_name = $request->last_name;
    $employee->extension_name = $request->extension_name;
    $employee->username = $request->username;
    $employee->employee_id = $request->employee_id;
    $employee->birthday = $request->birthday;
    $employee->place_of_birth = $request->place_of_birth;
    $employee->gender = $request->gender;
    $employee->civil_status = $request->civil_status;
    $employee->blood_type = $request->blood_type;
    $employee->height = $request->height;
    $employee->weight = $request->weight;
    $employee->age = $request->age;
    $employee->tel_no = $request->tel_no;
    $employee->mobile_no = $request->mobile_no;
    $employee->perm_country = $request->perm_country;
    $employee->citizenship = $request->citizenship;
    $employee->citizenship_type = $request->citizenship === 'Dual Citizenship' ? $request->citizenship_type : null;

    // Permanent Address
    $employee->perm_region = $request->perm_region;
    $employee->perm_province = $request->perm_province;
    $employee->perm_city = $request->perm_city;
    $employee->perm_barangay = $request->perm_barangay;
    $employee->perm_street = $request->perm_street;
    $employee->perm_house_no = $request->perm_house_no;
    $employee->perm_zipcode = $request->perm_zipcode;

    // Residence Address
    $employee->res_region = $request->res_region;
    $employee->res_province = $request->res_province;
    $employee->res_city = $request->res_city;
    $employee->res_barangay = $request->res_barangay;
    $employee->res_street = $request->res_street;
    $employee->res_house_no = $request->res_house_no;
    $employee->res_zipcode = $request->res_zipcode;

    // ✅ Handle profile image upload
    if ($request->hasFile('profile_image')) {
        // Delete old image if exists
        if ($employee->profile_image && file_exists(storage_path('app/public/' . $employee->profile_image))) {
            unlink(storage_path('app/public/' . $employee->profile_image));
        }

        // Store new image
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $employee->profile_image = $path;
    }

    // Password update
    if ($request->filled('password')) {
        $employee->password = Hash::make($request->password);
    }

    $employee->save();

    if ($request->ajax()) {
        return response()->json(['success' => true]);
    }

    return redirect()->back()->with('success', 'Profile updated successfully!');
}


}
