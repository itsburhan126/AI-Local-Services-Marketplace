<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        return view('Customer.freelancer.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }
            $path = $request->file('avatar')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function requestKyc(Request $request)
    {
        $user = Auth::guard('web')->user();
        
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
}
