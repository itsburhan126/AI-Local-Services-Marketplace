<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider_id',
        'category_id',
        'service_type_id',
        'title',
        'slug',
        'description',
        'thumbnail_image',
        'images',
        'video',
        'documents',
        'tags',
        'metadata',
        'is_active',
        'is_featured',
        'status',
        'view_count',
        'admin_note',
        'is_flash_sale',
        'discount_percentage',
        'flash_sale_end_time',
    ];

    protected $casts = [
        'images' => 'array',
        'documents' => 'array',
        'tags' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_flash_sale' => 'boolean',
        'flash_sale_end_time' => 'datetime',
    ];

    protected $appends = ['is_favorite', 'rating', 'reviews_count'];

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
        return $this->hasMany(GigPackage::class);
    }

    public function faqs()
    {
        return $this->hasMany(GigFaq::class);
    }

    public function extras()
    {
        return $this->hasMany(GigExtra::class);
    }

    public function relatedTags()
    {
        return $this->belongsToMany(Tag::class, 'gig_tag');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    public function getIsFavoriteAttribute()
    {
        if (auth('sanctum')->check()) {
            return $this->favorites()->where('user_id', auth('sanctum')->id())->exists();
        }
        return false;
    }

    public function getRatingAttribute()
    {
        // Check if the aggregate was loaded via withAvg
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            return round($this->attributes['reviews_avg_rating'], 1);
        }
        
        // Use loaded relation if available
        if ($this->relationLoaded('reviews')) {
            return round($this->reviews->avg('rating') ?? 0, 1);
        }

        // Fallback: Calculate from relation
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute()
    {
        // Check if the count was loaded via withCount
        if (array_key_exists('reviews_count', $this->attributes)) {
            return $this->attributes['reviews_count'];
        }

        // Use loaded relation if available
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }

        return $this->reviews()->count();
    }
}
