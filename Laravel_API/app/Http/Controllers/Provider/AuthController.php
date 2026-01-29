<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Freelancer Register
    public function registerFreelancer(Request $request)
    {
        return $this->register($request, 'freelancer');
    }

    // Local Provider Register
    public function registerLocal(Request $request)
    {
        return $this->register($request, 'local_service');
    }

    // Shared Register Logic
    protected function register(Request $request, $serviceRule)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'provider',
                'service_rule' => $serviceRule,
                'avatar' => 'default.png',
                'status' => 'active',
            ]);

            ProviderProfile::create([
                'user_id' => $user->id,
                'mode' => $serviceRule,
            ]);

            DB::commit();

            Auth::guard('web')->login($user);

            // Redirect to dashboard based on role
            if ($serviceRule === 'freelancer') {
                return redirect()->route('provider.freelancer.dashboard')->with('success', 'Registration successful! Welcome to your dashboard.');
            }

            // For now redirect to home with success message for others
            return redirect('/')->with('success', 'Registration successful! Welcome to the platform.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }

    // Shared Login Logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::guard('web')->user();
            
            if ($user->role !== 'provider') {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'This account is not authorized as a provider.',
                ]);
            }

            if ($user->service_rule === 'freelancer') {
                return redirect()->route('provider.freelancer.dashboard');
            }

            return redirect()->intended('/'); // Update this to provider dashboard later
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
