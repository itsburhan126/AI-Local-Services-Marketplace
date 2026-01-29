<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use App\Models\Category;
use Illuminate\Http\Request;

class GigController extends Controller
{
    public function index(Request $request)
    {
        $query = Gig::where('status', 'approved')
            ->where('is_active', true)
            ->with(['provider', 'category', 'serviceType', 'packages', 'relatedTags'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Filter by Category (including subcategories)
        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            
            // Get subcategories IDs
            $categoryIds = Category::where('parent_id', $categoryId)
                ->pluck('id')
                ->toArray();
            
            // Add the parent category ID
            $categoryIds[] = $categoryId;
            
            $query->whereIn('category_id', $categoryIds);
        }

        // Filter by Service Type
        if ($request->has('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }

        // Filter by Price Range
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->whereHas('packages', function($q) use ($request) {
                $q->where('tier', 'Basic');
                if ($request->has('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->has('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // Filter by Provider
        if ($request->has('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        // Filter by Seller Level
        if ($request->has('seller_level')) {
            $query->whereHas('provider.providerProfile', function($q) use ($request) {
                $q->where('seller_level', $request->seller_level);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'price_asc':
                    $query->join('gig_packages', 'gigs.id', '=', 'gig_packages.gig_id')
                          ->where('gig_packages.tier', 'Basic')
                          ->orderBy('gig_packages.price', 'asc')
                          ->select('gigs.*'); // Avoid column conflicts
                    break;
                case 'price_desc':
                    $query->join('gig_packages', 'gigs.id', '=', 'gig_packages.gig_id')
                          ->where('gig_packages.tier', 'Basic')
                          ->orderBy('gig_packages.price', 'desc')
                          ->select('gigs.*');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(20)
        ]);
    }

    public function show($id)
    {
        $gig = Gig::where('status', 'approved')
            ->where('is_active', true)
            ->with([
                'provider', 
                'provider.providerProfile', 
                'provider.freelancerPortfolios',
                'category', 
                'serviceType', 
                'packages', 
                'extras', 
                'faqs',
                'relatedTags',
                'reviews.user'
            ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->find($id);

        if (!$gig) {
            return response()->json([
                'success' => false,
                'message' => 'Gig not found or unavailable'
            ], 404);
        }

        // Increment view count
        $gig->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $gig
        ]);
    }
}
