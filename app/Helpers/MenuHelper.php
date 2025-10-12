<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('userCanView')) {
  function userCanView($menuItem)
  {
    $user = Auth::user();
    if (!$user) {
      return false;
    }

    // If no roles key, allow by default
    if (!isset($menuItem->roles) || empty($menuItem->roles)) {
      return true;
    }

    return in_array($user->role, $menuItem->roles);
  }
}
