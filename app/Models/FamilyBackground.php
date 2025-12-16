<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyBackground extends Model
{
  use HasFactory;

  protected $table = 'family_backgrounds';

  protected $fillable = [
    // spouse
    's_lname',
    's_fname',
    's_mname',
    's_ext',
    's_occ',
    's_emp',
    's_addr',

    // father
    'f_lname',
    'f_fname',
    'f_mname',
    'f_ext',

    // mother
    'm_lname',
    'm_fname',
    'm_mname',
    'm_ext',

    // children
    'children',
  ];

  protected $casts = [
    'children' => 'array', // automatically JSON encode/decode
  ];
}
