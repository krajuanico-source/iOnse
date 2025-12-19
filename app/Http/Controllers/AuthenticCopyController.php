<?php

namespace App\Http\Controllers;

use App\Models\AuthenticCopyRequest;
use App\Models\User;
use App\Notifications\AuthenticCopyRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticCopyController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'selections' => 'required|array|min:1'
    ]);

    $authRequest = AuthenticCopyRequest::create([
      'user_id' => Auth::id(),
      'selections' => $request->selections
    ]);

    // Notify HR users
    $hrUsers = User::where('role', 'HR')->get();
    foreach ($hrUsers as $hr) {
      $hr->notify(
        new AuthenticCopyRequested($authRequest, Auth::user())
      );
    }

    return response()->json([
      'message' => 'Request for authentic copy has been sent to HR.'
    ]);
  }
}
