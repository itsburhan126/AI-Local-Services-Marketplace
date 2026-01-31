<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'type',
        'quote',
        'story_content',
        'image_path',
        'avatar_path',
        'service_category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
