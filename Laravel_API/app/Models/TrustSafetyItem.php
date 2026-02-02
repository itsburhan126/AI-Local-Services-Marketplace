<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrustSafetyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'bg_color',
        'text_color',
        'is_active',
        'order',
    ];
}
