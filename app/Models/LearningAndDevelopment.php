<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningAndDevelopment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'inclusive_date_from',
        'inclusive_date_to',
        'number_of_hours',
        'type_of_ld',
        'conducted_by',
        'user_id',
    ];
}
