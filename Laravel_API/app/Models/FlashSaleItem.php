<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    use HasFactory;

    protected $fillable = ['flash_sale_id', 'service_id', 'custom_image', 'custom_title', 'discount_percentage', 'price', 'order'];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
