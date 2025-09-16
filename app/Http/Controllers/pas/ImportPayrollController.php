<?php

namespace App\Http\Controllers\pas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportPayrollController extends Controller
{
    public function index()
  {
    return view('content.pas.importpayroll');
  }
}
