<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use App\Models\GigOrder;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GigController extends Controller
{
    public function index()
    {
        $gigs = Gig::where('provider_id', auth()->id())
            ->with(['category', 'serviceType', 'packages'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $gigs
        ]);
    }

    public function store(Request $request)
    {
        // ... (store method implementation)
    }

    public function show($id)
    {
        $gig = Gig::where('provider_id', auth()->id())
            ->with(['category', 'serviceType', 'packages', 'faqs', 'tags'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $gig
        ]);
    }

    public function update(Request $request, $id)
    {
        // ... (update method implementation)
    }

    public function destroy($id)
    {
        // ... (destroy method implementation)
    }

    public function updateStatus(Request $request, $id)
    {
        // ... (updateStatus method implementation)
    }

    public function analytics($id)
    {
        $gig = Gig::where('provider_id', auth()->id())->findOrFail($id);
        
        // Calculate earnings from completed orders
        $totalEarnings = GigOrder::where('gig_id', $id)
            ->where('status', 'completed')
            ->sum('provider_amount');

        // Calculate pending amount (funds in pending state)
        $pendingAmount = WalletTransaction::where('reference_type', 'gig_order')
            ->whereIn('reference_id', function($query) use ($id) {
                $query->select('id')->from('gig_orders')->where('gig_id', $id);
            })
            ->where('status', 'pending')
            ->sum('amount');

        // Calculate today's earnings
        $todayEarnings = GigOrder::where('gig_id', $id)
            ->where('status', 'completed')
            ->whereDate('updated_at', today())
            ->sum('provider_amount');

        // Calculate earnings change percentage (vs yesterday)
        $yesterdayEarnings = GigOrder::where('gig_id', $id)
            ->where('status', 'completed')
            ->whereDate('updated_at', today()->subDay())
            ->sum('provider_amount');
            
        $earningsChange = 0;
        if ($yesterdayEarnings > 0) {
            $earningsChange = (($todayEarnings - $yesterdayEarnings) / $yesterdayEarnings) * 100;
        } else if ($todayEarnings > 0) {
            $earningsChange = 100;
        }

        // Order stats
        $activeOrders = GigOrder::where('gig_id', $id)
            ->whereIn('status', ['pending', 'active', 'in_progress'])
            ->count();

        $completedOrders = GigOrder::where('gig_id', $id)
            ->where('status', 'completed')
            ->count();

        $pendingOrdersCount = GigOrder::where('gig_id', $id)
            ->where('status', 'pending')
            ->count();

        $totalOrders = GigOrder::where('gig_id', $id)->count();

        // Chart data (Last 7 days)
        $salesChart = [];
        $ordersChart = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            
            $sales = GigOrder::where('gig_id', $id)
                ->where('status', 'completed')
                ->whereDate('updated_at', $date)
                ->sum('provider_amount');
                
            $orders = GigOrder::where('gig_id', $id)
                ->whereDate('created_at', $date)
                ->count();
                
            $salesChart[] = [
                'date' => $date->format('D'), // Mon, Tue, etc.
                'value' => $sales
            ];
            
            $ordersChart[] = [
                'date' => $date->format('D'),
                'value' => $orders
            ];
        }

        // Wallet Clearance Logic
        $gigOrderIds = GigOrder::where('gig_id', $id)->pluck('id');
        
        $clearanceAmount = WalletTransaction::where('reference_type', 'gig_order')
            ->whereIn('reference_id', $gigOrderIds)
            ->where('status', 'pending')
            ->sum('amount');

        $clearedAmount = WalletTransaction::where('reference_type', 'gig_order')
            ->whereIn('reference_id', $gigOrderIds)
            ->where('status', 'completed')
            ->sum('amount');

        $averageRating = $gig->reviews()->avg('rating') ?? 0.0;
        $totalReviews = $gig->reviews()->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_earnings' => $totalEarnings,
                'pending_amount' => $pendingAmount,
                'clearance_amount' => $clearanceAmount,
                'cleared_amount' => $clearedAmount,
                'today_earnings' => $todayEarnings,
                'earnings_change' => round($earningsChange, 1),
                'active_orders' => $activeOrders,
                'completed_orders' => $completedOrders,
                'pending_orders' => $pendingOrdersCount,
                'average_rating' => round($averageRating, 1),
                'total_reviews' => $totalReviews,
                'sales_chart' => $salesChart,
                'orders_chart' => $ordersChart,
                'recent_orders' => GigOrder::where('gig_id', $id)->with('user')->latest()->take(5)->get(),
                'recent_reviews' => $gig->reviews()->with('user')->latest()->take(5)->get(),
                'view_count' => $gig->view_count,
                'bookings_count' => $totalOrders,
            ]
        ]);
    }

    public function reviews($id)
    {
        $gig = Gig::where('provider_id', auth()->id())->findOrFail($id);
        
        $reviews = $gig->reviews()
            ->with('user')
            ->latest()
            ->paginate(20);
            
        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}
