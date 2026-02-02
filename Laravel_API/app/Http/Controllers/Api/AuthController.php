<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string|in:user,provider',
            'service_rule' => 'nullable|string|in:freelancer,local_service',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'user',
                'service_rule' => $request->service_rule ?? null,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=4F46E5&color=ffffff&rounded=true&bold=true&font-size=0.33',
            ]);

            if (($request->role ?? 'user') === 'provider') {
                ProviderProfile::create([
                    'user_id' => $user->id,
                    'mode' => $request->service_rule ?? null // Allow null initially
                ]);
                
                // Load the profile so it's returned in the response
                $user->load('providerProfile');
            }

            // Send Welcome Notification
            $user->notify(new WelcomeNotification());

            $token = $user->createToken('auth_token')->plainTextToken;
            
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->status === 'blocked') {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been blocked. Please contact support.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($user->role === 'provider') {
            $user->load('providerProfile');
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 200);
    }

    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string',
            'google_id' => 'required|string',
            'avatar' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::where('email', $request->email)->first();
            $isNewUser = false;

            if ($user && $user->status === 'blocked') {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Your account has been blocked. Please contact support.'
                ], 403);
            }

            if (!$user) {
                $isNewUser = true;

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make(Str::random(16)),
                    'google_id' => $request->google_id,
                    'avatar' => $request->avatar ?? 'default.png',
                    'status' => 'active'
                ]);
            } else {
                if ($request->avatar) {
                    $user->update(['avatar' => $request->avatar]);
                }
                if (!$user->google_id) {
                    $user->update(['google_id' => $request->google_id]);
                }
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'is_new_user' => $isNewUser
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkUser(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $exists = $user ? true : false;
        $isBlocked = $user && $user->status === 'blocked';
        return response()->json([
            'status' => true,
            'exists' => $exists,
            'is_blocked' => $isBlocked,
        ]);
    }

    public function updateServiceRule(Request $request)
    {
        $request->validate([
            'service_rule' => 'required|in:freelancer,local_service',
        ]);

        $user = $request->user();
        $user->service_rule = $request->service_rule;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Service rule updated successfully',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        
        // Revoke all tokens
        $user->tokens()->delete();
        
        // Delete user
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully'
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        
        // Load provider profile if user is a provider
        if ($user->role === 'provider') {
            $user->load(['providerProfile', 'freelancerPortfolios']);
        }
        
        return response()->json([
            'status' => true,
            'data' => [
                'user' => $user,
            ]
        ]);
    }

    public function updateProviderProfile(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'provider') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:255',
            'avatar' => 'nullable|image|max:5120', // 5MB Max
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        if (!empty($validated['name'])) {
            $user->update(['name' => $validated['name']]);
        }

        $profile = ProviderProfile::firstOrCreate(['user_id' => $user->id]);
        $profile->update([
            'company_name' => $validated['company_name'] ?? $profile->company_name,
            'about' => $validated['about'] ?? $profile->about,
            'address' => $validated['address'] ?? $profile->address,
            'country' => $validated['country'] ?? $profile->country,
            'languages' => array_key_exists('languages', $validated) ? $validated['languages'] : $profile->languages,
            'skills' => array_key_exists('skills', $validated) ? $validated['skills'] : $profile->skills,
        ]);

        $user->load(['providerProfile', 'freelancerPortfolios']);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function updateProviderMode(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:local_service,freelancer',
        ]);

        $user = $request->user();
        if (!$user || $user->role !== 'provider') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $profile = ProviderProfile::firstOrCreate(['user_id' => $user->id]);
        $profile->update(['mode' => $request->mode]);

        // Also update users table service_rule
        $user->update(['service_rule' => $request->mode]);

        return response()->json([
            'status' => true,
            'message' => 'Provider mode updated successfully',
            'data' => [
                'mode' => $user->service_rule,
            ],
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'FCM token updated successfully',
        ]);
    }
}
