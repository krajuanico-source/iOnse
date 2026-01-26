<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyBackgroundRequest;
use App\Models\FamilyBackground;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;

class FamilyBackgroundController extends Controller
{
    /**
     * Show the family background form.
     */
    public function edit()
    {
        $family = FamilyBackground::firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        return view('content.profile.family-background', compact('family'));
    }

    /**
     * Update family background and children.
     */
    public function update(FamilyBackgroundRequest $request)
    {
        $userId = Auth::id();

        // Update or create family background
        $family = FamilyBackground::updateOrCreate(
            ['user_id' => $userId],
            array_merge(
                $request->safe()->except(['children', 'deleted_children']),
                ['user_id' => $userId]
            )
        );

        // Delete removed children (secure to user)
        if ($request->filled('deleted_children')) {
            $deletedIds = explode(',', $request->deleted_children);

            Child::where('family_background_id', $family->id)
                ->whereIn('id', $deletedIds)
                ->delete();
        }

        // Create / Update children
        foreach ($request->children ?? [] as $childData) {

            // Skip empty rows
            if (!array_filter($childData)) {
                continue;
            }

            if (!empty($childData['id'])) {
                Child::where('id', $childData['id'])
                    ->where('family_background_id', $family->id)
                    ->update([
                        'first_name'  => $childData['first_name'] ?? null,
                        'middle_name' => $childData['middle_name'] ?? null,
                        'last_name'   => $childData['last_name'] ?? null,
                        'birthday'    => $childData['birthday'] ?? null,
                    ]);
            } else {
                $family->children()->create([
                    'first_name'  => $childData['first_name'] ?? null,
                    'middle_name' => $childData['middle_name'] ?? null,
                    'last_name'   => $childData['last_name'] ?? null,
                    'birthday'    => $childData['birthday'] ?? null,
                ]);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Family Background updated successfully.');
    }
}
