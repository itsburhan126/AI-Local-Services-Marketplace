<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'price',
        'additional_days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
