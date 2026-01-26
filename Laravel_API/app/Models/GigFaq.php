<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GigFaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'gig_id',
        'question',
        'answer',
    ];

    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }
}
