<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HowItWorksStep extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'icon',
        'step_order',
        'is_active',
    ];
}
