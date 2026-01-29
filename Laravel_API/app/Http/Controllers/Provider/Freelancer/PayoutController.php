<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\PayoutMethod;
use App\Models\UserPayoutMethod;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function index()
    {
        $adminMethods = PayoutMethod::where('is_active', true)->get();
        $userMethods = UserPayoutMethod::where('user_id', Auth::id())->with('payoutMethod')->get();
        
        return view('Provider.Freelancer.payout_methods.index', compact('adminMethods', 'userMethods'));
    }

    public function create(PayoutMethod $payoutMethod)
    {
        // Check if user already has this method
        $exists = UserPayoutMethod::where('user_id', Auth::id())
            ->where('payout_method_id', $payoutMethod->id)
            ->exists();
            
        if ($exists) {
            return redirect()->route('provider.freelancer.payout.index')->with('error', 'You have already added this payout method.');
        }

        return view('Provider.Freelancer.payout_methods.create', compact('payoutMethod'));
    }

    public function store(Request $request, PayoutMethod $payoutMethod)
    {
        $rules = [];
        if ($payoutMethod->fields) {
            foreach ($payoutMethod->fields as $field) {
                $rules['field_values.' . $field['name']] = 'required'; // Basic validation, can be improved based on type
            }
        }

        $request->validate($rules);

        UserPayoutMethod::create([
            'user_id' => Auth::id(),
            'payout_method_id' => $payoutMethod->id,
            'field_values' => $request->input('field_values', []),
            'is_default' => !UserPayoutMethod::where('user_id', Auth::id())->exists(), // First one is default
        ]);

        return redirect()->route('provider.freelancer.payout.index')->with('success', 'Payout method added successfully.');
    }

    public function destroy(UserPayoutMethod $userPayoutMethod)
    {
        if ($userPayoutMethod->user_id !== Auth::id()) {
            abort(403);
        }

        $userPayoutMethod->delete();
        return redirect()->route('provider.freelancer.payout.index')->with('success', 'Payout method removed successfully.');
    }
    
    public function withdrawPage()
    {
        $user = Auth::user();
        $availableBalance = $user->wallet_balance; // Assuming this field exists based on migration list
        $userMethods = UserPayoutMethod::where('user_id', $user->id)->with('payoutMethod')->get();
        
        return view('Provider.Freelancer.payout_methods.withdraw', compact('availableBalance', 'userMethods'));
    }

    public function withdrawRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'user_payout_method_id' => 'required|exists:user_payout_methods,id',
        ]);

        $user = Auth::user();
        
        if ($request->amount > $user->wallet_balance) {
            return back()->with('error', 'Insufficient balance.');
        }

        $userMethod = UserPayoutMethod::where('id', $request->user_payout_method_id)
            ->where('user_id', $user->id)
            ->with('payoutMethod')
            ->firstOrFail();

        // Check min/max limits
        if ($request->amount < $userMethod->payoutMethod->min_amount) {
            return back()->with('error', 'Minimum withdrawal amount is $' . $userMethod->payoutMethod->min_amount);
        }
        if ($userMethod->payoutMethod->max_amount && $request->amount > $userMethod->payoutMethod->max_amount) {
            return back()->with('error', 'Maximum withdrawal amount is $' . $userMethod->payoutMethod->max_amount);
        }

        // Create Withdrawal Request
        Withdrawal::create([
            'provider_id' => $user->id,
            'amount' => $request->amount,
            'method' => $userMethod->payoutMethod->name,
            'account_details' => json_encode($userMethod->field_values), // Snapshot details
            'status' => 'pending',
        ]);

        // Deduct Balance (Optionally, or just hold it. Usually better to deduct or mark as held)
        // For now, let's assume we just create the request and the admin approves it.
        // But to prevent double spend, we should probably deduct it or have a 'held_balance'.
        // Given simple requirements, let's deduct immediately or handle via wallet transactions.
        
        // Let's create a "debit" transaction
        $user->wallet_balance -= $request->amount;
        $user->save();

        \App\Models\WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => -$request->amount,
            'type' => 'debit',
            'description' => 'Withdrawal Request via ' . $userMethod->payoutMethod->name,
            'status' => 'pending', // Pending until processed
            'reference_id' => $userMethod->id, // Or withdrawal ID if we had it first
            'reference_type' => 'Withdrawal',
            'available_at' => now(),
        ]);

        return redirect()->route('provider.freelancer.earnings')->with('success', 'Withdrawal request submitted successfully.');
    }
}
