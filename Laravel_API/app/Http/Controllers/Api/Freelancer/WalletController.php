<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Professional Fix: Process pending balances for this user dynamically on access
        // This ensures funds are available immediately when the date is reached
        $pendingTransactions = WalletTransaction::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('available_at', '<=', now())
            ->get();

        if ($pendingTransactions->isNotEmpty()) {
            DB::transaction(function () use ($user, $pendingTransactions) {
                // Refresh user instance to get latest balances
                $user->refresh(); 
                
                foreach ($pendingTransactions as $transaction) {
                    // Move funds
                    if ($user->pending_balance >= $transaction->amount) {
                        $user->pending_balance -= $transaction->amount;
                    } else {
                        $user->pending_balance = 0;
                    }
                    
                    $user->wallet_balance += $transaction->amount;
                    
                    // Update transaction
                    $transaction->status = 'completed';
                    $transaction->save();
                }
                
                $user->save();
            });
        }

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return response()->json([
            'status' => true,
            'data' => [
                'wallet_balance' => $user->wallet_balance,
                'pending_balance' => $user->pending_balance,
                'transactions' => $transactions
            ]
        ]);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
            'account_details' => 'required|array',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        if ($user->wallet_balance < $amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient wallet balance. Pending funds are not available for withdrawal.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Deduct from wallet
            $user->wallet_balance -= $amount;
            $user->save();

            // Create Withdrawal Request
            $withdrawal = Withdrawal::create([
                'provider_id' => $user->id,
                'amount' => $amount,
                'method' => $request->method,
                'account_details' => $request->account_details,
                'status' => 'pending',
            ]);

            // Create Transaction Record
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'debit',
                'description' => 'Withdrawal request #' . $withdrawal->id,
                'reference_id' => $withdrawal->id,
                'reference_type' => 'withdrawal',
                'status' => 'completed', // Transaction is completed (deducted), but withdrawal is pending
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Withdrawal request submitted successfully',
                'data' => $withdrawal
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to process withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }
}
