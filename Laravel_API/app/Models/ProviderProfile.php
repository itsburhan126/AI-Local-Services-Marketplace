<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'about',
        'logo',
        'cover_image',
        'mode',
        'is_verified',
        'documents',
        'commission_rate',
        'address',
        'country',
        'languages',
        'skills',
        'latitude',
        'longitude',
        'rating',
        'reviews_count',
        'seller_level',
    ];

    protected $casts = [
        'documents' => 'array',
        'is_verified' => 'boolean',
        'languages' => 'array',
        'skills' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
