<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\BasicInformationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;



class BasicInformationController extends Controller
{
    // Basic Information
    public function index()
    {
        $employee = User::with([
            'permRegion', 'permProvince', 'permCity', 'permBarangay',
            'resRegion', 'resProvince', 'resCity', 'resBarangay'
        ])->findOrFail(Auth::id());

        return view('content.profile.basic-information', compact('employee'));
    }

    public function update(BasicInformationRequest $request)
    {
        $employee = auth()->user();

        $data = $request->validated();

        // Citizenship type logic
        $data['citizenship_type'] = $data['citizenship'] === 'Dual Citizenship' 
            ? $data['citizenship_type'] 
            : null;

        // Password
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Profile image
        if ($request->hasFile('profile_image')) {
            if ($employee->profile_image && Storage::disk('public')->exists($employee->profile_image)) {
                Storage::disk('public')->delete($employee->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $employee->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Basic Information updated successfully!',
            'employee' => $employee
        ]);
    }
}
