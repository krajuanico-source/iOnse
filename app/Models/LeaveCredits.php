<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveCredits extends Model
{
  protected $fillable = [
        'employee_id',
        'last_name_id',
        'first_name_id',
        'middle_name_id',
        'extension_name_id',
        'vacation_leave',
        'sick_leave',
        'total_leave',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    use HasFactory;
}
