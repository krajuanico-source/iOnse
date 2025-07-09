<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = ['filename', 'status', 'imported_at'];

    protected $casts = [
        'imported_at' => 'datetime',
    ];
}
