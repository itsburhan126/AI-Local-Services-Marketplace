<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\GigOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $bookings = GigOrder::with(['user', 'gig', 'package'])
            ->where('provider_id', $user->id)
            ->latest()
            ->get();

        $pendingOrders = $bookings->where('status', 'pending');
        $activeOrders = $bookings->whereIn('status', ['accepted', 'in_progress', 'ready']);
        $completedOrders = $bookings->whereIn('status', ['completed', 'cancelled', 'refunded']);

        return view('Provider.Freelancer.orders.index', compact('bookings', 'pendingOrders', 'activeOrders', 'completedOrders'));
    }

    public function show($id)
    {
        $order = GigOrder::with(['user', 'gig', 'package'])
            ->where('provider_id', Auth::id())
            ->findOrFail($id);

        return view('Provider.Freelancer.orders.show', compact('order'));
    }

    public function accept($id)
    {
        $order = GigOrder::where('provider_id', Auth::id())->findOrFail($id);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order cannot be accepted.');
        }

        $order->update(['status' => 'accepted']);
        // Optional: Notify user

        return back()->with('success', 'Order accepted successfully.');
    }

    public function decline($id)
    {
        $order = GigOrder::where('provider_id', Auth::id())->findOrFail($id);
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order cannot be declined.');
        }

        $order->update(['status' => 'cancelled']);
        // Optional: Notify user

        return back()->with('success', 'Order declined.');
    }

    public function deliver($id)
    {
        $order = GigOrder::where('provider_id', Auth::id())->findOrFail($id);
        
        if (!in_array($order->status, ['accepted', 'in_progress'])) {
            return back()->with('error', 'Order cannot be delivered.');
        }

        $order->update(['status' => 'completed']); // Or 'delivered' if using that status
        // Optional: Notify user

        return back()->with('success', 'Order marked as completed.');
    }
}
