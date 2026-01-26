<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Gig;
use App\Models\GigPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\GigOrder;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');

        // Fetch Gig Orders (Freelancer Gigs)
        $gigOrdersQuery = GigOrder::where('user_id', $user->id)
            ->with(['provider', 'gig', 'package'])
            ->latest();

        if ($status) {
            if ($status === 'active') {
                $gigOrdersQuery->whereIn('status', ['pending', 'accepted', 'in_progress']);
            } elseif ($status === 'history') {
                 $gigOrdersQuery->whereIn('status', ['completed', 'cancelled', 'disputed']);
            } else {
                $gigOrdersQuery->where('status', $status);
            }
        }

        $gigOrders = $gigOrdersQuery->get();

        // Transform GigOrders to match Booking structure if needed
        // For now, let's just return GigOrders as the user requested "db 'gig_orders' ... show koraw"
        // We can wrap them in a similar structure or just return them.
        // The frontend expects a list.
        
        // If we want to support both, we'd merge. But let's prioritize GigOrders as per request.
        
        // Map GigOrders to a common format if necessary for the frontend
        $data = $gigOrders->map(function ($order) {
            return [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'provider_id' => $order->provider_id,
                'gig_id' => $order->gig_id,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'scheduled_at' => $order->scheduled_at,
                'created_at' => $order->created_at,
                // Flatten relations for easier frontend consumption if needed
                'gig' => $order->gig,
                'provider' => $order->provider,
                'package' => $order->package,
                // Add 'image' field for frontend compatibility
                'image' => $order->gig ? $order->gig->image_url : null, // Assuming accessor exists or image field
                'title' => $order->gig ? $order->gig->title : 'Unknown Gig',
            ];
        });

        // Pagination manually since we might merge later, or just return all for now (paginate collection)
        // For simplicity, let's use the query pagination but we need to map the items.
        // Let's re-query with pagination.
        
        $bookings = $gigOrdersQuery->paginate(20);
        
        // Transform items
        $bookings->getCollection()->transform(function ($order) {
             $order->image = $order->gig ? $order->gig->image : null; // Accessor check needed
             $order->title = $order->gig ? $order->gig->title : 'Unknown Gig';
             return $order;
        });

        return response()->json([
            'status' => 'success',
            'data' => $bookings->items(),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'total' => $bookings->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $booking = Booking::with(['provider', 'service', 'gig', 'package'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $booking
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:users,id',
            'service_id' => 'nullable|exists:services,id', // Either service_id or gig_id required
            'gig_id' => 'nullable|exists:gigs,id',
            'gig_package_id' => 'nullable|exists:gig_packages,id',
            'date' => 'required|date',
            'time' => 'required',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (!$request->service_id && !$request->gig_id) {
             return response()->json([
                'status' => 'error',
                'message' => 'Either service_id or gig_id is required.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $price = 0;
            $commissionRate = 0.10; // 10% commission

            // Calculate Price
            if ($request->gig_id) {
                $gig = Gig::findOrFail($request->gig_id);
                if ($request->gig_package_id) {
                    $package = GigPackage::findOrFail($request->gig_package_id);
                    $price = $package->price;
                } else {
                    $price = $gig->price; // Fallback
                }
            } else {
                $service = Service::findOrFail($request->service_id);
                $price = $service->price;
                if ($service->discount_price) {
                    $price = $service->discount_price;
                }
            }

            $commissionAmount = $price * $commissionRate;
            $providerAmount = $price - $commissionAmount;

            // Combine date and time
            // Assuming time is "10:00 AM" or "14:30"
            $scheduledAt = date('Y-m-d H:i:s', strtotime("$request->date $request->time"));

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'provider_id' => $request->provider_id,
                'service_id' => $request->service_id,
                'gig_id' => $request->gig_id,
                'gig_package_id' => $request->gig_package_id,
                'status' => 'pending',
                'scheduled_at' => $scheduledAt,
                'total_amount' => $price,
                'commission_amount' => $commissionAmount,
                'provider_amount' => $providerAmount,
                'payment_status' => 'pending',
                'payment_method' => 'cod', // Default for now
                'address' => $request->address,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }
}
