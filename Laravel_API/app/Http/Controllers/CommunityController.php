<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityCategory;
use App\Models\ForumPost;
use App\Models\Event;

class CommunityController extends Controller
{
    public function index()
    {
        $categories = CommunityCategory::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $recentDiscussions = ForumPost::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();
            
        $upcomingEvents = Event::where('is_active', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(3)
            ->get();
            
        return view('pages.community.index', compact('categories', 'recentDiscussions', 'upcomingEvents'));
    }
}
