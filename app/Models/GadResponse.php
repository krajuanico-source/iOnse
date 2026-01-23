<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GadResponse extends Model
{
  protected $table = 'gad_responses';

  protected $fillable = [
    'user_id',
    'gender',
    'honorifics',
    'other_honorifics',
    'move_member',
    'gfps_twg',
    'desired_mode',
    'submitted_at',

    // Ratings
    'q1',
    'q2',
    'q3',
    'q4',
    'q5',
    'q6',
    'q7',
    'q8',
    'q9',
    'q10',
    'q11',
    'q12',
    'q13',
    'q14',
    'q15',
    'q16',
    'q17',
    'q18',
    'q19',
    'q20',
    'q21',
    'q22',
    'q23',
    'q24',
    'q25',

    // NEW FIELDS
    'gad_challenges',
    'gad_trainings',
    'intervention_modes',
  ];

  protected $casts = [
    'gad_challenges'      => 'array',
    'gad_trainings'       => 'array',
    'intervention_modes'  => 'array',
  ];
}
