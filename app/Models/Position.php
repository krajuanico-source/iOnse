<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

     protected $fillable = [
        'item_no',
        'office_location_id',
        'division_id',
        'section_id',
        'program',
        'created_at',
        'position_name',
        'abbreviation',
        'parenthetical_title',
        'position_level_id',
        'salary_tranche_id',
        'salary_grade_id',
        'salary_step_id',
        'monthly_rate',
        'designation',
        'special_order',
        'obsu',
        'fund_source_id',
        'employment_status_id',
        'type_of_request',
        'is_mass_hiring',
        'mass_group_id',
        'date_of_publication',
    ];

    public function division() {
        return $this->belongsTo(\App\Models\Division::class);
    }

    public function section() {
        return $this->belongsTo(\App\Models\Section::class);
    }

    public function employmentStatus() {
        return $this->belongsTo(\App\Models\EmploymentStatus::class);
    }

    public function salaryGrade() {
        return $this->belongsTo(\App\Models\SalaryGrade::class);
    }

    public function levelRelation() {
        return $this->belongsTo(\App\Models\PositionLevel::class, 'position_level_id');
    }
    protected $casts = [
    'date_of_publication' => 'date',
];
}
