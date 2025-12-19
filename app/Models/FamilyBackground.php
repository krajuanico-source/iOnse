<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyBackground extends Model
{
  use HasFactory;

  protected $table = 'family_backgrounds';

  protected $fillable = [
    'user_id',
    // spouse
    'spouse_surname',
    'spouse_first_name',
    'spouse_middle_name',
    'spouse_extension_name',
    'spouse_occupation',
    'spouse_employer',
    'spouse_employer_address',
    'spouse_employer_telephone',

    // father
    'father_surname',
    'father_first_name',
    'father_middle_name',
    'father_extension_name',

    // mother
    'mother_maiden_name',
    'mother_surname',
    'mother_first_name',
    'mother_middle_name',
    'mother_extension_name',

    // children
    'children',
  ];

  protected $casts = [
    'children' => 'array', // automatically JSON encode/decode
  ];
}
