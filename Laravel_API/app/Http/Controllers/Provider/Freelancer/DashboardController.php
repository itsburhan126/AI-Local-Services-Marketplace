<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Provider.Freelancer.dashboard');
    }

    public function analytics()
    {
        return view('Provider.Freelancer.analytics');
    }

    public function earnings()
    {
        return view('Provider.Freelancer.earnings');
    }

    public function profile()
    {
        return view('Provider.Freelancer.profile.index');
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
        
        if ($request->has('location')) {
             $profile->address = $request->location;
        }

        $profile->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function marketing()
    {
        return view('Provider.Freelancer.marketing');
    }
}
