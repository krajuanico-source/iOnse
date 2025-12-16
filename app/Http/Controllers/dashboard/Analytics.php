<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use App\Models\ItemNumber;

class Analytics extends Controller
{

    public function index()
    {

        $overallEmployees = DB::table('users')->count();
        $activeemployees  = DB::table('users')->where('status', 'Active')->count();
        // $vacancy          = DB::table('users')->where('status', 'Vacant')->count();

        // If you store birthdate instead of age:
        // $averageAge = DB::table('users')->selectRaw('AVG(TIMESTAMPDIFF(YEAR, birthdate, CURDATE())) as avg_age')->value('avg_age');
        // Otherwise if you have age column:
        $averageAge = DB::table('users')->avg('age') ?? 0;

        // attrition example: (leavers in last 12 months / avg headcount) * 100
        // $leaversLastYear = DB::table('users')
        //     ->where('status', 'Left')
        //     ->whereBetween('left_at', [now()->subYear(), now()])
        //     ->count();
        // $attritionRate = $overallEmployees > 0 ? round(($leaversLastYear / $overallEmployees) * 100, 2) : 0;

        // === Charts ===
        
        $male   = DB::table('users')->where('gender', 'Male')->count();
        $female = DB::table('users')->where('gender', 'Female')->count();
        
        // === Charts ===
        $ageGroups = ['Under 25', '25–34', '35–44', '45–54', 'Over 55'];

        // Initialize arrays with zeros
        $maleAgeData = array_fill(0, count($ageGroups), 0);
        $femaleAgeData = array_fill(0, count($ageGroups), 0);

        // Fetch users
        $users = DB::table('users')->select('age', 'gender')->get();

        foreach ($users as $user) {
            $age = $user->age;
            $gender = $user->gender;

            // Determine age group index
            if ($age < 25) $index = 0;
            elseif ($age < 35) $index = 1;
            elseif ($age < 45) $index = 2;
            elseif ($age < 55) $index = 3;
            else $index = 4;

            // Increment count based on gender
            if ($gender === 'Male') $maleAgeData[$index]++;
            elseif ($gender === 'Female') $femaleAgeData[$index]++;
        }


        $divisions = User::with('division')
            ->get()
            ->groupBy(fn($user) => $user->division->division_name ?? 'N/A')
            ->map->count();

        $divisions = DB::table('users')
            ->join('divisions', 'users.division_id', '=', 'divisions.id')
            ->select('divisions.abbreviation', DB::raw('count(*) as total'))
            ->groupBy('divisions.abbreviation')
            ->orderBy('total', 'desc')
            ->pluck('total', 'abbreviation')
            ->toArray(); // convert collection to array

        // $divisions = DB::table('users')
        //     ->select('division', DB::raw('count(*) as total'))
        //     ->groupBy('division')
        //     ->orderBy('total', 'desc')
        //     ->pluck('total', 'division'); // returns collection keyed by division

        $office_locations = DB::table('users')
            ->join('office_locations', 'users.office_location', '=', 'office_locations.id') // join on ID
            ->select('office_locations.abbreviation', DB::raw('COUNT(*) as total'))
            ->groupBy('office_locations.abbreviation')
            ->orderBy('total', 'desc')
            ->pluck('total', 'office_locations.abbreviation')
            ->toArray();



        $employment_status = DB::table('users')
        ->join('employment_statuses', 'users.employment_status_id', '=', 'employment_statuses.id')
        ->select('employment_statuses.name', DB::raw('COUNT(*) as total'))
        ->groupBy('employment_statuses.name')
        ->orderBy('total', 'desc')
        ->pluck('total', 'name')
        ->toArray();

        // Fetch all employment statuses
        $statuses = DB::table('employment_statuses')->pluck('name')->toArray();

        // Initialize arrays
        $malePerStatus = [];
        $femalePerStatus = [];

        foreach ($statuses as $status) {
            $maleCount = DB::table('users')
                ->join('employment_statuses', 'users.employment_status_id', '=', 'employment_statuses.id')
                ->where('employment_statuses.name', $status)
                ->where('gender', 'Male')
                ->count();

            $femaleCount = DB::table('users')
                ->join('employment_statuses', 'users.employment_status_id', '=', 'employment_statuses.id')
                ->where('employment_statuses.name', $status)
                ->where('gender', 'Female')
                ->count();

            $malePerStatus[] = $maleCount;
            $femalePerStatus[] = $femaleCount;
        }



        return view('content.planning.dashboard', compact(
            'maleAgeData',
            'femaleAgeData',
            'ageGroups',
            'overallEmployees',
            'activeemployees',
            'averageAge',
            'divisions',
            'office_locations',
            'employment_status',
            'male',
            'female',
            'statuses',       
            'malePerStatus',  
            'femalePerStatus' 
        ));

    }
}
