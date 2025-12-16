<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CprEmployee extends Model
{
  protected $fillable = [
    'employee_id',
    'cpr_id',
    'rating',
  ];

  // Optional relationships
  public function employee()
  {
    return $this->belongsTo(User::class, 'employee_id');
  }

  public function cpr()
  {
    return $this->belongsTo(Cpr::class, 'cpr_id');
  }
}
