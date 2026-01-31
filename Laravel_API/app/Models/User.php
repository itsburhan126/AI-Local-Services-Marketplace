<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'service_rule',
        'status',
        'password',
        'google_id',
        'avatar',
        'fcm_token',
        'wallet_balance',
        'pending_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'provider_id');
    }

    public function gigs()
    {
        return $this->hasMany(Gig::class, 'provider_id');
    }

    public function providerProfile()
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'user_interests');
    }

    public function freelancerInterests()
    {
        return $this->belongsToMany(FreelancerInterest::class, 'freelancer_interest_user');
    }

    public function freelancerPortfolios()
    {
        return $this->hasMany(FreelancerPortfolio::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getAvatarAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            return asset('storage/' . $value);
        }
        return $value;
    }

    public function getProfilePhotoUrlAttribute()
    {
        // 1. Check direct avatar
        if ($this->avatar) {
             return $this->avatar; // Accessor above already handles asset() path
        }

        // 2. Check provider profile logo
        if ($this->relationLoaded('providerProfile') && $this->providerProfile && $this->providerProfile->logo) {
             return asset('storage/' . $this->providerProfile->logo);
        }
        
        // 3. Fallback to Professional UI Avatar
        $name = urlencode($this->name);
        // Using a neutral, professional color scheme (gray/blue)
        return "https://ui-avatars.com/api/?name={$name}&background=f3f4f6&color=374151&size=256&bold=true&font-size=0.4&uppercase=true";
    }
}
