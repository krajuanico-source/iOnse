<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonAcademic extends Model
{
    use HasFactory;

    // ✅ If your table is named "non_academics", you can skip this.
    // protected $table = 'non_academics';

    protected $fillable = [
        'recognition',
        'user_id',
    ];

    // ✅ If you want to link this to the User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
