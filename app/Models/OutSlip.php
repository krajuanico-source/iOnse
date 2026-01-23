<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutSlip extends Model
{
  use HasFactory;

  protected $table = 'out_slips';

  protected $fillable = [
    'date',
    'user_id',
    'destination',
    'type_of_slip',
    'purpose',
    'status',
    'approved_by',
  ];

  /**
   * Relationship: The user who approved the slip.
   */
  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  // Employee linked to slip
  public function employee()
  {
    return $this->belongsTo(\App\Models\User::class, 'user_id', 'employee_id');
  }
}
