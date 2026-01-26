<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('provider')
            ->latest()
            ->paginate(10);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $withdrawal->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Withdrawal request updated successfully.');
    }
}
