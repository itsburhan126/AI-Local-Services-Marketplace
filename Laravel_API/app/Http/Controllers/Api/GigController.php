<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use Illuminate\Http\Request;

class GigController extends Controller
{
    public function index(Request $request)
    {
        $query = Gig::where('status', 'approved')
            ->where('is_active', true)
            ->with(['provider', 'category', 'serviceType', 'packages', 'relatedTags']);

        // Filter by Category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
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
