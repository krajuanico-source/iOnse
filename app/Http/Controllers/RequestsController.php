<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\OutSlip;
use App\Models\RequestForm;

class RequestsController extends Controller
{
  /**
   * Display list of leaves.
   */
  public function leave()
  {
    $leaves = Leave::with('employee')
      ->orderByDesc('from_date')
      ->get();

    return view('requests.leave', compact('leaves'));
  }

  /**
   * Display list of outslips.
   */
  public function outslip()
  {
    $outSlips = OutSlip::with('employee')
      ->orderByDesc('date')
      ->get();

    return view('requests.outslip', compact('outSlips'));
  }

  /**
   * Display list of payslips.
   */
  public function payslip()
  {
    return view('requests.payslip');
  }

  /**
   * Display list of certificate/dox request forms.
   */
  public function requestForms()
  {
    $requests = RequestForm::with('employee')
      ->orderByDesc('req_date')
      ->get();

    return view('requests.request_forms', compact('requests'));
  }

  /**
   * Certificate requests (optional).
   */
  public function certificates()
  {
    return view('requests.certificates');
  }
}
