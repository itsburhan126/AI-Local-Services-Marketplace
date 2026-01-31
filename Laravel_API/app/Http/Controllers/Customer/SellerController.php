<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Gig;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    public function show($slug)
    {
        // 1. Find the provider (User) by matching slugified name
        // This is a bit expensive but without a dedicated slug column, it's the way.
        // Alternatively, we could assume the slug IS the name if we enforce unique names.
        
        // Try exact match first (if name has no spaces)
        $user = User::where('name', $slug)->where('role', 'provider')->first();
        
        if (!$user) {
            // Try to find by checking all providers (caching this would be better in prod)
            // or use a LIKE query replacing hyphens with spaces (imperfect but works for simple names)
            $name = str_replace('-', ' ', $slug);
            $user = User::where('name', 'LIKE', $name)
                ->where('role', 'provider')
                ->firstOrFail();
        }

        // 2. Load relationships
        $user->load(['providerProfile', 'reviews.user', 'gigs' => function($q) {
            $q->where('is_active', true)
              ->whereIn('status', ['published', 'approved'])
              ->with('reviews'); // eager load reviews for rating calc
        }]);

        // 3. Aggregate stats
        $totalReviews = $user->gigs->sum(function($gig) {
            return $gig->reviews->count();
        });
        
        $averageRating = 0;
        if ($totalReviews > 0) {
            $totalStars = $user->gigs->sum(function($gig) {
                return $gig->reviews->sum('rating');
            });
            $averageRating = $totalStars / $totalReviews;
        }
        
        // 4. Get active gigs for display
        $gigs = Gig::with(['packages' => function($q) {
                $q->orderBy('price', 'asc');
            }, 'reviews'])
            ->where('provider_id', $user->id)
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->latest()
            ->get();

        // 5. Categories for Navbar (Standard requirement)
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();
            
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        return view('Customer.seller.profile', compact(
            'user', 
            'gigs', 
            'averageRating', 
            'totalReviews',
            'categories',
            'subcategories'
        ));
    }
}
