<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Position;
use App\Models\SalaryGrade;
use App\Models\SalaryTranche;
use App\Models\SalaryStep;
use App\Models\Division;
use App\Models\OfficeLocation;
use App\Models\EmploymentStatus;
use App\Models\Section;
use App\Models\PositionLevel;
use App\Models\FundSource;

class PositionController extends Controller
{
    // List all positions
    public function index()
    {
        $positions = Position::selectRaw('
                MIN(id) as id,
                item_no,
                position_name,
                abbreviation,
                status,
                is_mass_hiring,
                MIN(date_of_publication) as date_of_publication,
                COUNT(*) as positions_count
            ')
            ->groupBy(
                'item_no',
                'position_name',
                'abbreviation',
                'status',
                'is_mass_hiring'
            )
            ->get();

        return view('content.planning.position', [
            'positions'          => $positions,
            'salaryTranches'     => SalaryTranche::orderBy('tranche_name')->get(),
            'employmentStatuses' => EmploymentStatus::all(),
            'divisions'          => Division::all(),
            'officeLocations'    => OfficeLocation::all(),
            'positionLevels'     => PositionLevel::orderBy('level_name')->get(),
            'fundSources'         => FundSource::all(),
        ]);
    }

    // Store new position(s)
            public function store(Request $request)
        {
            $validated = $request->validate([
                'item_no'              => 'nullable|string|max:255',
                'office_location_id'   => 'nullable|exists:office_locations,id',
                'division_id'          => 'nullable|exists:divisions,id',
                'section_id'           => 'nullable|exists:sections,id',
                'program'              => 'nullable|string|max:255',
                'created_at'           => 'required|date',
                'position_name'        => 'required|string|max:255',
                'abbreviation'         => 'required|string|max:50',
                'parenthetical_title'  => 'nullable|string|max:255',
                'position_level_id'    => 'nullable|exists:position_levels,id',
                'salary_tranche_id'    => 'nullable|exists:salary_tranche,id',
                'salary_grade_id'      => 'nullable|exists:salary_grades,id',
                'salary_step_id'       => 'nullable|exists:salary_step,id',
                'monthly_rate'         => 'nullable|numeric',
                'designation'          => 'nullable|string|max:255',
                'special_order'        => 'nullable|string|max:255',
                'obsu'                 => 'nullable|string|max:255',
                'fund_source_id'       => 'nullable|exists:fund_sources,id',
                'employment_status_id' => 'nullable|exists:employment_statuses,id',
                'type_of_request'      => 'nullable|string|max:50',
                'is_mass_hiring'       => 'required|boolean',
                'positions_count'      => 'nullable|integer|min:1',
                'date_of_publication'  => 'required|date',
            ]);

            $validated['is_mass_hiring'] = (bool) $request->is_mass_hiring;
            $count = $validated['is_mass_hiring'] ? ($validated['positions_count'] ?? 1) : 1;

            $validated['position_name'] = strtoupper($validated['position_name']);
            $validated['abbreviation']  = strtoupper($validated['abbreviation']);

            $massGroupId = $count > 1 ? Str::uuid() : null;

            for ($i = 0; $i < $count; $i++) {
                $validated['mass_group_id'] = $massGroupId;
                Position::create($validated);
            }

            return response()->json([
                'success' => true,
                'created' => $count,
            ]);
        }   



        public function show($id)
    {
        $position = Position::findOrFail($id);

        return response()->json([
            'id' => $position->id,
            'item_no' => $position->item_no,
            'program' => $position->program,
            'created_at' => $position->created_at?->format('Y-m-d'),  // <-- important
            'position_name' => $position->position_name,
            'abbreviation' => $position->abbreviation,
            'parenthetical_title' => $position->parenthetical_title,
            'designation' => $position->designation,
            'special_order' => $position->special_order,
            'obsu' => $position->obsu,
            'fund_source' => $position->fund_source,
            'type_of_request' => $position->type_of_request,
            'employment_status_id' => $position->employment_status_id,
            'office_location_id' => $position->office_location_id,
            'position_level_id' => $position->position_level_id,
            'division_id' => $position->division_id,
            'section_id' => $position->section_id,
            'salary_tranche_id' => $position->salary_tranche_id,
            'salary_grade_id' => $position->salary_grade_id,
            'salary_step_id' => $position->salary_step_id,
            'monthly_rate' => $position->monthly_rate,
            'date_of_publication' => $position->date_of_publication?->format('Y-m-d'),
            'is_mass_hiring' => $position->is_mass_hiring,
            'mass_group_id' => $position->mass_group_id,
            'positions_count' => $position->positions_count ?? 1,
            'status' => $position->status,
        ]);
    }
        // Update position (single or massive)
            public function update(Request $request, $id)
        {
        $validated = $request->validate([
            'item_no'              => 'nullable|string|max:255',
            'office_location_id'   => 'nullable|exists:office_locations,id',
            'division_id'          => 'nullable|exists:divisions,id',
            'section_id'           => 'nullable|exists:sections,id',
            'program'              => 'nullable|string|max:255',
            'created_at'           => 'required|date',
            'position_name'        => 'required|string|max:255',
            'abbreviation'         => 'required|string|max:50',
            'parenthetical_title'  => 'nullable|string|max:255',
            'position_level_id'    => 'nullable|exists:position_levels,id',
            'salary_tranche_id'    => 'nullable|exists:salary_tranche,id',
            'salary_grade_id'      => 'nullable|exists:salary_grades,id',
            'salary_step_id'       => 'nullable|exists:salary_step,id',
            'monthly_rate'         => 'nullable|numeric',
            'designation'          => 'nullable|string|max:255',
            'special_order'        => 'nullable|string|max:255',
            'obsu'                 => 'nullable|string|max:255',
            'fund_source_id'       => 'nullable|exists:fund_sources,id',
            'employment_status_id' => 'nullable|exists:employment_statuses,id',
            'type_of_request'      => 'nullable|string|max:50',
            'positions_count'      => 'nullable|integer|min:1',
            'date_of_publication'  => 'required|date',
        ]);

        $validated['position_name'] = strtoupper($validated['position_name']);
        $validated['abbreviation']  = strtoupper($validated['abbreviation']);

        $position = Position::findOrFail($id);

        if ($position->is_mass_hiring && $position->mass_group_id) {
            // Update all positions in the mass group
            Position::where('mass_group_id', $position->mass_group_id)->update($validated);
        } else {
            $position->update($validated);
        }

        return response()->json(['success' => true]);
    }


    // Delete position
    public function destroy($id)
    {
        $position = Position::findOrFail($id);

        if ($position->is_mass_hiring && $position->mass_group_id) {
            // Delete entire mass group
            Position::where('mass_group_id', $position->mass_group_id)->delete();
        } else {
            $position->delete();
        }

        return response()->json(['success' => true]);
    }

    // Get sections for a division
    public function getSections($divisionId)
    {
        return Section::where('division_id', $divisionId)
            ->select('id', 'name')
            ->get();
    }

    // Get salary grades by tranche
    public function getSalaryGrades($trancheId)
    {
        return SalaryGrade::where('tranche_id', $trancheId)
            ->select('id', 'salary_grade')
            ->orderBy('salary_grade')
            ->get();
    }

    // Get salary steps by grade
    public function getSalarySteps($gradeId)
    {
        return SalaryStep::where('grade_id', $gradeId)
            ->select('id', 'step', 'salary_amount as monthly_rate')
            ->orderBy('step')
            ->get();
    }

    // Get monthly rate
    public function getMonthlyRate(Request $request)
    {
        $rate = SalaryStep::where('id', $request->step_id)
            ->where('grade_id', $request->grade_id)
            ->whereHas('grade', fn($q) => $q->where('tranche_id', $request->tranche_id))
            ->value('salary_amount');

        return response()->json(['monthly_rate' => $rate]);
    }
    // Get mass group count
    public function getMassGroupCount($massGroupId)
    {
        $count = Position::where('mass_group_id', $massGroupId)->count();
        return response()->json(['count' => $count]);
    }
}
