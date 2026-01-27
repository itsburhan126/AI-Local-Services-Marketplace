<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use App\Models\GigPackage;
use App\Models\GigOrder;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\WalletTransaction;

use App\Events\NewGigOrder;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class GigOrderController extends Controller
{
    /**
     * Place an order for a Gig.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gig_id' => 'required|exists:gigs,id',
            'gig_package_id' => 'required|exists:gig_packages,id',
            'date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
            'address' => 'nullable|string',
            'extras' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $gig = Gig::findOrFail($request->gig_id);
            $package = GigPackage::findOrFail($request->gig_package_id);
            
            // Validate package belongs to gig
            if ($package->gig_id !== $gig->id) {
                 return response()->json(['status' => 'error', 'message' => 'Invalid package for this gig.'], 422);
            }

            $price = $package->price;
            
            // Calculate extras price if any
            $extrasPrice = 0;
            if ($request->has('extras')) {
                // Logic to validate and sum extras price
                // For now, assuming extras are handled in frontend or passed as IDs
                // Ideally we should validate IDs against gig_extras table
            }
            
            // Recalculate total if needed, for now using package price
            $totalAmount = $price + $extrasPrice;

            $commissionRate = 0.10; // 10% commission (Should come from settings)
            $commissionAmount = $totalAmount * $commissionRate;
            $providerAmount = $totalAmount - $commissionAmount;

            $scheduledAt = date('Y-m-d H:i:s', strtotime("$request->date $request->time"));

            $gigOrder = GigOrder::create([
                'user_id' => Auth::id(),
                'provider_id' => $gig->provider_id,
                'gig_id' => $gig->id,
                'gig_package_id' => $package->id,
                'status' => 'pending',
                'scheduled_at' => $scheduledAt,
                'total_amount' => $totalAmount,
                'commission_amount' => $commissionAmount,
                'provider_amount' => $providerAmount,
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method ?? 'cod',
                'address' => $request->address,
                'notes' => $request->notes,
                'extras' => $request->extras,
            ]);

            // Notify Provider
            // 1. Real-time update via Pusher
            try {
                event(new NewGigOrder($gigOrder));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to broadcast NewGigOrder event: ' . $e->getMessage());
            }

            // 2. Push Notification via FCM
            $provider = User::find($gig->provider_id);
            if ($provider && $provider->fcm_token) {
                try {
                    $fcmService = new FCMService();
                    $title = 'New Order Received!';
                    $body = 'You have received a new order for ' . $gig->title;
                    
                    $data = [
                        'type' => 'new_order',
                        'order_id' => (string) $gigOrder->id,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ];
                    
                    $fcmService->sendNotification($provider->fcm_token, $title, $body, $data);
                } catch (\Exception $e) {
                     \Illuminate\Support\Facades\Log::error('Failed to send Order FCM: ' . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Gig order placed successfully',
                'data' => $gigOrder
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Increment view count for a Gig.
     */
    public function view(Request $request, $id)
    {
        try {
            $gig = Gig::findOrFail($id);
            $gig->increment('view_count');
            
            return response()->json([
                'status' => 'success',
                'message' => 'View count incremented',
                'view_count' => $gig->view_count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to increment view count'
            ], 500);
        }
    }

    /**
     * List orders for the provider.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $user = Auth::user();
        
        $query = GigOrder::where('provider_id', $user->id)
            ->with(['user', 'gig', 'package']) // Load customer info
            ->latest();

        if ($status) {
            if ($status === 'active') {
                $query->whereIn('status', ['accepted', 'in_progress', 'delivered']);
            } elseif ($status === 'completed') {
                $query->whereIn('status', ['completed', 'cancelled', 'rejected']);
            } elseif ($status === 'pending') {
                 $query->where('status', 'pending');
            } else {
                $query->where('status', $status);
            }
        }

        $orders = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * List orders for the customer.
     */
    public function customerIndex(Request $request)
    {
        $status = $request->query('status');
        $user = Auth::user();

        $query = GigOrder::where('user_id', $user->id)
            ->with(['provider', 'gig', 'package'])
            ->latest();

        if ($status) {
            if ($status === 'active') {
                $query->whereIn('status', ['pending', 'accepted', 'in_progress', 'delivered']);
            } elseif ($status === 'history') { // 'history' usually means completed or cancelled
                 $query->whereIn('status', ['completed', 'cancelled', 'disputed', 'rejected']);
            } elseif ($status === 'completed') {
                $query->where('status', 'completed');
            } elseif ($status === 'cancelled') {
                $query->where('status', 'cancelled');
            } else {
                $query->where('status', $status);
            }
        }

        $orders = $query->paginate(20);
        
        // Transform data to include 'image' and 'title' for frontend compatibility if needed
        $orders->getCollection()->transform(function ($order) {
             // Ensure we don't overwrite if model accessors exist, but usually safe here
             // Using gig->thumbnail_image if available, or a default
             $order->image = $order->gig ? $order->gig->thumbnail_image : null; 
             // Note: gig->thumbnail_image might be a relative path, ensure frontend handles it or use an accessor for full URL
             // If Gig model has 'image_url' accessor, use that.
             // Checking Gig model is wise, but for now assuming 'image' column exists.
             
             // Also include service title if gig title is not sufficient
             $order->service_name = $order->gig ? $order->gig->title : 'Unknown Service';
             
             // Include provider name/image
             $order->provider_name = $order->provider ? $order->provider->name : 'Unknown Provider';
             $order->provider_image = $order->provider ? $order->provider->profile_image : null;
             
             return $order;
        });

        return response()->json([
            'status' => 'success',
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * Update order status (Accept/Complete).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,completed,cancelled,in_progress',
        ]);

        $gigOrder = GigOrder::where('provider_id', Auth::id())->findOrFail($id);

        if ($gigOrder->status === 'completed') {
            return response()->json(['status' => 'error', 'message' => 'Order is already completed'], 422);
        }

        $gigOrder->status = $request->status;
        $gigOrder->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Order status updated successfully',
            'data' => $gigOrder
        ]);
    }

    /**
     * Deliver work for the order (Provider only).
     */
    public function deliverWork(Request $request, $id)
    {
        $request->validate([
            'delivery_note' => 'nullable|string',
            'delivery_files' => 'nullable|array',
            'delivery_files.*' => 'file|max:20480', // Max 20MB per file
        ]);

        $gigOrder = GigOrder::where('provider_id', Auth::id())->findOrFail($id);

        if ($gigOrder->status !== 'in_progress') {
            return response()->json(['status' => 'error', 'message' => 'Order must be in progress to deliver work.'], 422);
        }

        $filePaths = [];
        if ($request->hasFile('delivery_files')) {
            foreach ($request->file('delivery_files') as $file) {
                $path = $file->store('gig_deliveries', 'public');
                $filePaths[] = 'storage/' . $path;
            }
        }

        $gigOrder->status = 'delivered';
        $gigOrder->delivery_note = $request->delivery_note;
        $gigOrder->delivery_files = $filePaths;
        $gigOrder->save();
        
        // Notify Customer (Implementation depends on notification system)
        try {
             $customer = User::find($gigOrder->user_id);
             if ($customer && $customer->fcm_token) {
                 $fcmService = new FCMService();
                 $fcmService->sendNotification(
                     $customer->fcm_token,
                     'Order Delivered',
                     'Your order #' . $gigOrder->id . ' has been delivered. Please review and approve.',
                     ['type' => 'order_delivered', 'order_id' => (string)$gigOrder->id]
                 );
             }
        } catch (\Exception $e) {
            // Log error
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Work delivered successfully',
            'data' => $gigOrder
        ]);
    }

    /**
     * Reject delivered work (Customer only).
     */
    public function rejectWork(Request $request, $id)
    {
        $gigOrder = GigOrder::where('user_id', Auth::id())->findOrFail($id);

        if ($gigOrder->status !== 'delivered') {
            return response()->json(['status' => 'error', 'message' => 'Order must be delivered to reject.'], 422);
        }

        try {
            DB::beginTransaction();

            $gigOrder->status = 'rejected';
            $gigOrder->save();

            $refundAmount = $gigOrder->total_amount;
            $cancellationFee = 0; // Set fee if needed
            $refundAmount -= $cancellationFee;

            $user = User::find(Auth::id());
            $user->wallet_balance += $refundAmount;
            $user->save();

            // Record Transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $refundAmount,
                'type' => 'credit',
                'description' => 'Refund for rejected order #' . $gigOrder->id,
                'reference_id' => $gigOrder->id,
                'reference_type' => 'gig_order',
            ]);

            // Notify Provider
            try {
                $provider = User::find($gigOrder->provider_id);
                if ($provider && $provider->fcm_token) {
                    $fcmService = new FCMService();
                    $fcmService->sendNotification(
                        $provider->fcm_token,
                        'Work Rejected',
                        'Your delivery for order #' . $gigOrder->id . ' was rejected.',
                        ['type' => 'order_rejected', 'order_id' => (string)$gigOrder->id]
                    );
                }
            } catch (\Exception $e) {
                // Log notification error
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Work rejected. Refund added to your wallet.',
                'data' => $gigOrder
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject work: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve delivered work (Customer only).
     */
    public function approveWork(Request $request, $id)
    {
        $gigOrder = GigOrder::where('user_id', Auth::id())->findOrFail($id);

        if ($gigOrder->status !== 'delivered') {
            return response()->json(['status' => 'error', 'message' => 'Order must be delivered to approve.'], 422);
        }

        try {
            DB::beginTransaction();

            $gigOrder->status = 'completed';
            $gigOrder->save();
            
            // Payment Release Logic
            $provider = User::find($gigOrder->provider_id);
            if ($provider) {
                $amount = $gigOrder->provider_amount;
                
                // Get delay setting
                $delayDays = \App\Models\Setting::get('freelancer_payment_delay_days', 0);
                $availableAt = now()->addDays($delayDays);
                
                // Add to pending balance
                $provider->pending_balance += $amount;
                $provider->save();
                
                // Create Transaction
                WalletTransaction::create([
                    'user_id' => $provider->id,
                    'amount' => $amount,
                    'type' => 'credit',
                    'description' => 'Payment for order #' . $gigOrder->id,
                    'reference_id' => $gigOrder->id,
                    'reference_type' => 'gig_order',
                    'status' => 'pending',
                    'available_at' => $availableAt,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order approved and completed successfully',
                'data' => $gigOrder
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to approve work: ' . $e->getMessage()], 500);
        }
    }
}