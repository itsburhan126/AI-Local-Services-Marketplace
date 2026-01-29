<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutMethod extends Model
{
    protected $fillable = [
        'name', 'logo', 'description', 'fields', 'is_active', 
        'min_amount', 'max_amount', 'processing_time_days'
    ];

    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
    ];

    public function userPayoutMethods()
    {
        return $this->hasMany(UserPayoutMethod::class);
    }
}
