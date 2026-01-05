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
        
        $male   = DB::table('users')->where('gender', 'Male')->count();
        $female = DB::table('users')->where('gender', 'Female')->count();
        

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
            'overallEmployees',
            'activeemployees',
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
