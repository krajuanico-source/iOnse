<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
  use HasFactory;

  protected $fillable = [
    'position_name',
    'abbreviation',
    'status',
  ];

  // Relationships
  public function salaryGrade()
  {
    return $this->belongsTo(SalaryGrade::class);
  }

  public function division()
  {
    return $this->belongsTo(Division::class);
  }

  public function section()
  {
    return $this->belongsTo(Section::class);
  }

  public function employmentStatus()
  {
    return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
  }

  public function positionLevel()
  {
    return $this->belongsTo(PositionLevel::class);
  }
  public function qualifications()
  {
    return $this->belongsToMany(Qualification::class, 'position_qualification');
  }
}
