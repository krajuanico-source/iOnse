<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
  use HasFactory;

  protected $table = 'tbl_leave';
  protected $primaryKey = 'leave_no';
  public $incrementing = false; // if leave_no is not auto-increment

  protected $fillable = [
    'user_id',
    'leave_type',
    'leave_type_specify',
    'leave_spent',
    'leave_specify',
    'leave_no_wdays',
    'leave_com',
    'from_date',
    'to_date',
    'leave_credit_bal',
    'leave_avail',
    'leave_approved_by',
    'leave_recommend_status',
    'leave_recommend_by',
    'leave_wpay',
    'leave_wo_pay',
    'leave_remarks',
    'leave_authorized_by',
    'date_applied',
    'date_approved',
    'leave_credit_bal_sick',
    'leave_credit_bal_vac',
    'status',
    'pas_head',
    'datefiled',
    'other_purpose',
  ];

  // Employee relationship
  public function employee()
  {
    return $this->belongsTo(\App\Models\User::class, 'user_id', 'employee_id');
  }

  // Approver relationship
  public function approver()
  {
    return $this->belongsTo(\App\Models\User::class, 'leave_approved_by', 'employee_id');
  }
}
