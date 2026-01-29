@extends('layouts.freelancer')

@section('title', 'Withdraw')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ selected: '{{ $userMethods->first()->id ?? '' }}' }">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Withdraw Funds</h1>
        <p class="text-slate-500 mt-2 text-sm">Available balance: ${{ number_format($availableBalance, 2) }}</p>
    </div>

    <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)]">
        <form action="{{ route('provider.freelancer.withdraw.request') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Amount</label>
                <input type="number" step="0.01" min="1" name="amount" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:border-slate-800 focus:ring-0" required>
                @error('amount')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Payout Method</label>
                <select name="user_payout_method_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:border-slate-800 focus:ring-0" x-model="selected" required>
                    @foreach($userMethods as $um)
                        <option value="{{ $um->id }}">
                            {{ $um->payoutMethod->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_payout_method_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-xs text-slate-500">
                @if($userMethods->count() > 0)
                    <p>Min: ${{ number_format($userMethods->first()->payoutMethod->min_amount ?? 0, 2) }} @if($userMethods->first()->payoutMethod->max_amount) • Max: ${{ number_format($userMethods->first()->payoutMethod->max_amount, 2) }} @endif</p>
                @else
                    <p>Add a payout method first.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('provider.freelancer.payout.index') }}" class="px-4 py-2.5 bg-white border border-slate-300 rounded-[4px] text-sm font-semibold text-slate-700 hover:bg-slate-50">Manage methods</a>
                <button type="submit" class="px-4 py-2.5 bg-black text-white rounded-[4px] text-sm font-bold hover:bg-slate-800">Submit request</button>
            </div>
        </form>
    </div>
</div>
@endsection
