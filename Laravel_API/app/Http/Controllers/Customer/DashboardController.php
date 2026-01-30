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

        // Fetch subcategories grouped by parent_id for the mega menu
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

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

        return view('Customer.dashboard', compact('categories', 'subcategories', 'popularSubcategories', 'recommendedGigs'));
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
