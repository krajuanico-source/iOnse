<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }


  public function store(Request $request)
  {
    $request->validate([
      'login' => 'required|string',
      'password' => 'required|string',
    ]);

    $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $credentials = [
      $loginType => $request->login,
      'password' => $request->password,
    ];

    if (Auth::validate($credentials)) {
      Session::put('pending_login', $credentials);

      // Generate OTP
      $otp = rand(100000, 999999);
      Session::put('otp', $otp);

      // Get user email
      $user = \App\Models\User::where($loginType, $request->login)->first();

      // Send OTP to email
      Mail::to($user->email)->send(new OtpMail($otp));

      return redirect()->route('otp.form');
    }

    return back()->withErrors([
      'login' => 'Invalid credentials.',
    ])->onlyInput('login');
  }

  public function showOtpForm()
  {
    if (!Session::has('pending_login')) {
      return redirect()->route('auth-login-basic');
    }

    return view('content.authentications.auth-otp');
  }

  public function verifyOtp(Request $request)
  {
    $request->validate([
      'otp' => 'required|numeric',
    ]);

    if ($request->otp == Session::get('otp')) {
      Auth::attempt(Session::get('pending_login')); // login user
      Session::forget(['otp', 'pending_login']);     // clear OTP data
      $request->session()->regenerate();

      return redirect()->route('dashboard-analytics');
    }

    return back()->withErrors([
      'otp' => 'Invalid OTP code.',
    ]);
  }
}
