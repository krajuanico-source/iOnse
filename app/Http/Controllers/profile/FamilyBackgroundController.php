<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyBackground;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;

class FamilyBackgroundController extends Controller
{
    /**
     * Show the family background form for the logged-in user.
     */
    public function edit()
    {
        $user_id = Auth::id(); // always get logged-in user
        $family = FamilyBackground::firstOrCreate(['user_id' => $user_id]);
        return view('content.profile.family-background', compact('family'));
    }

    /**
     * Update family background and children.
     */
    public function update(Request $request)
    {
        $user_id = Auth::id();

        // Validate input
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

        // Prepare data to update/create
        $familyData = $request->only([
            'spouse_surname','spouse_first_name','spouse_middle_name','spouse_extension_name',
            'spouse_occupation','spouse_employer','spouse_employer_address','spouse_employer_telephone',
            'father_surname','father_first_name','father_middle_name','father_extension_name',
            'mother_maiden_name','mother_surname','mother_first_name','mother_middle_name','mother_extension_name',
        ]);

        // Ensure user_id is included
        $family = FamilyBackground::updateOrCreate(
            ['user_id' => $user_id],
            array_merge($familyData, ['user_id' => $user_id])
        );

        // Delete removed children
        if ($request->filled('deleted_children')) {
            $deletedIds = explode(',', $request->deleted_children);
            Child::whereIn('id', $deletedIds)->delete();
        }

        // Add or update children
        foreach ($request->children ?? [] as $childData) {

            // Skip if all fields are empty
            if (empty($childData['first_name']) && empty($childData['middle_name']) &&
                empty($childData['last_name']) && empty($childData['birthday'])) {
                continue;
            }

            if (!empty($childData['id'])) {
                // Update existing child
                $child = Child::find($childData['id']);
                if ($child) {
                    $child->update([
                        'first_name' => $childData['first_name'] ?? null,
                        'middle_name'=> $childData['middle_name'] ?? null,
                        'last_name'  => $childData['last_name'] ?? null,
                        'birthday'   => $childData['birthday'] ?? null,
                    ]);
                }
            } else {
                // Create new child
                $family->children()->create([
                    'first_name' => $childData['first_name'] ?? null,
                    'middle_name'=> $childData['middle_name'] ?? null,
                    'last_name'  => $childData['last_name'] ?? null,
                    'birthday'   => $childData['birthday'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Family Background updated successfully.');
    }
}
