<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BasicInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check(); // only logged-in users
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => "required|string|max:255|unique:users,username,$userId",
            'employee_id' => "required|string|max:50|unique:users,employee_id,$userId",
            'password' => 'nullable|string|min:8',
            'citizenship' => 'required|string|in:Filipino,Dual Citizenship',
            'citizenship_type' => 'nullable|string|in:by_birth,by_naturalization',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

            // Optional additional fields
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:10',
            'birthday' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:Male,Female',
            'civil_status' => 'nullable|string|max:50',
            'blood_type' => 'nullable|string|max:5',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'tel_no' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'perm_country' => 'nullable|string|max:100',

            // Address fields
            'perm_region' => 'nullable|string|max:100',
            'perm_province' => 'nullable|string|max:100',
            'perm_city' => 'nullable|string|max:100',
            'perm_barangay' => 'nullable|string|max:100',
            'perm_street' => 'nullable|string|max:255',
            'perm_house_no' => 'nullable|string|max:50',
            'perm_zipcode' => 'nullable|string|max:10',
            'res_region' => 'nullable|string|max:100',
            'res_province' => 'nullable|string|max:100',
            'res_city' => 'nullable|string|max:100',
            'res_barangay' => 'nullable|string|max:100',
            'res_street' => 'nullable|string|max:255',
            'res_house_no' => 'nullable|string|max:50',
            'res_zipcode' => 'nullable|string|max:10',
        ];
    }
}
