<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function show($id)
    {
        $provider = User::where('role', 'provider')
            ->with([
                'providerProfile', 
                'freelancerPortfolios',
                'gigs' => function($q) {
                    $q->where('status', 'approved')->where('is_active', true);
                },
                // Use the correct relationship name from User model
                // If receivedReviews is not defined, we might need to rely on 'gigs.reviews' or define it.
                // But for now, let's assume we can add it or use a manual query if needed.
                // Let's check if we can add it to User model first.
            ])
            ->find($id);

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Provider not found'
            ], 404);
        }

        // Load reviews manually if relationship is missing or complex
        // We want reviews for all gigs of this provider + direct provider reviews (if any)
        // The Review model has 'provider_id'.
        $reviews = \App\Models\Review::where('provider_id', $id)
            ->with('user')
            ->latest()
            ->get();
            
        $provider->setRelation('reviews', $reviews);
        
        // Calculate stats
        $rating = $reviews->avg('rating') ?? 5.0;
        $reviews_count = $reviews->count();
        $completed_orders = \App\Models\GigOrder::where('provider_id', $id)
            ->where('status', 'completed')
            ->count();

        $provider->rating = $rating;
        $provider->reviews_count = $reviews_count;
        $provider->completed_orders = $completed_orders;

        return response()->json([
            'success' => true,
            'data' => $provider
        ]);
    }
}
