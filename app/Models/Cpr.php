<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CprEmployee; // make sure to import the model

class Cpr extends Model
{
  use HasFactory;

  protected $fillable = [
    'rating_period_start',
    'semester',
    'status',
  ];

  // Define relationship to CprEmployee
  public function employees()
  {
    return $this->hasMany(CprEmployee::class, 'cpr_id');
  }
}
