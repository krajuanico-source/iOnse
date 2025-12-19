<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next)
    {
        // Redirect to login if not authenticated
        if (!Auth::check() && !$request->is('auth-login-basic')) {
            return redirect()->route('auth-login-basic');
        }


        // Prevent inactive users from staying logged in
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('auth-login-basic')
                ->withErrors(['login' => 'Your account is inactive.']);
        }

        $response = $next($request);

        // Add headers to prevent caching
        return $response->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma','no-cache')
                        ->header('Expires','Sat, 01 Jan 1990 00:00:00 GMT');
    }
}
