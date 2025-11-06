<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentId extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sss_id',
        'gsis_id',
        'pagibig_id',
        'philhealth_id',
        'tin',
        'gov_issued_id',
        'id_number',
        'date_issuance',
        'place_issuance',
    ];
}
