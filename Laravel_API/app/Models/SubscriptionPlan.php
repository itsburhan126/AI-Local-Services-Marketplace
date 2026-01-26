<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'features',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];
}
