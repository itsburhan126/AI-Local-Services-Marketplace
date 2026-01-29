<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\GigOrder;
use App\Models\WalletTransaction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        $activeOrders = $bookings->whereIn('status', ['accepted', 'in_progress', 'ready', 'delivered']);
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

    public function deliver(Request $request, $id)
    {
        $order = GigOrder::where('provider_id', Auth::id())->findOrFail($id);
        
        if (!in_array($order->status, ['accepted', 'in_progress'])) {
            return back()->with('error', 'Order cannot be delivered.');
        }

        $request->validate([
            'delivery_note' => 'required|string|min:10',
            'delivery_files.*' => 'nullable|file|max:10240', // Max 10MB per file
        ]);

        $deliveryFiles = [];
        if ($request->hasFile('delivery_files')) {
            foreach ($request->file('delivery_files') as $file) {
                $path = $file->store('order_deliveries', 'public');
                $deliveryFiles[] = $path;
            }
        }

        // Update Order with Delivery Info and set status to 'delivered'
        $order->update([
            'status' => 'delivered',
            'delivery_note' => $request->delivery_note,
            'delivery_files' => $deliveryFiles,
        ]);
        
        // Handle Wallet Transaction (Pending Clearance)
        // NOTE: In a full flow, this might happen after client approval (completed). 
        // For now, we trigger it on delivery as per previous instructions to show "Payments being cleared".
        $amount = $order->provider_amount ?? $order->total_amount;
        
        // Get Clearance Delay from Settings (Default 0 if not set)
        $delayDays = (int) Setting::get('freelancer_payment_delay_days', 0);

        // Create Transaction
        WalletTransaction::create([
            'user_id' => $order->provider_id,
            'amount' => $amount,
            'type' => 'credit',
            'description' => 'Order Revenue for #' . $order->id,
            'reference_id' => $order->id,
            'reference_type' => 'GigOrder',
            'status' => 'pending',
            'available_at' => Carbon::now()->addDays($delayDays),
        ]);

        // Update User Pending Balance
        $user = Auth::user();
        $user->pending_balance = ($user->pending_balance ?? 0) + $amount;
        $user->save();

        $message = 'Work delivered successfully. Waiting for customer approval.';
        if ($delayDays > 0) {
            $message .= " Funds are pending clearance for {$delayDays} days.";
        } else {
            $message .= " Funds will be available shortly.";
        }

        return back()->with('success', $message);
    }
}
