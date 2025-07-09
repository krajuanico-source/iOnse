<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User; // Optional: If you want to count users
use App\Models\Dashboard;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $totalEmployees = User::count();
    $femaleEmployees = User::where('gender', 'Female')->count();
    $maleEmployees = User::where('gender', 'Male')->count();

    return view('content.planning.dashboard', [
        'user' => $user,
        'totalEmployees' => $totalEmployees,
        'femaleEmployees' => $femaleEmployees,
        'maleEmployees' => $maleEmployees,
    ]);
}

}