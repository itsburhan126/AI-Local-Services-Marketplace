<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerBanner;
use App\Models\Category;
use App\Models\Gig;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Banners (Freelancer Specific)
        $banners = FreelancerBanner::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function($banner) {
                return [
                    'id' => $banner->id,
                    'image' => Str::startsWith($banner->image_path, ['http://', 'https://']) 
                        ? $banner->image_path 
                        : asset('storage/' . $banner->image_path),
                    'title' => $banner->title,
                    'redirect_url' => null, 
                    'redirect_type' => null,
                ];
            });

        // 2. Categories
        $categories = Category::where('is_active', true)
            ->where('type', 'freelancer')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->take(8)
            ->get();

        // 3. Popular Gigs
        $popularServices = Gig::where('is_active', true)
            ->where('status', 'approved')
            ->where('is_featured', true)
            ->with(['provider.providerProfile', 'category', 'packages', 'faqs', 'extras'])
            ->take(5)
            ->get()
            ->map(function ($gig) {
                return $this->mapGigToService($gig);
            });

        // 4. Recommended Gigs
        $recommendedQuery = Gig::where('is_active', true)
            ->where('status', 'approved')
            ->with(['provider.providerProfile', 'category', 'packages', 'faqs', 'extras']);

        $hasInterestFilter = false;
        if ($user = Auth::guard('sanctum')->user()) {
                // Get freelancer interest categories
                $interestCategoryIds = $user->freelancerInterests()
                ->whereNotNull('category_id')
                ->pluck('category_id')
                ->toArray();
                
                if (!empty($interestCategoryIds)) {
                    $recommendedQuery->whereIn('category_id', $interestCategoryIds);
                    $hasInterestFilter = true;
                } else {
                    $recommendedQuery->inRandomOrder();
                }
        } else {
            $recommendedQuery->inRandomOrder();
        }

        $recommendedServices = $recommendedQuery->take(6)->get()->map(function ($gig) {
            return $this->mapGigToService($gig);
        });
        
        // Fallback for freelancer
        if ($hasInterestFilter && $recommendedServices->count() < 6) {
                $moreGigs = Gig::where('is_active', true)
                ->where('status', 'approved')
                ->whereNotIn('id', $recommendedServices->pluck('id'))
                ->inRandomOrder()
                ->with(['provider.providerProfile', 'category', 'packages', 'faqs', 'extras'])
                ->take(6 - $recommendedServices->count())
                ->get()
                ->map(function ($gig) {
                    return $this->mapGigToService($gig);
                });
                $recommendedServices = $recommendedServices->merge($moreGigs);
        }

        // 5. Single Banner (Placeholder or logic if needed)
        // Freelancer specific single banner logic can go here.
        // For now using the last FreelancerBanner as a featured one if needed, or null.
        $singleBanner = null; 

        // 6. Left-Right Banners (Placeholder)
        $promotionalBanners = [];

        // 7. Recently Viewed (Placeholder)
        $recentlyViewed = [];

        // 8. Recently Saved (Wishlist)
        $recentlySaved = [];

        // 9. Referral Campaign
        $referral = null;
        if (Setting::where('key', 'referral_enabled')->value('value') !== '0') {
            $referral = [
                'title' => Setting::where('key', 'referral_title')->value('value') ?? 'Refer a friend & get up to $200',
                'description' => Setting::where('key', 'referral_description')->value('value') ?? 'Invite your friends to try our services.',
                'image' => Setting::where('key', 'referral_image')->value('value') ?? 'https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'link' => Setting::where('key', 'referral_link')->value('value') ?? '/referral',
            ];
        }

        // 10. Flash Sale (If applicable to freelancers)
        $flashSaleData = null; 

        // 11. Trust & Safety
        $trustSafety = [
            [
                'icon' => 'https://img.icons8.com/fluency/96/shield.png',
                'title' => 'Secure Payment',
                'description' => '100% secure payment processing'
            ],
            [
                'icon' => 'https://img.icons8.com/fluency/96/guarantee.png',
                'title' => 'Satisfaction Guarantee',
                'description' => 'We guarantee high quality service'
            ],
            [
                'icon' => 'https://img.icons8.com/fluency/96/customer-support.png',
                'title' => '24/7 Support',
                'description' => 'Dedicated support anytime'
            ]
        ];

        // 12. Testimonials
        $testimonials = [
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'role' => 'Business Owner',
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'rating' => 5,
                'comment' => 'The freelancer I hired was professional and delivered excellent work on time.'
            ],
            [
                'id' => 2,
                'name' => 'Michael Chen',
                'role' => 'Startup Founder',
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'rating' => 5,
                'comment' => 'Found a great developer for my app. Smooth process from start to finish.'
            ],
            [
                'id' => 3,
                'name' => 'Emma Wilson',
                'role' => 'Marketing Lead',
                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'rating' => 4,
                'comment' => 'Great platform to find creative talent. Very satisfied with the results.'
            ]
        ];

        // 13. New Gigs (Recently Created)
        $newServices = Gig::where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->with(['provider.providerProfile', 'category', 'packages', 'faqs', 'extras'])
            ->take(6)
            ->get()
            ->map(function ($gig) {
                return $this->mapGigToService($gig);
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'banners' => $banners,
                'categories' => $categories,
                'popular_services' => $popularServices,
                'recommended_services' => $recommendedServices,
                'new_services' => $newServices,
                'single_banner' => $singleBanner,
                'promotional_banners' => $promotionalBanners,
                'recently_viewed' => $recentlyViewed,
                'recently_saved' => $recentlySaved,
                'referral' => $referral,
                'flash_sale' => $flashSaleData,
                'trust_safety' => $trustSafety,
                'testimonials' => $testimonials,
            ]
        ]);
    }

    private function mapGigToService($gig)
    {
        // Calculate min price from packages
        $minPrice = $gig->packages->min('price') ?? 0;
        
        // Map Gig structure to Service structure for consistent UI
        return [
            'id' => $gig->id,
            'name' => $gig->title, // Service.name = Gig.title
            'slug' => $gig->slug,
            'thumbnail' => !empty($gig->images) ? $gig->images[0] : null,
            'images' => $gig->images,
            'description' => $gig->description,
            'price' => $minPrice,
            'discount_price' => null, 
            'rating' => 0, 
            'reviews_count' => 0,
            'provider' => $gig->provider,
            'category' => $gig->category,
            'is_gig' => true, 
            'service_type' => 'freelancer',
            'packages' => $gig->packages,
            'extras' => $gig->extras,
            'faqs' => $gig->faqs,
            'tags' => $gig->tags,
        ];
    }
}
