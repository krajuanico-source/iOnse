<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level_of_education',
        'school_name',
        'degree_course',
        'from',
        'to',
        'highest_level_earned',
        'year_graduated',
        'scholarship_honors',
    ];

    // Link to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
