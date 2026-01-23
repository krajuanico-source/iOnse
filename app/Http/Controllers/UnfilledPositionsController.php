<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Division;
use App\Models\Position;

class UnfilledPositionsController extends Controller
{

        public function index()
        {
            $positions = Position::with('salaryGrade', 'employmentStatus', 'fundSource')
                ->selectRaw('COALESCE(mass_group_id, id) as group_id')
                ->selectRaw('MAX(item_no) as item_no')
                ->selectRaw('MAX(position_name) as position_name')
                ->selectRaw('MAX(status) as status')
                ->selectRaw('MAX(salary_grade_id) as salary_grade_id')
                ->selectRaw('COUNT(*) as group_count')
                ->whereIn('status', ['Unfilled','Newly-Created'])
                ->groupByRaw('COALESCE(mass_group_id, id)')
                ->get();

            return view('content.planning.unfilled_positions.index', compact('positions'));
        }

    public function show($groupId)
    {
        
        $position = $this->getPositionByGroup($groupId);

        if (!$position) {
            return redirect()->route('unfilled_positions.index')
                            ->with('error', 'Position not found.');
        }

        // Fetch applicants linked to this position
        $applicants = Applicant::where('position_id', $position->id)->get();

        // Compute next applicant number for the modal
        $lastApplicant = Applicant::where('applicant_no', 'like', "TEMP-11-%")
                                  ->orderBy('applicant_no', 'desc')
                                  ->first();

        $nextSequence = $lastApplicant
            ? str_pad((int)substr($lastApplicant->applicant_no, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        $nextApplicantNo = "TEMP-11-{$nextSequence}";

        $divisions = Division::orderBy('name')->get();

        return view('content.planning.unfilled_positions.show', compact(
            'position',
            'applicants',
            'nextApplicantNo',
            'divisions'
        ));
    }

    public function applicants($groupId)
    {
        $position = $this->getPositionByGroup($groupId);

        if (!$position) {
            return redirect()->route('unfilled_positions.index')
                             ->with('error', 'Position not found.');
        }

        $applicants = Applicant::where('position_id', $position->id)->get();
        $divisions  = Division::orderBy('name')->get();

        // Generate next applicant number for the modal
        $lastApplicant = Applicant::where('applicant_no', 'like', "TEMP-11-%")
                                  ->orderBy('applicant_no', 'desc')
                                  ->first();

        $nextSequence = $lastApplicant
            ? str_pad((int)substr($lastApplicant->applicant_no, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        $nextApplicantNo = "TEMP-11-{$nextSequence}";

        return view('content.planning.unfilled_positions.applicants', compact(
            'position',
            'applicants',
            'divisions',
            'nextApplicantNo'
        ));
    }

    /**
     * Store a new applicant
     */
    public function storeApplicant(Request $request, $groupId)
    {
        $request->validate([
            'applicant_no'     => 'required|string|max:100',
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'last_name'        => 'required|string|max:100',
            'extension_name'   => 'nullable|string|max:50',
            'sex'              => 'required|in:Male,Female,Other',
            'date_of_birth'    => 'required|date',
            'date_applied'     => 'required|date',
            'status'           => 'nullable|string',
            'remarks'          => 'nullable|string|max:255',
            'date_hired'       => 'nullable|date',
            'mobile_no'        => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
        ]);

        // Get all positions for this mass group or single position
        $positionIds = Position::where('mass_group_id', $groupId)
                        ->orWhere('id', $groupId)
                        ->pluck('id');

        if ($positionIds->isEmpty()) {
            return back()->with('error', 'Position not found.');
        }

        // Check for duplicate applicant across the group
        $firstName = trim(strtolower($request->first_name));
        $lastName  = trim(strtolower($request->last_name));

        $existingApplicant = Applicant::whereIn('position_id', $positionIds)
            ->whereRaw('LOWER(TRIM(first_name)) = ?', [$firstName])
            ->whereRaw('LOWER(TRIM(last_name)) = ?', [$lastName])
            ->first();

        if ($existingApplicant) {
            return back()->with('error', 'An applicant with the same first and last name already exists for this position.');
        }

        // Choose the "primary" position ID for this applicant
        $positionId = $positionIds->first(); // usually the first in the group
        

        // Generate username
        $firstInitial  = strtoupper(substr($request->first_name, 0, 1));
        $middleInitial = $request->middle_name ? strtoupper(substr($request->middle_name, 0, 1)) : '';
        $lastNameUpper = strtoupper($request->last_name);
        $extension     = $request->extension_name ? strtoupper($request->extension_name) : '';
        $username = strtolower($firstInitial . $middleInitial . $lastNameUpper . $extension);
        $password = bcrypt('dswd12345678');

        // Auto-generate unique applicant_no
        $lastApplicant = Applicant::where('applicant_no', 'like', "TEMP-11-%")
                                ->orderBy('applicant_no', 'desc')
                                ->first();

        $newSequence = $lastApplicant
            ? str_pad((int)substr($lastApplicant->applicant_no, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        $applicantNo = "TEMP-11-{$newSequence}";

        // Prepare data
        $data = $request->all();
        $data['username']       = $username;
        $data['password']       = $password;
        $data['applicant_no']   = $applicantNo;
        $data['position_id'] = $positionId;

        // Create applicant
        Applicant::create($data);

        return redirect()
            ->route('unfilled_positions.show', $groupId)
            ->with('success', 'Applicant added successfully! Username: ' . $username . ', Applicant No: ' . $applicantNo);
    }

    private function getPositionByGroup($groupId)
    {
        return Position::with('salaryGrade', 'employmentStatus', 'fundSource')
            ->where('mass_group_id', $groupId)
            ->orWhere('id', $groupId)
            ->first();
    }
}
