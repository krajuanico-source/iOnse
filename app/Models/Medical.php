<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
  protected $table = 'medical';
  public $timestamps = false; // Using created_on/updated_on

  protected $fillable = [
    'user_id',
    'blood_type',
    'qualified_blood_donation',
    'blood_donation',
    'asthma',
    'autoimmune',
    'autoimmune_other',
    'cancer',
    'cancer_other',
    'diabetes_mellitus',
    'heart_disease',
    'hiv_aids',
    'hypertension',
    'kidney_disease',
    'liver_disease',
    'mental_health',
    'mental_health_other',
    'seizures',
    'health_condition',
    'health_condition_other',
    'maintenance_med',
    'disability_type',
    'disability_cause',
    'created_on',
    'updated_on'
  ];
}
