<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicInformation extends Model
{
    protected $fillable = [
    'username', 'employee_id', 'first_name', 'middle_name', 'last_name', 'extension_name',
    'birthday', 'place_of_birth', 'gender', 'civil_status', 'height', 'weight', 'blood_type',
    'tel_no', 'mobile_no', 'email', 'citizenship',
    'perm_country', 'perm_region', 'perm_province', 'perm_city', 'perm_barangay',
    'perm_street', 'perm_house_no', 'perm_village', 'perm_zipcode',
    'res_country', 'res_region', 'res_province', 'res_city', 'res_barangay',
    'res_street', 'res_house_no', 'res_village', 'res_zipcode',
    'password', 'profile_image',
];
}
