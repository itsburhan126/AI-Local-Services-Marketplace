<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GigOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'provider_id',
        'gig_id',
        'gig_package_id',
        'status',
        'scheduled_at',
        'total_amount',
        'service_fee',
        'commission_amount',
        'provider_amount',
        'payment_status',
        'payment_method',
        'notes',
        'address',
        'extras',
        'delivery_note',
        'delivery_files',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'extras' => 'array',
        'delivery_files' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }

    public function package()
    {
        return $this->belongsTo(GigPackage::class, 'gig_package_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'gig_order_id');
    }
}
