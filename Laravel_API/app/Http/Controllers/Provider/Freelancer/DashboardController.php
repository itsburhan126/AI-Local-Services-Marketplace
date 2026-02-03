<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Gig;
use App\Models\GigOrder;
use App\Models\Message;
use App\Models\Review;
use App\Models\WalletTransaction;
use App\Mail\OtpMail;
use App\Models\Country;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats
        $activeOrdersCount = GigOrder::where('provider_id', $user->id)
            ->whereIn('status', ['accepted', 'in_progress', 'ready', 'delivered'])
            ->count();

        $newMessagesCount = Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $walletBalance = $user->wallet_balance ?? 0;

        // Metrics
        $totalOrdersCount = GigOrder::where('provider_id', $user->id)->count();
        $completedOrdersCount = GigOrder::where('provider_id', $user->id)->where('status', 'completed')->count();
        $totalGigViews = Gig::where('provider_id', $user->id)->sum('view_count');
        $averageRating = Review::where('provider_id', $user->id)->avg('rating') ?? 0;
        $totalReviews = Review::where('provider_id', $user->id)->count();
        
        // Success Score calculation (simplified)
        $successScore = $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100;

        // Earnings Chart Data (Last 6 months)
        $earningsData = [];
        $earningsLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $earningsLabels[] = $date->format('M');
            
            $earnings = GigOrder::where('provider_id', $user->id)
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('provider_amount'); // Using provider_amount for net earnings
                
            $earningsData[] = $earnings;
        }

        // Recent Active Orders
        $activeOrders = GigOrder::with(['user', 'gig', 'package'])
            ->where('provider_id', $user->id)
            ->whereIn('status', ['accepted', 'in_progress', 'ready', 'delivered'])
            ->latest()
            ->take(5)
            ->get();

        // Recent Messages
        $recentMessages = Message::with('sender')
            ->where('receiver_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        // Profile Completion
        $profile = $user->providerProfile;
        $completion = 20; // Base (Name & Email)
        if ($profile) {
            if ($profile->company_name) $completion += 20;
            if ($profile->about) $completion += 20;
            if (!empty($profile->languages)) $completion += 20;
            if ($profile->address) $completion += 20;
        }

        $sellerLevel = $profile->seller_level ?? 'New Seller';

        // To-Do List Generation
        $todoList = collect();

        // Add Active Orders to To-Do
        foreach ($activeOrders as $order) {
            $title = $order->status === 'delivered' 
                ? 'Waiting for approval #' . $order->id 
                : 'Deliver order #' . $order->id;

            $todoList->push([
                'type' => 'order',
                'title' => $title,
                'subtitle' => 'Status: ' . ucfirst(str_replace('_', ' ', $order->status)),
                'link' => route('provider.freelancer.orders.show', $order->id),
                'icon' => 'fas fa-box',
                'completed' => false
            ]);
        }

        // Add Unread Messages to To-Do
        $unreadMessages = Message::with('sender')
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->take(3)
            ->get();

        foreach ($unreadMessages as $msg) {
             $todoList->push([
                'type' => 'message',
                'title' => 'Reply to ' . ($msg->sender->name ?? 'User'),
                'subtitle' => 'New message received',
                'link' => route('provider.freelancer.chat.index'),
                'icon' => 'fas fa-comment',
                'completed' => false
            ]);
        }

        return view('Provider.Freelancer.dashboard', compact(
            'activeOrdersCount',
            'newMessagesCount',
            'walletBalance',
            'totalOrdersCount',
            'completedOrdersCount',
            'successScore',
            'activeOrders',
            'recentMessages',
            'completion',
            'totalGigViews',
            'earningsData',
            'earningsLabels',
            'todoList',
            'sellerLevel',
            'averageRating',
            'totalReviews'
        ));
    }

    public function analytics()
    {
        return view('Provider.Freelancer.analytics');
    }

    public function earnings()
    {
        $user = Auth::user();

        // Financial Overview
        $availableFunds = $user->wallet_balance ?? 0;
        $pendingClearance = $user->pending_balance ?? 0;
        
        // Calculate detailed stats from transactions
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15); // Pagination for the table

        // Metrics (Lifetime)
        $netIncome = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->sum('amount');

        $withdrawn = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->where(function($q) {
                $q->where('description', 'like', '%withdraw%')
                  ->orWhere('description', 'like', '%payout%');
            })
            ->sum('amount');
            
        // Expenses (e.g. buying boosts, subscription fees if any)
        $expenses = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->where(function($q) {
                $q->where('description', 'not like', '%withdraw%')
                  ->where('description', 'not like', '%payout%');
            })
            ->sum('amount');

        // Active Orders Value (Future payments not yet in pending wallet)
        $activeOrdersValue = GigOrder::where('provider_id', $user->id)
            ->whereIn('status', ['accepted', 'in_progress', 'ready'])
            ->sum('provider_amount');

        // Get Clearing Payments Details
        $clearingPayments = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where(function ($query) {
                $query->where('status', 'pending')
                      ->orWhere('available_at', '>', now());
            })
            ->with(['user']) // Eager load if needed, though mostly just need description/amount
            ->orderBy('available_at', 'asc')
            ->get();

        return view('Provider.Freelancer.earnings', compact(
            'availableFunds',
            'pendingClearance',
            'netIncome',
            'withdrawn',
            'expenses',
            'transactions',
            'activeOrdersValue',
            'user',
            'clearingPayments'
        ));
    }

    public function profile()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get(['name','code','flag_emoji']);
        return view('Provider.Freelancer.profile.index', compact('countries'));
    }

    public function editProfile()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get(['name','code','flag_emoji']);
        $languages = \App\Models\Language::where('is_active', true)->orderBy('name')->get(['name','code']);
        $skills = \App\Models\Skill::where('is_active', true)->orderBy('name')->get(['name','type']);
        return view('Provider.Freelancer.profile.edit', compact('countries','languages','skills'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profile = $user->providerProfile;

        if (!$profile) {
            $profile = new \App\Models\ProviderProfile();
            $profile->user_id = $user->id;
        }

        if ($request->has('name')) {
            $user->name = $request->name;
            $user->save();
        }

        if ($request->has('professional_headline')) {
            $profile->company_name = $request->professional_headline;
        }
        
        if ($request->has('company_name')) {
            $profile->company_name = $request->company_name;
        }

        if ($request->has('description')) {
            $profile->about = $request->description;
        }

        if ($request->has('languages')) {
            $langs = $request->languages;
            if (is_string($langs)) {
                 $langs = array_map('trim', explode(',', $langs));
            }
            $profile->languages = $langs;
        }
        
        if ($request->has('skills')) {
            $skills = $request->skills;
            if (is_string($skills)) {
                $skills = array_map('trim', explode(',', $skills));
            }
            $profile->skills = $skills;
        }
        
        if ($request->has('location')) {
             $profile->address = $request->location;
        }
        
        if ($request->has('country')) {
             $profile->country = $request->country;
        }
        
        if ($request->has('phone')) {
            session(['phone_otp_target' => $request->phone]);
        }
        
        if ($request->hasFile('logo')) {
            $profile->logo = $request->file('logo')->store('provider/logo', 'public');
        }
        
        if ($request->hasFile('cover_image')) {
            $profile->cover_image = $request->file('cover_image')->store('provider/cover', 'public');
        }

        $profile->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function marketing()
    {
        return view('Provider.Freelancer.marketing');
    }

    public function settings()
    {
        $user = Auth::user();
        return view('Provider.Freelancer.settings.index', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function submitKyc(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'document_type' => 'required|string',
            'document_front' => 'required|image|max:4096',
            'document_back' => 'nullable|image|max:4096',
        ]);

        $kycData = [
            'type' => $request->document_type,
            'submitted_at' => now(),
        ];

        if ($request->hasFile('document_front')) {
            $kycData['front'] = $request->file('document_front')->store('kyc-documents', 'public');
        }
        
        if ($request->hasFile('document_back')) {
            $kycData['back'] = $request->file('document_back')->store('kyc-documents', 'public');
        }

        $user->kyc_status = 'pending';
        $user->kyc_data = $kycData;
        $user->save();

        return back()->with('success', 'KYC verification request submitted successfully.');
    }

    public function sendPhoneOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);
        $user = Auth::user();
        $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session([
            'phone_otp' => $otp,
            'phone_otp_expires' => now()->addMinutes(10),
            'phone_otp_target' => $request->phone,
        ]);
        Mail::to($user->email)->send(new OtpMail($otp));
        return back()->with('success', 'OTP sent to your email.');
    }

    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);
        $user = Auth::user();
        $storedOtp = session('phone_otp');
        $expires = session('phone_otp_expires');
        $target = session('phone_otp_target');
        if (!$storedOtp || !$expires || !$target) {
            return back()->withErrors(['otp' => 'No OTP request found.']);
        }
        if (now()->greaterThan($expires)) {
            return back()->withErrors(['otp' => 'OTP expired.']);
        }
        if ($request->otp !== $storedOtp) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }
        $user->phone = $target;
        $user->save();
        session()->forget(['phone_otp', 'phone_otp_expires', 'phone_otp_target']);
        return back()->with('success', 'Phone verified and updated.');
    }
}
