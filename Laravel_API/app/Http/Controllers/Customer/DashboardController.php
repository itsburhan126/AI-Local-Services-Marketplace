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
use App\Models\Interest;
use App\Models\Testimonial;
use App\Models\TrustSafetyItem;
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

        // 7. What Sparks Your Interest
        // Fetch Top Level Categories (Freelancer Categories)
        $interests = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->limit(10)
            ->get();
        
        // Note: We are using Categories as "Interests" for display.
        // The toggle functionality will handle mapping Category -> Interest.
        
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
            
        // Testimonials
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Trust & Safety
        $trustSafetyItems = TrustSafetyItem::where('is_active', true)
            ->orderBy('order')
            ->get();

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
            'interests',
            'inspiredByHistory',
            'testimonials',
            'trustSafetyItems'
        ));
    }

    public function toggleInterest(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Check if we received a category_id (from new UI) or interest_id (legacy)
        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            $category = Category::find($categoryId);
            
            if (!$category) {
                return response()->json(['status' => 'error', 'message' => 'Category not found'], 404);
            }

            // Find or Create Interest for this category
            $interest = Interest::firstOrCreate(
                ['category_id' => $category->id],
                [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->image, // Fallback to category image
                    'is_active' => true
                ]
            );
            $interestId = $interest->id;

        } else {
            $request->validate([
                'interest_id' => 'required|exists:interests,id',
            ]);
            $interestId = $request->interest_id;
        }

        $relation = $user->interests();
        $exists = $relation->where('interest_id', $interestId)->exists();

        if ($exists) {
            $relation->detach($interestId);
            $action = 'removed';
        } else {
            $relation->attach($interestId);
            $action = 'added';
        }

        return response()->json([
            'status' => 'success',
            'action' => $action
        ]);
    }

    public function allInterests()
    {
        // Fetch all top-level categories as interests
        $interests = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        // Pass categories and subcategories for layout dependencies (sidebar/header)
        $categories = $interests; 
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        // Get user's current interest category IDs
        $userInterestCategoryIds = [];
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $userInterestCategoryIds = $user->interests()->pluck('category_id')->toArray();
        }

        return view('Customer.freelancer.interests.index', compact('interests', 'categories', 'subcategories', 'userInterestCategoryIds'));
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

        return view('Customer.freelancer.gigs.index', compact('gigs', 'categories', 'subcategories', 'subcategory'));
    }
}
