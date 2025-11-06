<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsEligibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'eligibility',
        'rating',
        'exam_date',
        'exam_place',
        'license_number',
        'license_validity',
    ];
}
