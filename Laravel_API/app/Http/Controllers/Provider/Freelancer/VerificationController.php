<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('Provider.Freelancer.verification.index', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'document_type' => 'required|string',
            'phone' => 'required|string',
            'document_front' => 'required|image|max:4096',
            'document_back' => 'required|image|max:4096',
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

        $user->phone = $request->phone;
        $user->kyc_status = 'pending';
        $user->kyc_data = $kycData;
        $user->save();

        return back()->with('success', 'KYC verification request submitted successfully.');
    }
}
