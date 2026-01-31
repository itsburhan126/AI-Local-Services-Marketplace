<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'title',
        'credentials',
        'is_active',
        'mode',
    ];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
    ];
}
