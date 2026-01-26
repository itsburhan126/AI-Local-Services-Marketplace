<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'banner_image',
        'start_time',
        'end_time',
        'is_active',
        'bg_color',
        'text_color',
        'default_discount_percentage',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(FlashSaleItem::class)->orderBy('order', 'asc');
    }
}
