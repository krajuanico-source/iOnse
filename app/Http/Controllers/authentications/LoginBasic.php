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
      // Login the user
      Auth::attempt(Session::get('pending_login'));
      $user = Auth::user(); // Get logged-in user
      Session::forget(['otp', 'pending_login']); // clear OTP data
      $request->session()->regenerate();

      // Check the user's role
      $role = $user->role; // assuming 'role' column exists in users table

      // Redirect based on role
      switch ($role) {

        case 'HR-Planning':
          return redirect()->route('dashboard-analytics'); // ✅ correct

        case 'HR-Welfare':
          return redirect()->route('dashboard-analytics'); // update to your real route name

        case 'HR-PAS':
          return redirect()->route('dashboard-analytics'); // update to your real route name

        case 'HR-L&D':
          return redirect()->route('dashboard-analytics'); // update to your real route name

        default:
          Auth::logout();
          return redirect()->route('auth-login-basic')->withErrors([
            'login' => 'Unauthorized role.',
          ]);
      }
    }

    return back()->withErrors([
      'otp' => 'Invalid OTP code.',
    ]);
  }
}
