<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ChatController;

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\FavoriteController;


// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/check-user', [AuthController::class, 'checkUser']);

// Public Home Data
Route::get('/home', [HomeController::class, 'index']);
Route::get('/freelancer/home', [\App\Http\Controllers\Api\Freelancer\HomeController::class, 'index']); // New Freelancer Home Route
Route::get('/freelancer/gigs/new', [\App\Http\Controllers\Api\Freelancer\HomeController::class, 'newGigs']); // New Gigs View All Route
Route::get('/interests', [InterestController::class, 'index']);
Route::get('/flash-sale', [\App\Http\Controllers\Api\FlashSaleController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/service-types', [App\Http\Controllers\Api\ServiceTypeController::class, 'index']);
Route::get('/countries', [\App\Http\Controllers\Api\CountryController::class, 'index']);
Route::get('/languages', [\App\Http\Controllers\Api\LanguageController::class, 'index']);
Route::get('/skills', [\App\Http\Controllers\Api\SkillController::class, 'index']);
Route::get('/tags', [\App\Http\Controllers\Api\TagController::class, 'index']);
Route::get('/settings', [\App\Http\Controllers\Api\SettingController::class, 'index']);
Route::get('/gigs', [\App\Http\Controllers\Api\GigController::class, 'index']);
Route::get('/gigs/{id}', [\App\Http\Controllers\Api\GigController::class, 'show']);
Route::get('/providers/{id}', [\App\Http\Controllers\Api\ProviderController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/delete-account', [AuthController::class, 'deleteAccount']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/service-rule', [AuthController::class, 'updateServiceRule']);
    Route::post('/user/update-fcm-token', [AuthController::class, 'updateFcmToken']);
    Route::post('/provider/mode', [AuthController::class, 'updateProviderMode']);
    Route::put('/provider/profile', [AuthController::class, 'updateProviderProfile']);
    Route::get('/service-rules', [\App\Http\Controllers\Api\ServiceRuleController::class, 'index']);
    Route::get('/provider/services', [ServiceController::class, 'providerIndex']);
    Route::post('/provider/services', [ServiceController::class, 'providerStore']);
    
    // Freelancer Gigs & Orders
    Route::apiResource('provider/gigs', \App\Http\Controllers\Api\Freelancer\GigController::class);
    Route::patch('/provider/gigs/{id}/status', [\App\Http\Controllers\Api\Freelancer\GigController::class, 'updateStatus']);
    Route::get('/provider/gigs/{id}/analytics', [\App\Http\Controllers\Api\Freelancer\GigController::class, 'analytics']);
    Route::get('/provider/gigs/{id}/reviews', [\App\Http\Controllers\Api\Freelancer\GigController::class, 'reviews']);
    
    // Freelancer Orders (Provider Side)
    Route::get('/freelancer/orders', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'index']);
    Route::patch('/freelancer/orders/{id}/status', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'updateStatus']);
    Route::post('/freelancer/orders/{id}/deliver', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'deliverWork']);
    
    // Freelancer Orders (Customer Side)
    // Note: The store method is for creating an order (by customer)
    Route::post('/freelancer/orders', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'store']);
    Route::get('/freelancer/customer/orders', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'customerIndex']);
    Route::post('/freelancer/orders/{id}/approve', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'approveWork']);
    Route::post('/freelancer/orders/{id}/reject', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'rejectWork']);

    // Wallet & Withdrawals
    Route::get('/freelancer/wallet', [\App\Http\Controllers\Api\Freelancer\WalletController::class, 'index']);
    Route::post('/freelancer/withdraw', [\App\Http\Controllers\Api\Freelancer\WalletController::class, 'withdraw']);

    Route::post('/freelancer/gigs/{id}/view', [\App\Http\Controllers\Api\Freelancer\GigOrderController::class, 'view']); 
    
    Route::get('/provider/portfolio', [\App\Http\Controllers\Api\Freelancer\PortfolioController::class, 'index']);
    Route::post('/provider/portfolio', [\App\Http\Controllers\Api\Freelancer\PortfolioController::class, 'store']);
    Route::delete('/provider/portfolio/{portfolio}', [\App\Http\Controllers\Api\Freelancer\PortfolioController::class, 'destroy']);

    // Interests
    Route::post('/interests/toggle', [InterestController::class, 'toggle']);

    // Favorites
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);
    
    // Reviews
    Route::post('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    
    // Chat Routes
    Route::get('/chat/config', [ChatController::class, 'config']);
    Route::get('/chat/conversations', [ChatController::class, 'index']);
    Route::get('/chat/messages/{id}', [ChatController::class, 'show']);
    Route::post('/chat/send', [ChatController::class, 'store']);
    Route::post('/chat/typing', [ChatController::class, 'typing']);
    Route::post('/chat/delivered', [ChatController::class, 'delivered']);
    Route::post('/chat/read', [ChatController::class, 'read']);
    
    // Call Routes
    // Route::get('/call/config', [\App\Http\Controllers\Api\CallController::class, 'config']);
    // Route::post('/call/initiate', [\App\Http\Controllers\Api\CallController::class, 'initiate']);
    // Route::post('/call/accept', [\App\Http\Controllers\Api\CallController::class, 'accept']);
    // Route::post('/call/reject', [\App\Http\Controllers\Api\CallController::class, 'reject']);
    // Route::post('/call/end', [\App\Http\Controllers\Api\CallController::class, 'end']);

    Route::post('/broadcasting/auth', function (Request $request) {
        return Broadcast::auth($request);
    });

    // Deprecated Booking Routes - Migrating to Freelancer Order Structure
    // Route::apiResource('bookings', \App\Http\Controllers\Api\BookingController::class);
});


// Pages
Route::get('/pages/{slug}', [PageController::class, 'show']);
