<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyBackground;
use Illuminate\Support\Facades\Auth;

class FamilyBackgroundController extends Controller
{
    // Family Background
    public function edit()
    {
        $employee_id = Auth::id(); // Assuming logged-in employee
        $family = FamilyBackground::firstOrCreate(['employee_id' => $employee_id]);
        return view('content.profile.family-background', compact('family'));
    }

    public function update(Request $request)
{
    $employee_id = Auth::id();

     $validated = $request->validate([
            // SPOUSE
            'spouse_surname'            => 'nullable|string|max:255',
            'spouse_first_name'         => 'nullable|string|max:255',
            'spouse_middle_name'        => 'nullable|string|max:255',
            'spouse_extension_name'     => 'nullable|string|max:20',
            'spouse_occupation'         => 'nullable|string|max:255',
            'spouse_employer'           => 'nullable|string|max:255',
            'spouse_employer_address'   => 'nullable|string|max:255',
            'spouse_employer_telephone' => 'nullable|string|max:50',

            // FATHER
            'father_surname'            => 'nullable|string|max:255',
            'father_first_name'         => 'nullable|string|max:255',
            'father_middle_name'        => 'nullable|string|max:255',
            'father_extension_name'     => 'nullable|string|max:20',

            // MOTHER
            'mother_maiden_name'        => 'nullable|string|max:255',
            'mother_surname'            => 'nullable|string|max:255',
            'mother_first_name'         => 'nullable|string|max:255',
            'mother_middle_name'        => 'nullable|string|max:255',
            'mother_extension_name'     => 'nullable|string|max:20',

            // CHILDREN (ARRAY)
            'children' => 'nullable|array',
            'children.*.id'         => 'nullable|integer|exists:children,id',
            'children.*.first_name' => 'nullable|string|max:255',
            'children.*.middle_name'=> 'nullable|string|max:255',
            'children.*.last_name'  => 'nullable|string|max:255',
            'children.*.birthday'   => 'nullable|date',

            // Deleted Child IDs
            'deleted_children' => 'nullable|string',
        ]);

    // Update family background
    $data = $request->only([
        'spouse_surname','spouse_first_name','spouse_middle_name','spouse_extension_name',
        'spouse_occupation','spouse_employer','spouse_employer_address', 'spouse_employer_telephone',
        'father_surname','father_first_name','father_middle_name','father_extension_name', 'mother_maiden_name',
        'mother_surname','mother_first_name','mother_middle_name','mother_extension_name',
    ]);

    $family = FamilyBackground::updateOrCreate(
        ['employee_id' => $employee_id],
        $data
    );

    // Delete removed children
    if ($request->filled('deleted_children')) {
        $deletedIds = explode(',', $request->deleted_children);
        \App\Models\Child::whereIn('id', $deletedIds)->delete();
    }

            // Add/update children
            $childrenInput = $request->children ?? [];
            foreach ($childrenInput as $key => $childData) {

                // Skip if all required fields are empty
                if (
                    empty($childData['first_name']) &&
                    empty($childData['middle_name']) &&
                    empty($childData['last_name']) &&
                    empty($childData['birthday'])
                ) {
                    continue; // skip saving this child
                }

                if (!empty($childData['id'])) {
                    // Update existing child
                    $child = \App\Models\Child::find($childData['id']);
                    if ($child) {
                        $child->update([
                            'first_name' => $childData['first_name'] ?? null,
                            'middle_name' => $childData['middle_name'] ?? null,
                            'last_name' => $childData['last_name'] ?? null,
                            'birthday' => $childData['birthday'] ?? null,
                        ]);
                    }
                } else {
                    // Create new child
                    $family->children()->create([
                        'first_name' => $childData['first_name'] ?? null,
                        'middle_name' => $childData['middle_name'] ?? null,
                        'last_name' => $childData['last_name'] ?? null,
                        'birthday' => $childData['birthday'] ?? null,
                    ]);
                }
            }


    return redirect()->back()->with('success', 'Family Background updated successfully.');
}
}