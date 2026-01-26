<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'target_audience',
        'image',
        'is_sent',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}
