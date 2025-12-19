<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticCopyRequest extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'selections',
    'status',
    'pdf_path',        // ✅ add this
    'signed_pdf_path', // if you also want signed PDF
  ];

  protected $casts = [
    'selections' => 'array',
  ];

  // Add this relationship
  public function user()
  {
    return $this->belongsTo(\App\Models\User::class, 'user_id');
  }
}
