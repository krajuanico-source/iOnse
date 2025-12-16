<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestForm extends Model
{
  use HasFactory;

  protected $table = 'tbl_request';
  protected $primaryKey = 'req_num';
  public $incrementing = true;
  protected $keyType = 'int';

  protected $fillable = [
    'empid',
    'req_date',
    'req_doc',
    'req_period',
    'req_purpose',
    'req_specify',
    'req_mode',
    'req_status',
    'req_date_released',
    'req_incharge',
    'req_date_recieved',
    'req_released_by',
    'scan_file',
    'employee_id', // make sure this exists in your table for the relation
  ];

  /**
   * Relationship: RequestForm belongs to an Employee
   */
  // Employee linked to slip
  public function employee()
  {
    return $this->belongsTo(\App\Models\User::class, 'empid', 'employee_id');
  }
}
