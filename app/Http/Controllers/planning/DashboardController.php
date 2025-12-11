<?php

namespace App\Http\Controllers\Planning;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use App\Models\ItemNumber;

class DashboardController extends Controller
{

    public function index()
    {
        // === KPIs ===
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
        
        $ageGroups = [
          DB::table('users')->whereBetween('age', [18, 24])->count(),
          DB::table('users')->whereBetween('age', [25, 34])->count(),
          DB::table('users')->whereBetween('age', [35, 44])->count(),
          DB::table('users')->whereBetween('age', [45, 54])->count(),
          DB::table('users')->where('age', '>=', 55)->count(),
        ];

        $divisions = User::with('division')
            ->get()
            ->groupBy(fn($user) => $user->division->division_name ?? 'N/A')
            ->map->count();

        $divisions = DB::table('users')
            ->join('divisions', 'users.division_id', '=', 'divisions.id')
            ->select('divisions.name', DB::raw('count(*) as total'))
            ->groupBy('divisions.name')
            ->orderBy('total', 'desc')
            ->pluck('total', 'name')
            ->toArray(); // convert collection to array

        // $divisions = DB::table('users')
        //     ->select('division', DB::raw('count(*) as total'))
        //     ->groupBy('division')
        //     ->orderBy('total', 'desc')
        //     ->pluck('total', 'division'); // returns collection keyed by division

        $office_locations = DB::table('users')
            ->join('office_locations', 'users.name', '=', 'office_locations.name')
            ->select('office_locations.name', DB::raw('count(*) as total'))
            ->groupBy('office_locations.name')
            ->orderBy('total', 'desc')
            ->pluck('total', 'name')
            ->toArray(); // convert collection to array

        $employment_status = DB::table('users')
            ->select('employment_status_id', DB::raw('count(*) as total'))
            ->groupBy('employment_status_id')
            ->pluck('total', 'employment_status_id');

        return view('content.planning.dashboard', compact(

            'overallEmployees',
            'activeemployees',
            // 'vacancy',
            // 'attritionRate',
            'averageAge',
            'male',
            'female',
            'ageGroups',
            'divisions',
            'office_locations'
            // 'contracts'
        ));
    }
}
