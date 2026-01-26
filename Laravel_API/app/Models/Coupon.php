<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'start_date',
        'expiry_date',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
}
