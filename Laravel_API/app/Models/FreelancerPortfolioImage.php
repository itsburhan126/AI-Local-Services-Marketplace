<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerPortfolioImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_portfolio_id',
        'image_path',
    ];

    public function portfolio()
    {
        return $this->belongsTo(FreelancerPortfolio::class, 'freelancer_portfolio_id');
    }
}
