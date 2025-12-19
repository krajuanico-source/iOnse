<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cpr;

class CprEmployee extends Model
{
  use HasFactory;

  protected $fillable = [
    'cpr_id',
    'employee_id',
    'rating',
    'cpr_file',
    'status',
  ];

  public function cpr()
  {
    return $this->belongsTo(Cpr::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
