<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arta extends Model
{
  use HasFactory;

  protected $fillable = [
    'employee_id',
    'position_id',
    'division_id',
    'section_id',
    'assignment_id',
  ];

  /**
   * Relationships
   */

  // Employee model (if needed)
  public function employee()
  {
    return $this->belongsTo(User::class, 'employee_id');
  }

  // Position
  public function position()
  {
    return $this->belongsTo(Position::class, 'position_id');
  }

  // Division
  public function division()
  {
    return $this->belongsTo(Division::class, 'division_id');
  }

  // Section
  public function section()
  {
    return $this->belongsTo(Section::class, 'section_id');
  }

  // Assignment (office location)
  public function assignment()
  {
    return $this->belongsTo(OfficeLocation::class, 'assignment_id');
  }
}
