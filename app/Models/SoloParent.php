<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoloParent extends Model
{
  protected $table = 'solo_parent';
  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'circumstance',
    'circumstance_other',
    'created_on',
    'updated_on'
  ];
}
