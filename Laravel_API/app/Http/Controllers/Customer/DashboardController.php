<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Gig;

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

        // Fetch recommended gigs
        // In a real app, this would be personalized based on user history
        $recommendedGigs = Gig::with(['provider', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }])
            ->where('is_active', true)
            ->where('status', 'published') // Assuming 'published' is the status for visible gigs
            ->latest()
            ->take(8)
            ->get();

        return view('Customer.dashboard', compact('categories', 'recommendedGigs'));
    }
}
