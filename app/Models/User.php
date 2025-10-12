<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $fillable = [
    'employee_id',
    'first_name',
    'middle_name',
    'last_name',
    'extension_name',
    'employment_status_id',
    'division_id',
    'section_id',
    'username',
    'email',
    'password',
    'gender',
    'role', // ✅ add this line
  ];

  protected $hidden = [
    'password',
  ];

  public function division()
  {
    return $this->belongsTo(Division::class);
  }

  public function section()
  {
    return $this->belongsTo(Section::class);
  }
  public function getSections(Request $request)
  {
    $divisionId = $request->division_id;

    if (!$divisionId) {
      return response()->json([]);
    }

    $sections = Section::where('division_id', $divisionId)->get(['id', 'name']); // or ['id', 'abbreviation'] if you're using abbreviations

    return response()->json($sections);
  }
  public function employmentStatus()
  {
    return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
  }

  // public function qualification()
  // {
  //   return $this->belongsTo(Qualification::class);
  // }
  public function model(array $row)
  {
    // Dump the row to test if import reads it
    Log::info('IMPORTING ROW: ', $row);

    if (strtolower($row[0]) === 'username') return null;

    // ... rest of logic
  }
  
}
