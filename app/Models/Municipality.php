<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $table = 'municipalities'; // or 'cities'
    protected $fillable = ['psgc', 'name'];
    public $timestamps = false;
}
