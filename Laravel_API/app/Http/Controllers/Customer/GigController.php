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

        return view('Customer.gigs.show', compact('gig', 'relatedGigs', 'categories', 'subcategories', 'lastDelivery'));
    }

    public function checkout($slug, Request $request)
    {
        $gig = Gig::with(['provider', 'packages'])->where('slug', $slug)->firstOrFail();
        $packageId = $request->query('package_id');
        $selectedPackage = $gig->packages->where('id', $packageId)->first();
        
        if (!$selectedPackage) {
            $selectedPackage = $gig->packages->first();
        }

        return view('Customer.gigs.checkout', compact('gig', 'selectedPackage'));
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
        ]);

        try {
            DB::beginTransaction();

            $gig = Gig::findOrFail($request->gig_id);
            $package = GigPackage::findOrFail($request->gig_package_id);
            
            if ($package->gig_id !== $gig->id) {
                 return back()->with('error', 'Invalid package for this gig.');
            }

            $price = $package->price;
            $totalAmount = $price; // Add extras logic if needed

            $commissionRate = 0.10; 
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

            return redirect()->route('customer.dashboard')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
