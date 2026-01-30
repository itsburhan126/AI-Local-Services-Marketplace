<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Gig;
use App\Models\Banner;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch top-level categories
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();

        // Fetch subcategories grouped by parent_id for the mega menu
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        // Fetch Banners
        $banners = Banner::where('status', true)->orWhere('status', 'active')->get();

        // Fetch recommended gigs (Personalized or General)
        $recommendedGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Fetch Popular Gigs (by views or rating)
        $popularGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->orderBy('view_count', 'desc')
            ->take(8)
            ->get();

        // Fetch New Gigs
        $newGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->latest()
            ->take(8)
            ->get();

        // Fetch popular subcategories
        $popularSubcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        // Mock Recently Viewed (using Random for now)
        $recentlyViewed = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(5)
            ->get();

        // Mock Recently Saved (using Random for now)
        $recentlySaved = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(5)
            ->get();

        // Mock Inspired by History (using Random for now)
        $inspiredByHistory = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(5)
            ->get();
            
        // Mock Interests
        $interests = [
            ['id' => 1, 'name' => 'Web Design', 'icon' => 'code'],
            ['id' => 2, 'name' => 'Logo Design', 'icon' => 'brush'],
            ['id' => 3, 'name' => 'SEO', 'icon' => 'search'],
            ['id' => 4, 'name' => 'Translation', 'icon' => 'translate'],
            ['id' => 5, 'name' => 'Video Editing', 'icon' => 'movie'],
            ['id' => 6, 'name' => 'Data Entry', 'icon' => 'keyboard'],
            ['id' => 7, 'name' => 'Voice Over', 'icon' => 'mic'],
            ['id' => 8, 'name' => 'Social Media', 'icon' => 'share'],
            ['id' => 9, 'name' => 'Illustration', 'icon' => 'edit'],
        ];
        
        // Mock Testimonials
        $testimonials = [
            [
                'name' => 'Sarah Jenkins',
                'role' => 'Small Business Owner',
                'content' => 'Found an amazing web developer who transformed my online store. Sales have doubled since the redesign!',
                'rating' => 5,
                'image' => 'https://i.pravatar.cc/150?u=1'
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Startup Founder',
                'content' => 'The quality of freelance talent here is exceptional. We built our entire MVP using developers from this platform.',
                'rating' => 5,
                'image' => 'https://i.pravatar.cc/150?u=2'
            ],
            [
                'name' => 'Emily Rodriguez',
                'role' => 'Marketing Director',
                'content' => 'Quick turnaround and professional results. My go-to place for all graphic design needs.',
                'rating' => 4,
                'image' => 'https://i.pravatar.cc/150?u=3'
            ],
            [
                'name' => 'David Kim',
                'role' => 'Content Creator',
                'content' => 'Video editors here are top-notch. Saved me hours of work every week.',
                'rating' => 5,
                'image' => 'https://i.pravatar.cc/150?u=4'
            ],
        ];

        // Mock Flash Sale (Using existing gigs for items)
        $flashSale = [
            'title' => 'Flash Sale',
            'endTime' => now()->addHours(24),
            'items' => $popularGigs->take(4) 
        ];

        // Mock Single Banner
        $singleBanner = [
            'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
            'title' => 'Upgrade your business',
            'subtitle' => 'Get verified pro services today',
            'link' => '#'
        ];

        // Mock Promotional Banners (Left/Right)
        $promotionalBanners = [
            [
                'image' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'title' => 'Logo Design',
                'subtitle' => 'Build your brand',
                'link' => '#'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'title' => 'SEO Services',
                'subtitle' => 'Rank higher',
                'link' => '#'
            ]
        ];

        return view('Customer.dashboard', compact(
            'categories', 
            'subcategories', 
            'popularSubcategories', 
            'recommendedGigs', 
            'banners', 
            'popularGigs', 
            'newGigs',
            'recentlyViewed',
            'recentlySaved',
            'inspiredByHistory',
            'interests',
            'testimonials',
            'flashSale',
            'singleBanner',
            'promotionalBanners'
        ));
    }

    public function gigsBySubcategory($slug)
    {
        $subcategory = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        // Fetch categories and subcategories for the menu
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();
            
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');
        
        $gigs = Gig::whereHas('categories', function($q) use ($subcategory) {
                $q->where('category_id', $subcategory->id);
            })
            ->with(['provider', 'packages'])
            ->where('is_active', true)
            ->where('status', 'published')
            ->latest()
            ->paginate(12);
            
        return view('Customer.gigs.index', compact('subcategory', 'gigs', 'categories', 'subcategories'));
    }
}
