<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'provider_id',
        'service_id',
        'gig_id',
        'gig_package_id',
        'status',
        'scheduled_at',
        'total_amount',
        'commission_amount',
        'provider_amount',
        'payment_status',
        'payment_method',
        'address',
        'coordinates',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'coordinates' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }

    public function package()
    {
        return $this->belongsTo(GigPackage::class, 'gig_package_id');
    }
}
