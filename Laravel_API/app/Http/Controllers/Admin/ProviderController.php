<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProviderProfile;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = User::where('role', 'provider')
            ->with('providerProfile')
            ->latest()
            ->paginate(10);
        return view('admin.providers.index', compact('providers'));
    }

    public function show($id)
    {
        $provider = User::where('role', 'provider')
            ->with(['providerProfile', 'services' => function($query) {
                $query->latest();
            }, 'gigs' => function($query) {
                $query->latest();
            }])
            ->findOrFail($id);
        return view('admin.providers.show', compact('provider'));
    }

    public function updateStatus(Request $request, $id)
    {
        $provider = User::where('role', 'provider')->findOrFail($id);
        
        $status = $request->status; // active, inactive, pending
        
        // Update User Status
        $provider->update(['status' => $status]);

        // Update Profile Verification if approved
        if ($status === 'active' && $provider->providerProfile) {
            $provider->providerProfile->update(['is_verified' => true]);
        }

        return back()->with('success', 'Provider status updated successfully.');
    }
}
