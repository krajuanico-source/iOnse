<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CprEmployee;
use App\Models\User;

class Cpr extends Model
{
  use HasFactory;

  protected $fillable = [
    'rating_period_start',
    'semester',
    'status',
    'requestor_id',
  ];

  protected $attributes = [
    'status' => 'Pending',
  ];

  // CPR → CprEmployee (pivot-like table)
  public function employees()
  {
    return $this->hasMany(CprEmployee::class, 'cpr_id');
  }

  // Requestor (user)
  public function requestor()
  {
    return $this->belongsTo(User::class, 'requestor_id');
  }
}
