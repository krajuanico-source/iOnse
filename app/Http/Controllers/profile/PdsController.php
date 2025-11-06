<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

// Import all your profile-related models
use App\Models\FamilyBackground;
use App\Models\Education;
use App\Models\CsEligibility;
use App\Models\WorkExperience;
use App\Models\VoluntaryWork;
use App\Models\LearningAndDevelopment;
use App\Models\Reference;
use App\Models\GovernmentId;
use App\Models\NonAcademic;
use App\Models\Organization;
use App\Models\Skill;
use App\Models\OtherInformation;

class PdsController extends Controller
{
    public function generate()
    {
        $userId = Auth::id();

        // Fetch all user-related sections
        $family = FamilyBackground::where('employee_id', $userId)->first();
        $education = Education::where('user_id', $userId)->get();
        $eligibility = CsEligibility::where('user_id', $userId)->get();
        $work = WorkExperience::where('user_id', $userId)->get();
        $voluntary = VoluntaryWork::where('user_id', $userId)->get();
        $learning = LearningAndDevelopment::where('user_id', $userId)->get();
        $references = Reference::where('user_id', $userId)->get();
        $governmentIds = GovernmentId::where('user_id', $userId)->first();
        $nonAcademic = NonAcademic::where('user_id', $userId)->get();
        $organization = Organization::where('user_id', $userId)->get();
        $skills = Skill::where('user_id', $userId)->get();
        $other = OtherInformation::where('user_id', $userId)->first();

        // Generate PDF
        $pdf = Pdf::loadView('content.profile.pds-template', compact(
            'family',
            'education',
            'eligibility',
            'work',
            'voluntary',
            'learning',
            'references',
            'governmentIds',
            'nonAcademic',
            'organization',
            'skills',
            'other'
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('PDS-' . ($basicInfo->last_name ?? 'User') . '.pdf');
    }
}
