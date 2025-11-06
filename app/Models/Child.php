<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = [
        'family_background_id',
        'first_name',
        'middle_name',
        'last_name',
        'birthday',
        'gender',
    ];

    public function familyBackground()
    {
        return $this->belongsTo(FamilyBackground::class);
    }
}
