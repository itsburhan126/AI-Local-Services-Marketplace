<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    if (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
        if ($user->role === 'provider' && $user->service_rule === 'freelancer') {
            return redirect()->route('provider.freelancer.dashboard');
        }
        if ($user->role === 'user') {
            return redirect()->route('customer.dashboard');
        }
        // Fallback or other roles
    }
    return view('landing');
})->name('landing');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/register', [\App\Http\Controllers\Customer\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Customer\AuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [\App\Http\Controllers\Customer\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Customer\AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [\App\Http\Controllers\Customer\AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:web')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/gigs/subcategory/{slug}', [\App\Http\Controllers\Customer\DashboardController::class, 'gigsBySubcategory'])->name('gigs.by.subcategory');
    });
});


Route::get('/join-as-pro', function () {
    return view('join-selection');
})->name('join.pro');

Route::get('/hire-a-pro', function () {
    return view('hire-selection');
})->name('hire.pro');

Route::prefix('freelancer')->name('provider.freelancer.')->group(function () {
    Route::get('/register', function () {
        return view('Provider.Freelancer.auth.register');
    })->name('register');
    
    Route::post('/register', [\App\Http\Controllers\Provider\AuthController::class, 'registerFreelancer'])->name('register.submit');

    Route::get('/login', function () {
        return view('Provider.Freelancer.auth.login');
    })->name('login');
    
    Route::post('/login', [\App\Http\Controllers\Provider\AuthController::class, 'login'])->name('login.submit');

    Route::get('/dashboard', [\App\Http\Controllers\Provider\Freelancer\DashboardController::class, 'index'])->middleware('auth:web')->name('dashboard');
    Route::get('/analytics', [\App\Http\Controllers\Provider\Freelancer\DashboardController::class, 'analytics'])->middleware('auth:web')->name('analytics');
    Route::get('/earnings', [\App\Http\Controllers\Provider\Freelancer\DashboardController::class, 'earnings'])->middleware('auth:web')->name('earnings');
    Route::get('/profile', [\App\Http\Controllers\Provider\Freelancer\DashboardController::class, 'profile'])->middleware('auth:web')->name('profile');
    Route::put('/profile/update', [\App\Http\Controllers\Provider\Freelancer\DashboardController::class, 'updateProfile'])->middleware('auth:web')->name('profile.update');
    Route::get('/marketing', [\App\Http\Controllers\Provider\Freelancer\DashboardController::class, 'marketing'])->middleware('auth:web')->name('marketing');

    Route::middleware('auth:web')->group(function () {
        // Gig Routes
        Route::resource('gigs', \App\Http\Controllers\Provider\Freelancer\GigController::class);

        // Order Routes
        Route::get('orders', [\App\Http\Controllers\Provider\Freelancer\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [\App\Http\Controllers\Provider\Freelancer\OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{id}/accept', [\App\Http\Controllers\Provider\Freelancer\OrderController::class, 'accept'])->name('orders.accept');
        Route::post('orders/{id}/decline', [\App\Http\Controllers\Provider\Freelancer\OrderController::class, 'decline'])->name('orders.decline');
        Route::post('orders/{id}/deliver', [\App\Http\Controllers\Provider\Freelancer\OrderController::class, 'deliver'])->name('orders.deliver');
        
        // Chat Routes
        Route::get('chat', [\App\Http\Controllers\Provider\Freelancer\ChatController::class, 'index'])->name('chat.index');
        Route::post('chat/send', [\App\Http\Controllers\Provider\Freelancer\ChatController::class, 'store'])->name('chat.store');

        // Payout Methods
        Route::get('payout-methods', [\App\Http\Controllers\Provider\Freelancer\PayoutController::class, 'index'])->name('payout.index');
        Route::get('payout-methods/create/{payoutMethod}', [\App\Http\Controllers\Provider\Freelancer\PayoutController::class, 'create'])->name('payout.create');
        Route::post('payout-methods/{payoutMethod}', [\App\Http\Controllers\Provider\Freelancer\PayoutController::class, 'store'])->name('payout.store');
        Route::delete('payout-methods/{userPayoutMethod}', [\App\Http\Controllers\Provider\Freelancer\PayoutController::class, 'destroy'])->name('payout.destroy');
        
        // Withdrawals
        Route::get('withdraw', [\App\Http\Controllers\Provider\Freelancer\PayoutController::class, 'withdrawPage'])->name('withdraw.page');
        Route::post('withdraw', [\App\Http\Controllers\Provider\Freelancer\PayoutController::class, 'withdrawRequest'])->name('withdraw.request');
    });
});

Route::prefix('provider/local')->name('provider.local.')->group(function () {
    Route::get('/register', function () {
        return view('Provider.Local.auth.register');
    })->name('register');
    
    Route::post('/register', [\App\Http\Controllers\Provider\AuthController::class, 'registerLocal'])->name('register.submit');

    Route::get('/login', function () {
        return view('Provider.Local.auth.login');
    })->name('login');
    
    Route::post('/login', [\App\Http\Controllers\Provider\AuthController::class, 'login'])->name('login.submit');
});

Route::post('/provider/logout', [\App\Http\Controllers\Provider\AuthController::class, 'logout'])->name('provider.logout');

 
 
Route::get('login', function() {
    return redirect()->route('admin.login');
})->name('login');

// Public Page Route
Route::get('/page/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

// Admin Routes
use App\Http\Controllers\Admin\PayoutMethodController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated Admin Routes
    Route::middleware(['auth:admin', 'admin.demo'])->group(function () {
        
     
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
       
        Route::middleware('admin.permission:dashboard_access')->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        });
        
        Route::resource('payout-methods', PayoutMethodController::class);
        
    
        
        // Staff & Roles
        Route::middleware('admin.permission:manage_staff')->group(function () {
            Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
            Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class);
        });

        // Providers Management
        Route::post('providers/{id}/status', [\App\Http\Controllers\Admin\ProviderController::class, 'updateStatus'])->name('providers.status');
        Route::resource('providers', \App\Http\Controllers\Admin\ProviderController::class);

        // Bookings
        Route::patch('bookings/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.status');
        Route::get('bookings/{booking}/invoice', [\App\Http\Controllers\Admin\BookingController::class, 'downloadInvoice'])->name('bookings.invoice');
        Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show']);

        // Users (Customers)
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'show']);

        // Categories & Services
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('service-types', \App\Http\Controllers\Admin\ServiceTypeController::class);
        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
        
        // Freelancer Management
        Route::get('freelancers/tags', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'tags'])->name('freelancers.tags');
        Route::post('freelancers/tags', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'storeTag'])->name('freelancers.tags.store');
        Route::put('freelancers/tags/{id}', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'updateTag'])->name('freelancers.tags.update');
        Route::delete('freelancers/tags/{id}', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'destroyTag'])->name('freelancers.tags.destroy');
        Route::get('freelancers/banners', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'banners'])->name('freelancers.banners');
        Route::post('freelancers/banners', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'storeBanner'])->name('freelancers.banners.store');
        Route::delete('freelancers/banners/{banner}', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'destroyBanner'])->name('freelancers.banners.destroy');
        
        // Freelancer Settings
        Route::get('freelancers/settings', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'settings'])->name('freelancers.settings');
        Route::post('freelancers/settings', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'updateSettings'])->name('freelancers.settings.update');

        Route::get('freelancers', [\App\Http\Controllers\Admin\Freelancer\FreelancerController::class, 'index'])->name('freelancers.index');
        
        // Freelancer Interests
        Route::post('freelancer-interests/reorder', [\App\Http\Controllers\Admin\Freelancer\FreelancerInterestController::class, 'reorder'])->name('freelancer-interests.reorder');
        Route::resource('freelancer-interests', \App\Http\Controllers\Admin\Freelancer\FreelancerInterestController::class)->only(['index', 'store', 'update', 'destroy']);
        
        // Freelancer Categories
        Route::get('freelancer-categories', [\App\Http\Controllers\Admin\Freelancer\FreelancerCategoryController::class, 'index'])->name('freelancer-categories.index');
        Route::get('freelancer-categories/create', [\App\Http\Controllers\Admin\Freelancer\FreelancerCategoryController::class, 'create'])->name('freelancer-categories.create');
        Route::post('freelancer-categories', [\App\Http\Controllers\Admin\Freelancer\FreelancerCategoryController::class, 'store'])->name('freelancer-categories.store');
        Route::get('freelancer-categories/{id}/edit', [\App\Http\Controllers\Admin\Freelancer\FreelancerCategoryController::class, 'edit'])->name('freelancer-categories.edit');
        Route::put('freelancer-categories/{id}', [\App\Http\Controllers\Admin\Freelancer\FreelancerCategoryController::class, 'update'])->name('freelancer-categories.update');
        Route::delete('freelancer-categories/{id}', [\App\Http\Controllers\Admin\Freelancer\FreelancerCategoryController::class, 'destroy'])->name('freelancer-categories.destroy');

        // Gig Management
        Route::get('gig-requests', [\App\Http\Controllers\Admin\Freelancer\GigController::class, 'requests'])->name('gigs.requests');
        Route::post('gigs/{gig}/approve', [\App\Http\Controllers\Admin\Freelancer\GigController::class, 'approve'])->name('gigs.approve');
        Route::post('gigs/{gig}/reject', [\App\Http\Controllers\Admin\Freelancer\GigController::class, 'reject'])->name('gigs.reject');
        Route::post('gigs/{gig}/suspend', [\App\Http\Controllers\Admin\Freelancer\GigController::class, 'suspend'])->name('gigs.suspend');
        Route::post('gigs/{gig}/pause', [\App\Http\Controllers\Admin\Freelancer\GigController::class, 'pause'])->name('gigs.pause');
        Route::resource('gigs', \App\Http\Controllers\Admin\Freelancer\GigController::class);

        // Monetization
        Route::resource('subscription-plans', \App\Http\Controllers\Admin\SubscriptionPlanController::class);
        Route::resource('provider-subscriptions', \App\Http\Controllers\Admin\ProviderSubscriptionController::class)->only(['index']);
        
        // Withdrawals
        Route::resource('withdrawals', \App\Http\Controllers\Admin\WithdrawalController::class)->only(['index', 'update']);

        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Zones
        Route::resource('zones', \App\Http\Controllers\Admin\ZoneController::class);

        // Marketing
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
        Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
        Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class)->only(['index', 'store', 'destroy']);
        
        // Flash Sale
        Route::get('flash-sale/activity', [\App\Http\Controllers\Admin\FlashSaleController::class, 'activity'])->name('flash-sale.activity');
        Route::get('flash-sale/analytics', [\App\Http\Controllers\Admin\FlashSaleController::class, 'analytics'])->name('flash-sale.analytics');
        Route::get('flash-sale/item-analytics/{id}', [\App\Http\Controllers\Admin\FlashSaleController::class, 'itemAnalytics'])->name('flash-sale.item-analytics');
        Route::resource('flash-sale', \App\Http\Controllers\Admin\FlashSaleController::class)->only(['index', 'store', 'destroy', 'update']);

        // AI Tools
        Route::get('ai-settings', [\App\Http\Controllers\Admin\AiController::class, 'index'])->name('ai.settings');
        Route::post('ai-settings', [\App\Http\Controllers\Admin\AiController::class, 'update'])->name('ai.settings.update');

        // Push Notifications
        Route::resource('push-notifications', \App\Http\Controllers\Admin\PushNotificationController::class)->only(['index', 'create', 'store', 'destroy']);

        // Chat System
        Route::get('chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
        Route::get('chat/messages/{id}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('chat.show');
        Route::post('chat/messages', [\App\Http\Controllers\Admin\ChatController::class, 'store'])->name('chat.store');

        // Referral Settings
        Route::get('referral', [\App\Http\Controllers\Admin\ReferralController::class, 'index'])->name('referral.index');
        Route::post('referral', [\App\Http\Controllers\Admin\ReferralController::class, 'update'])->name('referral.update');

        // System Data (Countries, Languages, Skills)
        Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class);
        Route::resource('languages', \App\Http\Controllers\Admin\LanguageController::class);
        Route::resource('skills', \App\Http\Controllers\Admin\SkillController::class);

        // Interest Management (New Ultra Feature)
        Route::post('interests/reorder', [\App\Http\Controllers\Admin\InterestController::class, 'reorder'])->name('interests.reorder');
        Route::resource('interests', \App\Http\Controllers\Admin\InterestController::class)->only(['index', 'store', 'update', 'destroy']);

        // Flash Sale Management (Ultra)
        Route::get('flash-sale', [\App\Http\Controllers\Admin\FlashSaleController::class, 'index'])->name('flash-sale.index');
        Route::get('flash-sale/add-items', [\App\Http\Controllers\Admin\FlashSaleController::class, 'addItemsPage'])->name('flash-sale.add-items-page');
        Route::post('flash-sale/config', [\App\Http\Controllers\Admin\FlashSaleController::class, 'updateConfig'])->name('flash-sale.update-config');
        Route::post('flash-sale/items', [\App\Http\Controllers\Admin\FlashSaleController::class, 'storeItem'])->name('flash-sale.store-item');
        Route::delete('flash-sale/items/{id}', [\App\Http\Controllers\Admin\FlashSaleController::class, 'destroyItem'])->name('flash-sale.destroy-item');
        Route::post('flash-sale/reorder', [\App\Http\Controllers\Admin\FlashSaleController::class, 'reorder'])->name('flash-sale.reorder');

    });
});
