<?php

namespace App\Http\Controllers\pas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeesListController extends Controller
{

       public function index()
    {
        $employees = User::with(['division', 'section', 'employmentStatus'])->get();
        return view('content\pas\employeeslist', compact('employees'));
    }
}
