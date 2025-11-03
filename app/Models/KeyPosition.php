<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyPosition extends Model
{
  protected $table = 'key_positions'; // ✅ your actual table name
  protected $fillable = [
    'title',
    'successor_name',
    'readiness_level',
  ];
}
