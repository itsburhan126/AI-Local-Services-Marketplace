<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GigPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gig_id',
        'tier',
        'name',
        'description',
        'price',
        'delivery_days',
        'revisions',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
    ];

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }
}
