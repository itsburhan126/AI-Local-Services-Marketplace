<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        return view('Customer.freelancer.settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        if ($request->input('form_id') === 'security') {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            return back()->with('success', 'Password updated successfully.');
        }

        if ($request->input('form_id') === 'notifications') {
            $settings = $user->settings ?? [];
            
            // Toggle logic: If checkbox is checked, it sends 'on' (or value). If unchecked, it sends nothing.
            // But we want to explicitly set true/false.
            // Using $request->has() works if we assume the form is submitted.
            
            $settings['email_notifications'] = $request->has('email_notifications');
            $settings['order_updates'] = $request->has('order_updates');
            $settings['security_alerts'] = $request->has('security_alerts');
            
            $user->settings = $settings;
            $user->save();

            return back()->with('success', 'Notification preferences updated.');
        }
        
        return back()->with('error', 'Invalid request.');
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        $request->validate([
            'password' => 'required|current_password:web',
        ]);

        // Optional: Delete related data or soft delete
        // For now, we'll just delete the user
        
        Auth::guard('web')->logout();
        
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }
}
