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
    ];

    protected $casts = [
        'images' => 'array',
        'documents' => 'array',
        'tags' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected $appends = ['is_favorite'];

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
}
