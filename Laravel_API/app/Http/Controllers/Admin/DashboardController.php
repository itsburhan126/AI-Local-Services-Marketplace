<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $totalProviders = User::where('role', 'provider')->count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalServices = Service::count();
        $totalBookings = Booking::count();
        
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_amount');
        
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        
        // Recent Bookings
        $recentBookings = Booking::with(['user', 'provider', 'service'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly Revenue Chart Data
        $monthlyRevenue = Booking::where('payment_status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        // Fill missing months with 0
        $revenueChartData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 1; $i <= 12; $i++) {
            $revenueChartData[] = $monthlyRevenue[$i] ?? 0;
        }
        
        return view('admin.dashboard', compact(
            'totalProviders',
            'totalCustomers',
            'totalServices',
            'totalBookings',
            'totalRevenue',
            'pendingWithdrawals',
            'recentBookings',
            'revenueChartData',
            'months'
        ));
    }
}
