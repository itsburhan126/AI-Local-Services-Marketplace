<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WalletTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPendingBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-pending-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move pending funds to wallet balance if available_at has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = WalletTransaction::where('status', 'pending')
            ->where('available_at', '<=', now())
            ->get();

        if ($transactions->isEmpty()) {
            $this->info("No pending transactions ready for processing.");
            return;
        }

        $count = 0;
        foreach ($transactions as $transaction) {
            try {
                DB::transaction(function () use ($transaction, &$count) {
                    $user = User::lockForUpdate()->find($transaction->user_id);
                    if ($user) {
                        // Move funds
                        if ($user->pending_balance >= $transaction->amount) {
                            $user->pending_balance -= $transaction->amount;
                        } else {
                            // Should not happen, but handle gracefully
                            $user->pending_balance = 0;
                            Log::warning("User {$user->id} pending balance negative or insufficient for transaction {$transaction->id}");
                        }
                        
                        $user->wallet_balance += $transaction->amount;
                        $user->save();

                        // Update transaction
                        $transaction->status = 'completed';
                        $transaction->save();
                        $count++;
                    }
                });
            } catch (\Exception $e) {
                Log::error("Failed to process transaction {$transaction->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Processed {$count} transactions.");
    }
}
