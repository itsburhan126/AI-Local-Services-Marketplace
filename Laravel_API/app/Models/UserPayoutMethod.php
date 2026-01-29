<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayoutMethod extends Model
{
    protected $fillable = [
        'user_id', 'payout_method_id', 'field_values', 'is_default'
    ];

    protected $casts = [
        'field_values' => 'array',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payoutMethod()
    {
        return $this->belongsTo(PayoutMethod::class);
    }
}
