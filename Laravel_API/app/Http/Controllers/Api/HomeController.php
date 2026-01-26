<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\FreelancerBanner;
use App\Models\Category;
use App\Models\Gig;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'local_service');

        // 1. Banners (Main Slider)
        if ($type === 'freelancer') {
            $banners = FreelancerBanner::where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map(function($banner) {
                    return [
                        'id' => $banner->id,
                        'image_url' => asset('storage/' . $banner->image_path),
                        'title' => $banner->title,
                        'redirect_url' => null, // Or add logic
                        'redirect_type' => null,
                    ];
                });
        } else {
            $banners = Banner::where('status', true)
                ->orderBy('order')
                ->get();
        }

        // 2. Categories
        $categories = Category::where('is_active', true)
            ->where('type', $type) // Filter by type
            ->whereNull('parent_id')
            ->orderBy('order')
            ->take(8)
            ->get();

        // 3. Popular Services / Gigs
        if ($type === 'freelancer') {
            $popularServices = Gig::where('is_active', true)
                ->where('status', 'approved') // Only approved gigs
                ->where('is_featured', true)
                ->with(['provider', 'category', 'packages'])
                ->take(5)
                ->get()
                ->map(function ($gig) {
                    return $this->mapGigToService($gig);
                });
        } else {
            $popularServices = Service::where('is_active', true)
                ->where('is_featured', true)
                ->with(['provider', 'category'])
                ->take(5)
                ->get();
        }

        // 4. Recommended Services / Gigs
        if ($type === 'freelancer') {
            $recommendedQuery = Gig::where('is_active', true)
                ->where('status', 'approved')
                ->with(['provider', 'category', 'packages']);

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
                    ->with(['provider', 'category', 'packages'])
                    ->take(6 - $recommendedServices->count())
                    ->get()
                    ->map(function ($gig) {
                        return $this->mapGigToService($gig);
                    });
                 $recommendedServices = $recommendedServices->merge($moreGigs);
            }

        } else {
            // Existing Local Service Logic
            $recommendedServicesQuery = Service::where('is_active', true)
                ->with(['provider', 'category']);
    
            $hasInterestFilter = false;
            if ($user = Auth::guard('sanctum')->user()) {
                 $interestCategoryIds = $user->interests()
                    ->whereNotNull('category_id')
                    ->pluck('category_id')
                    ->toArray();
                 
                 if (!empty($interestCategoryIds)) {
                     $recommendedServicesQuery->whereIn('category_id', $interestCategoryIds);
                     $hasInterestFilter = true;
                 } else {
                     $recommendedServicesQuery->inRandomOrder();
                 }
            } else {
                $recommendedServicesQuery->inRandomOrder();
            }
    
            $recommendedServices = $recommendedServicesQuery->take(6)->get();
            
            if ($hasInterestFilter && $recommendedServices->count() < 6) {
                 $moreServices = Service::where('is_active', true)
                    ->whereNotIn('id', $recommendedServices->pluck('id'))
                    ->inRandomOrder()
                    ->with(['provider', 'category'])
                    ->take(6 - $recommendedServices->count())
                    ->get();
                 $recommendedServices = $recommendedServices->merge($moreServices);
            }
        }

        // ... rest of the code (Banners, etc.)


        // 5. Single Banner (Simulated for now, or use a specific banner type)
        // We can use the first banner as a "featured" single banner if we want, 
        // or add a 'type' column to banners table later. For now, taking the last one.
        $singleBanner = Banner::where('status', true)->latest()->first();

        // 6. Left-Right Banners (Simulated)
        $promotionalBanners = Banner::where('status', true)->inRandomOrder()->take(2)->get();

        // 7. Recently Viewed (Mocked for now, needs DB tracking table)
        // In a real app, we'd query a RecentlyViewed model.
        $recentlyViewed = Service::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->with(['provider', 'category'])
            ->take(5)
            ->get();

        // 8. Recently Saved (Wishlist)
        // If user is logged in, fetch their wishlist.
        $recentlySaved = [];
        if ($user = Auth::guard('sanctum')->user()) {
            // Assuming a Wishlist model or relation exists. 
            // If not, returning empty list for now to avoid errors.
            // $recentlySaved = $user->wishlist()->with('service')->get();
        }

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

        // 10. Flash Sale
        $flashSale = \App\Models\FlashSale::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_time')->orWhere('start_time', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_time')->orWhere('end_time', '>=', now());
            })
            ->with(['items.service', 'items.service.provider', 'items.service.category'])
            ->first();
            
        $flashSaleData = null;
        if ($flashSale) {
            $flashSaleData = [
                'id' => $flashSale->id,
                'title' => $flashSale->title,
                'end_time' => $flashSale->end_time ? $flashSale->end_time->toIso8601String() : null,
                'bg_color' => $flashSale->bg_color,
                'text_color' => $flashSale->text_color,
                'items' => $flashSale->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->custom_title ?? ($item->service ? $item->service->name : ''),
                        'image' => $item->custom_image 
                            ? url('storage/'.$item->custom_image) 
                            : ($item->service && $item->service->image ? $item->service->image : null),
                        'price' => (float) ($item->service ? $item->service->price : $item->price),
                        'discount_percentage' => (int) $item->discount_percentage,
                        'discounted_price' => (float) ($item->service 
                            ? ($item->service->price * (1 - $item->discount_percentage/100)) 
                            : ($item->price ? $item->price * (1 - $item->discount_percentage/100) : 0)),
                        'service_id' => $item->service_id,
                        'category' => $item->service && $item->service->category ? [
                            'id' => $item->service->category->id,
                            'name' => $item->service->category->name,
                        ] : null,
                        'provider' => $item->service && $item->service->provider ? [
                            'id' => $item->service->provider->id,
                            'name' => $item->service->provider->name,
                            'avatar' => $item->service->provider->avatar,
                        ] : null,
                    ];
                })
            ];
        }

        // 11. Trust & Safety (Static for now)
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

        // 12. Testimonials (Static for now)
        $testimonials = [
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'role' => 'Home Owner',
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'rating' => 5,
                'comment' => 'The service was absolutely amazing! The cleaner was punctual, professional, and left my house sparkling clean. Highly recommended!'
            ],
            [
                'id' => 2,
                'name' => 'Michael Chen',
                'role' => 'Business Owner',
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'rating' => 5,
                'comment' => 'I used the graphic design service for my new startup logo. The designer understood my vision perfectly and delivered within 24 hours.'
            ],
            [
                'id' => 3,
                'name' => 'Emma Wilson',
                'role' => 'Marketing Manager',
                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'rating' => 4,
                'comment' => 'Great experience overall. The booking process was smooth and the service provider was very skilled. Will definitely use again.'
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'banners' => $banners,
                'categories' => $categories,
                'popular_services' => $popularServices,
                'recommended_services' => $recommendedServices,
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
            'price' => $minPrice,
            'discount_price' => null, // Gigs don't have discount price usually
            'rating' => 0, // Implement rating logic if available
            'reviews_count' => 0,
            'provider' => $gig->provider,
            'category' => $gig->category,
            'is_gig' => true, // Flag for frontend if needed
            'service_type' => 'freelancer',
            'packages' => $gig->packages,
        ];
    }
}
