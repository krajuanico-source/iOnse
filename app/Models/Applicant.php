<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
  use HasFactory;

  protected $fillable = [
    'item_number_id',
    'first_name',
    'middle_name',
    'last_name',
    'extension_name',
    'sex',
    'date_of_birth',
    'date_applied',
    'status',
    'remarks',
    'date_hired',
    'examination_date',
    'date_interviewed',
    'mobile_no', // added
    'email',     // added
  ];


  public function itemNumber()
  {
    return $this->belongsTo(ItemNumber::class, 'item_number_id');
  }
}
