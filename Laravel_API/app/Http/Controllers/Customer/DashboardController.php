<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Gig;
use App\Models\Banner;
use App\Models\FreelancerBanner;
use App\Models\Review;
use App\Models\RecentlyViewedGig;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        // 1. Fetch Categories (Top level)
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();

        // Subcategories for mega menu
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        // 2. Banners
        // Hero Slider - Using FreelancerBanner model as per user request
        $banners = FreelancerBanner::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        if ($banners->isEmpty()) {
            // Fallback to Banner model if FreelancerBanner is empty
            $banners = Banner::where(function($q) {
                    $q->where('type', 'hero')->orWhereNull('type');
                })
                ->where('status', true)
                ->get();
        }

        // Single Promotional Banner (New)
        $singleBanner = Banner::where('type', 'promo_large')
            ->where('status', true)
            ->first();

        // Left/Right Banners (New)
        $leftBanner = Banner::where('type', 'promo_split')
            ->where('position', 'left')
            ->where('status', true)
            ->first();
        
        $rightBanner = Banner::where('type', 'promo_split')
            ->where('position', 'right')
            ->where('status', true)
            ->first();


        // 3. Flash Sale (New)
        // Assuming we added is_flash_sale to Gigs or have a separate mechanism
        // For now using the column added in migration
        $flashSaleGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_flash_sale', true)
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->where(function($q) {
                $q->whereNull('flash_sale_end_time')
                  ->orWhere('flash_sale_end_time', '>', now());
            })
            ->take(8)
            ->get();

        // 4. Popular Services (by View Count)
        $popularGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->orderBy('view_count', 'desc')
            ->take(8)
            ->get();

        // 5. Recently Viewed (New - Real implementation)
        $recentlyViewed = collect();
        if ($user) {
            $recentlyViewed = RecentlyViewedGig::where('user_id', $user->id)
                ->with(['gig' => function($q) {
                    $q->with(['provider', 'packages' => function($p) {
                        $p->orderBy('price', 'asc');
                    }]);
                }])
                ->orderBy('updated_at', 'desc')
                ->take(8)
                ->get()
                ->pluck('gig')
                ->filter(); // remove nulls if gig was deleted
        }

        // 6. Recently Saved (Favorites) (New)
        $recentlySaved = collect();
        if ($user) {
            // Assuming favorites relation on User model or manual query
            $recentlySaved = $user->favorites() // Assuming morphMany or similar on User
                ->where('favorable_type', Gig::class)
                ->with(['favorable' => function($q) {
                    $q->with(['provider', 'packages' => function($p) {
                        $p->orderBy('price', 'asc');
                    }]);
                }])
                ->latest()
                ->take(8)
                ->get()
                ->pluck('favorable')
                ->filter();
        }

        // 7. What Sparks Your Interest (Based on user interests or random categories)
        // If user has interests, show gigs from those categories
        $interestsGigs = collect();
        // Placeholder logic for interests:
        // if ($user && $user->interests->count() > 0) { ... }
        // Fallback to random high rated gigs
        $interestsGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->inRandomOrder()
            ->take(8)
            ->get();


        // 8. Inspired by Browsing History (New)
        // Logic: Get category of last viewed gig, show more from that category
        $inspiredByHistory = collect();
        if ($user) {
            $lastViewed = RecentlyViewedGig::where('user_id', $user->id)->latest()->first();
            if ($lastViewed && $lastViewed->gig) {
                $inspiredByHistory = Gig::with(['provider', 'packages' => function($query) {
                        $query->orderBy('price', 'asc');
                    }])
                    ->where('category_id', $lastViewed->gig->category_id)
                    ->where('id', '!=', $lastViewed->gig_id)
                    ->where('is_active', true)
                    ->whereIn('status', ['published', 'approved'])
                    ->take(8)
                    ->get();
            }
        }
        
        // 9. New Gigs
        $newGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->latest()
            ->take(8)
            ->get();

        // 10. Popular Subcategories
        $popularSubcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(6)
            ->get();
            
        // Testimonials (Mock for now, or fetch from Reviews if needed)
        $testimonials = [
            [
                'name' => 'Sarah Jenkins',
                'role' => 'Small Business Owner',
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'text' => 'Found an amazing graphic designer in minutes. The quality of work was outstanding!'
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Tech Startup Founder',
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'text' => 'This platform made it so easy to find local developers for our MVP. Highly recommended.'
            ],
            [
                'name' => 'Jessica Williams',
                'role' => 'Marketing Director',
                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'text' => 'The best place to find reliable freelancers. Trust and Safety features gave me peace of mind.'
            ]
        ];

        return view('Customer.dashboard', compact(
            'categories', 
            'subcategories', 
            'banners', 
            'singleBanner',
            'leftBanner',
            'rightBanner',
            'flashSaleGigs',
            'popularGigs', 
            'newGigs', 
            'popularSubcategories',
            'recentlyViewed',
            'recentlySaved',
            'interestsGigs',
            'inspiredByHistory',
            'testimonials'
        ));
    }

    public function gigsBySubcategory($slug)
    {
        // 1. Find Subcategory
        $subcategory = Category::where('slug', $slug)
            ->whereNotNull('parent_id')
            ->firstOrFail();

        // 2. Fetch Gigs
        $query = Gig::with(['provider', 'packages', 'reviews'])
            ->where('category_id', $subcategory->id)
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved']);

        // 3. Sorting
        if (request()->has('sort')) {
            switch (request()->sort) {
                case 'popular':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'rating':
                    $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $gigs = $query->paginate(12);

        // 4. Fetch Categories for Filter (Parent Categories)
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Subcategories for mega menu (if needed by layout)
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        return view('Customer.gigs.index', compact('gigs', 'categories', 'subcategories', 'subcategory'));
    }
}
