<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleRequest extends Model
{
    protected $fillable = ['service_id', 'provider_id', 'proposed_discount', 'status'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
