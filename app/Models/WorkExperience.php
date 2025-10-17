<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_from',
        'date_to',
        'position_title',
        'department_agency',
        'monthly_salary',
        'salary_grade',
        'status_of_appointment',
        'govt_service',
    ];
}
