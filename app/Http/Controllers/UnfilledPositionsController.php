<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Division;
use Illuminate\Support\Facades\DB;

class UnfilledPositionsController extends Controller
{
    /**
     * List all unfilled positions
     */
    public function index()
    {
        $positions = DB::table('positions')
            ->leftJoin('salary_grades', 'positions.salary_grade_id', '=', 'salary_grades.id')
            ->leftJoin('employment_statuses', 'positions.employment_status_id', '=', 'employment_statuses.id')
            ->leftJoin('fund_sources', 'positions.fund_source_id', '=', 'fund_sources.id')
            ->select(
                DB::raw('COALESCE(positions.mass_group_id, positions.id) as group_id'),
                DB::raw('MAX(positions.position_name) as position_name'),
                DB::raw('MAX(salary_grades.salary_grade) as salary_grade'),
                DB::raw('MAX(employment_statuses.name) as employment_status'),
                DB::raw('MAX(fund_sources.fund_source) as fund_source'),
                DB::raw('MAX(positions.status) as status')
            )
            ->whereIn('positions.status', ['Unfilled','Newly-Created'])
            ->groupBy(DB::raw('COALESCE(positions.mass_group_id, positions.id)'))
            ->get();

        return view('content.planning.unfilled_positions.index', compact('positions'));
    }

    /**
     * Show a single position and its applicants
     */
    public function show($groupId)
    {
        $position = $this->getPositionByGroup($groupId);

        if (!$position) {
            return redirect()->route('unfilled_positions.index')
                            ->with('error', 'Position not found.');
        }

        // Fetch applicants linked to this position
        $applicants = Applicant::where('item_number_id', $position->id)->get();

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

        $applicants = Applicant::where('item_number_id', $position->id)->get();
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
            'first_name'     => 'required|string|max:100',
            'middle_name'    => 'nullable|string|max:100',
            'last_name'      => 'required|string|max:100',
            'extension_name' => 'nullable|string|max:50',
            'sex'            => 'required|in:Male,Female,Other',
            'date_of_birth'  => 'required|date',
            'date_applied'   => 'required|date',
            'status'         => 'nullable|string',
            'remarks'        => 'nullable|string|max:255',
            'date_hired'     => 'nullable|date',
            'mobile_no'      => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
        ]);

        $position = $this->getPositionByGroup($groupId);

        if (!$position) {
            return back()->with('error', 'Position not found.');
        }

        // Generate username
        $firstInitial  = strtoupper(substr($request->first_name, 0, 1));
        $middleInitial = $request->middle_name ? strtoupper(substr($request->middle_name, 0, 1)) : '';
        $lastName      = strtoupper($request->last_name);
        $extension     = $request->extension_name ? strtoupper($request->extension_name) : '';
        $username = strtolower($firstInitial . $middleInitial . $lastName . $extension);
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
        $data['item_number_id'] = $position->id;
        $data['applicant_no']   = $applicantNo;

        // Create applicant
        Applicant::create($data);

        return redirect()
            ->route('unfilled_positions.show', $groupId)
            ->with('success', 'Applicant added successfully! Username: ' . $username . ', Applicant No: ' . $applicantNo);
    }

    /**
     * Helper function: get a position by mass group or id
     */
    private function getPositionByGroup($groupId)
    {
        return DB::table('positions')
            ->leftJoin('salary_grades', 'positions.salary_grade_id', '=', 'salary_grades.id')
            ->leftJoin('employment_statuses', 'positions.employment_status_id', '=', 'employment_statuses.id')
            ->leftJoin('fund_sources', 'positions.fund_source_id', '=', 'fund_sources.id')
            ->select(
                'positions.*',
                'salary_grades.salary_grade',
                'employment_statuses.name as employment_status',
                'fund_sources.fund_source'
            )
            ->where('positions.mass_group_id', $groupId)
            ->orWhere('positions.id', $groupId)
            ->first();
    }
}
