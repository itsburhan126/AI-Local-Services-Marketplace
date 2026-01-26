<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GigExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'gig_id',
        'title',
        'description',
        'price',
        'additional_days',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }
}
