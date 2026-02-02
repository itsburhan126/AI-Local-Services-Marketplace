<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'community_category_id',
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'is_online',
        'meeting_link',
        'image',
        'max_attendees',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_online' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CommunityCategory::class, 'community_category_id');
    }

    public function attendees()
    {
        return $this->hasMany(EventAttendee::class);
    }
}
