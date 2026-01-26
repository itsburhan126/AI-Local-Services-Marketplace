<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'provider_id',
        'category_id',
        'type',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'duration_minutes',
        'image',
        'gallery',
        'is_active',
        'is_featured',
        'location_type',
        'type',
        'service_type_id',
        'metadata',
        'tags',
    ];

    protected $casts = [
        'gallery' => 'array',
        'metadata' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function packages()
    {
        return $this->hasMany(ServicePackage::class);
    }

    public function extras()
    {
        return $this->hasMany(ServiceExtra::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
