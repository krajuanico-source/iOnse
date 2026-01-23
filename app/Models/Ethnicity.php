<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
  // Use your singular table
  protected $table = 'ethnicity';

  // Disable default timestamps since your table uses created_on/updated_on
  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'ethnicity',
    'ethnicity_other',
    'household_count',
    'zero_above',
    'six_above',
    'eighteen_above',
    'forty_six_above',
    'sixty_above',
    'children_still_studying',
    'special_needs',
    'description',
    'living_condition',
    'living_condition_other',
    'created_on',
    'updated_on'
  ];
}
