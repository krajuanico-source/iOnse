<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoluntaryWork extends Model
{
    use HasFactory;

    protected $table = 'voluntary_works';

    protected $fillable = [
        'user_id',
        'organization_name',
        'date_from',
        'date_to',
        'number_of_hours',
        'position_nature_of_work',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
