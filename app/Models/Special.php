<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Special extends Model
{
  use HasFactory;

  protected $table = 'tbl_special';
  protected $primaryKey = 'id_special';

  protected $fillable = [
    'user_id',
    'special_ref',
    'special_subject',
    'special_from_date',
    'special_to_date',
    'special_date_request',
    'special_purpose',
    'special_number',
    'special_date_approve',
    'special_approve_by',
    'special_to',
    'special_from',
    'special_requested_by',
    'status',
    'file_image',
    'sp_section',
    'sp_venue',
    'training_type',
    'pdf_file',
  ];

  public $timestamps = true;
}
