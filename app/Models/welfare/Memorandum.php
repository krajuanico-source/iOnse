<?php

namespace App\Models\Welfare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memorandum extends Model
{
    // 👇 This tells Laravel to use the 'memorandums' table
    protected $table = 'memorandums';

    // (Optional) Add fillable fields if you're using mass assignment
    protected $fillable = [
        'issuance_number',
        'subject',
        'award_type',
        'date_of_issuance',
        'file_path',
        'file_version',
        'notes',
    ];
}

