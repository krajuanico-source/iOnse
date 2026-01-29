<?php

namespace App\Http\Controllers\Authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;

class LoginBasic extends Controller
{
    /**
     * Show login page
     */
    public function index()
    {
        return view('content.authentications.auth-login-basic');
    }

    /**
     * Handle login credentials and send OTP
     */
    public function store(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        // Validate credentials without logging in
        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'login' => 'Invalid credentials.',
            ])->onlyInput('login');
        }

        $user = User::where($loginType, $request->login)->first();

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'login' => 'Your account is inactive. Please contact admin.',
            ])->onlyInput('login');
        }

        // Generate OTP
        $otp = random_int(100000, 999999);

        // Store temporary login data in session
        Session::put([
            'pending_login'  => $credentials,
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'otp_user_id'    => $user->id,
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('otp.form')->with('success', 'OTP has been sent to your email.');
    }

    /**
     * Show OTP form
     */
    public function showOtpForm()
    {
        if (!Session::has('pending_login')) {
            return redirect()->route('auth-login-basic')
                ->withErrors(['login' => 'Session expired. Please login again.']);
        }

        return view('content.authentications.auth-otp');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if (!Session::has('otp')) {
            return redirect()->route('auth-login-basic')
                ->withErrors(['login' => 'Session expired. Please login again.']);
        }

        // Check if OTP expired
        if (now()->greaterThan(Session::get('otp_expires_at'))) {
            Session::flush();
            return redirect()->route('auth-login-basic')
                ->withErrors(['login' => 'OTP expired. Please login again.']);
        }

        // Check if OTP matches
        if ($request->otp != Session::get('otp')) {
            return back()->withErrors([
                'otp' => 'Invalid OTP code.',
            ]);
        }

        $pendingLogin = Session::get('pending_login');

        // Login user
        Auth::attempt($pendingLogin);
        $request->session()->regenerate();

        // Clear OTP session data
        Session::forget(['otp', 'otp_expires_at', 'pending_login', 'otp_user_id']);

        // Redirect to a single dashboard after login
        return redirect()->route('content.planning.dashboard');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth-login-basic');
    }
}
