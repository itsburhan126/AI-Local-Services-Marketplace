<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdrawal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider_id',
        'amount',
        'method',
        'account_details',
        'status',
        'admin_note',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'account_details' => 'array',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
