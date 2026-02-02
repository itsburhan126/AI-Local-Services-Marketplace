<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'community_category_id',
        'title',
        'slug',
        'content',
        'is_pinned',
        'is_locked',
        'view_count',
        'like_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CommunityCategory::class, 'community_category_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class);
    }
}
