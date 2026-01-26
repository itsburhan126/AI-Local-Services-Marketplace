<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'rule_content',
        'is_active',
        'order',
    ];
}
