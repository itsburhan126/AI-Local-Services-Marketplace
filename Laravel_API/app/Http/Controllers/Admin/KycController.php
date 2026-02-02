<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function index()
    {
        $pendingRequests = User::where('kyc_status', 'pending')->latest()->get();
        $verifiedUsers = User::where('kyc_status', 'verified')->latest()->get();
        $rejectedUsers = User::where('kyc_status', 'rejected')->latest()->get();

        return view('admin.kyc.index', compact('pendingRequests', 'verifiedUsers', 'rejectedUsers'));
    }

    public function verified()
    {
        $verifiedUsers = User::where('kyc_status', 'verified')->latest()->paginate(20);
        return view('admin.kyc.verified', compact('verifiedUsers'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.kyc.show', compact('user'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->kyc_status = 'verified';
        $user->save();

        return redirect()->route('admin.kyc.index')->with('success', 'User KYC approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($id);
        $user->kyc_status = 'rejected';
        
        // Preserve existing KYC data and add rejection info
        $kycData = $user->kyc_data ?? [];
        $kycData['rejection_reason'] = $request->rejection_reason;
        $kycData['rejected_at'] = now();
        
        $user->kyc_data = $kycData;
        $user->save();

        return redirect()->route('admin.kyc.index')->with('success', 'User KYC rejected with reason.');
    }
}
