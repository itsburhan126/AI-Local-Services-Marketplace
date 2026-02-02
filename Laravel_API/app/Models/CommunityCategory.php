<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'type',
        'is_active',
        'order',
    ];

    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
