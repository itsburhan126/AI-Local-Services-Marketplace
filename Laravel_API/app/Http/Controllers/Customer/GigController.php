<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gig;
use App\Models\GigOrder;
use App\Models\GigPackage;
use App\Models\User;
use App\Services\FCMService;
use App\Events\NewGigOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GigController extends Controller
{
    public function index(Request $request)
    {
        $query = Gig::with(['provider', 'packages', 'reviews'])
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'rating':
                    $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $gigs = $query->paginate(12);
        
        $categories = Category::whereNull('parent_id')->get();

        return view('Customer.gigs.index', compact('gigs', 'categories'));
    }

    public function orders(Request $request)
    {
        $query = GigOrder::with(['gig.provider', 'package'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('gig', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('Customer.gigs.orders', compact('orders'));
    }

    public function show($slug)
    {
        // Fetch categories for navigation
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();

        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        $gig = Gig::with(['provider.providerProfile', 'packages' => function($query) {
                $query->orderBy('price', 'asc');
            }, 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Calculate average rating if not present
        if (!$gig->rating) {
            $gig->rating = $gig->reviews()->avg('rating') ?? 0;
        }

        // Get last delivery
        $lastOrder = GigOrder::where('provider_id', $gig->provider_id)
            ->where('status', 'completed')
            ->latest('updated_at')
            ->first();
        
        $lastDelivery = $lastOrder ? $lastOrder->updated_at->diffForHumans() : 'No deliveries yet';

        // Get related gigs (same category)
        $relatedGigs = Gig::with(['provider', 'packages'])
            ->where('category_id', $gig->category_id)
            ->where('id', '!=', $gig->id)
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('Customer.gigs.show', compact('gig', 'relatedGigs', 'categories', 'subcategories', 'lastDelivery', 'providerImage'));
    }

    public function checkout($slug, Request $request)
    {
        $gig = Gig::with(['provider', 'packages'])->where('slug', $slug)->firstOrFail();
        $packageId = $request->query('package_id');
        $selectedPackage = $gig->packages->where('id', $packageId)->first();
        
        if (!$selectedPackage) {
            $selectedPackage = $gig->packages->first();
        }

        $gateways = \App\Models\PaymentGateway::where('is_active', true)->get();
        $serviceFeePercentage = \App\Models\Setting::get('service_fee', 0);
        $serviceFee = round(($selectedPackage->price * $serviceFeePercentage) / 100, 2);

        return view('Customer.gigs.checkout', compact('gig', 'selectedPackage', 'gateways', 'serviceFee', 'serviceFeePercentage'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'gig_id' => 'required|exists:gigs,id',
            'gig_package_id' => 'required|exists:gig_packages,id',
            'date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
            'address' => 'nullable|string',
            'payment_method' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::exists('payment_gateways', 'name')->where(function ($query) {
                    $query->where('is_active', true);
                }),
            ],
        ]);

        try {
            DB::beginTransaction();

            $gig = Gig::findOrFail($request->gig_id);
            $package = GigPackage::findOrFail($request->gig_package_id);
            
            if ($package->gig_id !== $gig->id) {
                 return back()->with('error', 'Invalid package for this gig.');
            }

            $price = $package->price;
            $serviceFeePercentage = \App\Models\Setting::get('service_fee', 0);
            $serviceFee = ($price * $serviceFeePercentage) / 100;
            $totalAmount = $price + $serviceFee;

            $commissionRate = 0.10; 
            $commissionAmount = $price * $commissionRate;
            $providerAmount = $price - $commissionAmount;

            $scheduledAt = date('Y-m-d H:i:s', strtotime("$request->date $request->time"));

            $gigOrder = GigOrder::create([
                'user_id' => Auth::id(),
                'provider_id' => $gig->provider_id,
                'gig_id' => $gig->id,
                'gig_package_id' => $package->id,
                'status' => 'pending',
                'scheduled_at' => $scheduledAt,
                'total_amount' => $totalAmount,
                'service_fee' => $serviceFee,
                'commission_amount' => $commissionAmount,
                'provider_amount' => $providerAmount,
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'address' => $request->address,
                'notes' => $request->notes,
            ]);

            // Notify Provider
            try {
                event(new NewGigOrder($gigOrder));
            } catch (\Exception $e) {
                // Log error
            }

             // Push Notification via FCM
            $provider = User::find($gig->provider_id);
            if ($provider && $provider->fcm_token) {
                 try {
                    $fcmService = new FCMService();
                    $fcmService->sendNotification(
                        $provider->fcm_token,
                        'New Order Received!',
                        'You have received a new order for ' . $gig->title,
                        ['type' => 'new_order', 'order_id' => $gigOrder->id]
                    );
                } catch (\Exception $e) {
                    // Log error
                }
            }

            DB::commit();

            if (in_array($request->payment_method, ['paypal', 'stripe', 'card'])) {
                return redirect()->route('customer.payment.pay', ['order_id' => $gigOrder->id]);
            }

            return redirect()->route('customer.gigs.order.success', ['order_id' => $gigOrder->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function orderSuccess($orderId)
    {
        $order = GigOrder::with(['gig.category'])->find($orderId);

        if (!$order) {
            abort(404, 'Order not found');
        }

        if ($order->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this order');
        }

        // Fetch categories for navigation
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();

        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        // Fetch Related Gigs
        $relatedGigs = Gig::with(['provider', 'packages', 'reviews'])
            ->where('category_id', $order->gig->category_id)
            ->where('id', '!=', $order->gig_id)
            ->where('is_active', true)
            ->whereIn('status', ['published', 'approved'])
            ->inRandomOrder()
            ->take(8)
            ->get();
        
        return view('Customer.gigs.order-success', compact('order', 'categories', 'subcategories', 'relatedGigs'));
    }

    public function orderDetails($orderId)
    {
        $order = GigOrder::with(['gig', 'package', 'provider'])->find($orderId);

        if (!$order) {
            abort(404, 'Order not found');
        }

        if ($order->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this order');
        }

        // Fetch categories for navigation
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();

        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        return view('Customer.gigs.order-details', compact('order', 'categories', 'subcategories'));
    }

    public function invoice($orderId)
    {
        $order = GigOrder::with(['gig', 'package', 'provider', 'user'])->find($orderId);

        if (!$order) {
            abort(404, 'Order not found');
        }

        if ($order->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this order');
        }

        $invoiceService = new \App\Services\InvoiceService();
        $invoiceData = $invoiceService->generateInvoiceData($order);

        return view('Customer.gigs.invoice', compact('order', 'invoiceData'));
    }
}
