<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'type',
        'price',
        'delivery_days',
        'revisions',
        'description',
        'source_code',
        'commercial_use',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'source_code' => 'boolean',
        'commercial_use' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
