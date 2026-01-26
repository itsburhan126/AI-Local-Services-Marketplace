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

    public function getAvatarAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            return asset('storage/' . $value);
        }
        return $value;
    }
}
