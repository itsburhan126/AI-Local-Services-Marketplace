<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreelancerInterest extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'category_id',
        'is_active',
        'order',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
