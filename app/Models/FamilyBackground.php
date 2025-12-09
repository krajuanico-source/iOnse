<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyBackground extends Model
{
    use HasFactory;

    protected $table = 'family_backgrounds'; // Table name

    protected $fillable = [
        // Spouse
        'spouse_surname', 'spouse_first_name', 'spouse_middle_name', 'spouse_extension_name', 'mother_maiden_name',
        'spouse_occupation', 'spouse_employer', 'spouse_employer_address', 'spouse_employer_telephone',

        // Father
        'father_surname', 'father_first_name', 'father_middle_name', 'father_extension_name',

        // Mother
        'mother_surname', 'mother_first_name', 'mother_middle_name', 'mother_extension_name',

        // Reference to employee
        'employee_id',
    ];
    
    public function children()
{
    return $this->hasMany(Child::class);
}

    protected $casts = [
        'children' => 'array', // automatically JSON encode/decode
    ];
}
