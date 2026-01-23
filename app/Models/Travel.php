<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
  protected $table = 'tbl_travel';
  protected $primaryKey = 'id_travel';
  public $timestamps = true;

  protected $fillable = [
    'user_id',
    'date_requested',
    'travel_date',
    'travel_purpose',
    'travel_designation',
    'travel_off_station',
    'travel_destination',
    'travel_number',
    'travel_status',
    'travel_approved_by',
    'travel_date_approved',
    'travel_app_chief',
    'travel_remarks',
    'travel_inc_time',
    'travel_exc_time',
    'travel_prescribed',
    'travel_request_by',
    'file_image',
    'pdf_file',
    'travel_reference_number',
    'status'
  ];
  // Employee relationship
  public function employee()
  {
    return $this->belongsTo(\App\Models\User::class, 'user_id', 'employee_id');
  }
}
