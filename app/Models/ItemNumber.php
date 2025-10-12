<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemNumber extends Model
{
  use HasFactory;

  protected $fillable = [
    'item_number',
    'position_id',
    'salary_grade_id',
    'employment_status_id',
    'fund_source_id',
    'stature',
    'date_posting',
    'date_end_submission'
  ];

  public function position()
  {
    return $this->belongsTo(Position::class, 'position_id');
  }

  public function salaryGrade()
  {
    return $this->belongsTo(SalaryGrade::class, 'salary_grade_id');
  }

  public function employmentStatus()
  {
    return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
  }

  public function fundSource()
  {
    return $this->belongsTo(FundSource::class, 'fund_source_id');
  }

  public function applicants()
  {
    return $this->hasMany(Applicant::class, 'item_number_id');
  }
}
