<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OtherInformation;
use Illuminate\Support\Facades\Auth;

class OtherInformationController extends Controller
{
    public function index()
    {
        // Fetch existing info or create empty data for current user
        $other = OtherInformation::firstOrNew([
            'user_id' => Auth::id(),
        ]);

        // Pass the variable name that matches your Blade view
        return view('content.profile.other-information', compact('other'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'related_within_third_degree' => 'nullable|string',
            'related_within_third_degree_details' => 'nullable|string',
            'related_within_fourth_degree' => 'nullable|string',
            'related_within_fourth_degree_details' => 'nullable|string',
            'found_guilty_admin_offense' => 'nullable|string',
            'administrative_offense_details' => 'nullable|string',
            'criminally_charged' => 'nullable|string',
            'criminal_date_filed' => 'nullable|date',
            'criminal_status' => 'nullable|string',
            'convicted_of_crime' => 'nullable|string',
            'crime_details' => 'nullable|string',
            'separated_from_service' => 'nullable|string',
            'service_separation_details' => 'nullable|string',
            'candidate_in_election' => 'nullable|string',
            'candidate_in_election_details' => 'nullable|string',
            'resigned_before_election' => 'nullable|string',
            'resigned_before_election_details' => 'nullable|string',
            'immigrant_status' => 'nullable|string',
            'immigrant_country' => 'nullable|string',
            'member_of_indigenous_group' => 'nullable|string',
            'indigenous_group_details' => 'nullable|string',
            'person_with_disability' => 'nullable|string',
            'disability_details' => 'nullable|string',
            'solo_parent' => 'nullable|string',
            'solo_parent_details' => 'nullable|string',
        ]);

        OtherInformation::updateOrCreate(
            ['user_id' => Auth::id()],
            array_merge($request->except('_token'), ['user_id' => Auth::id()])
        );

        return response()->json(['message' => 'Other Information saved successfully!']);
    }
}
