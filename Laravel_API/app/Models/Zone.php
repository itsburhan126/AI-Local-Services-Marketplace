<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'name',
        'coordinates',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
