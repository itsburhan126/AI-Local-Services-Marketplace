@extends('layouts.freelancer')

@section('title', 'Payout Methods')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 tracking-tight">Payout Methods</h1>
            <p class="text-slate-500 mt-2 text-base">Add a method and withdraw your earnings.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] lg:col-span-2">
            <h3 class="text-base font-bold text-slate-800 mb-6">Available payout methods</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($adminMethods as $method)
                <div class="border border-slate-200 rounded-lg p-5 bg-white">
                    <div class="flex items-center gap-3 mb-4">
                        @if($method->logo)
                        <img src="{{ asset('storage/'.$method->logo) }}" alt="{{ $method->name }}" class="w-9 h-9 rounded-md object-cover">
                        @else
                        <div class="w-9 h-9 rounded-md bg-slate-100 flex items-center justify-center text-slate-500">
                            <i class="fas fa-wallet"></i>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $method->name }}</p>
                            <p class="text-xs text-slate-500">Processing {{ $method->processing_time_days ? $method->processing_time_days.' days' : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 mb-4">
                        <p>Min: ${{ number_format($method->min_amount ?? 0, 2) }} @if($method->max_amount) • Max: ${{ number_format($method->max_amount, 2) }} @endif</p>
                    </div>
                    <a href="{{ route('provider.freelancer.payout.create', $method->id) }}" class="w-full inline-flex justify-center rounded-[4px] px-4 py-2 bg-black text-white text-sm font-bold hover:bg-slate-800 transition-colors">
                        Add this method
                    </a>
                </div>
                @empty
                <p class="text-sm text-slate-500">No payout methods configured by admin.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)]">
            <h3 class="text-base font-bold text-slate-800 mb-6">Your payout methods</h3>
            <div class="space-y-4">
                @forelse($userMethods as $um)
                <div class="border border-slate-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $um->payoutMethod->name }}</p>
                            <p class="text-xs text-slate-500">Default: {{ $um->is_default ? 'Yes' : 'No' }}</p>
                        </div>
                        <form action="{{ route('provider.freelancer.payout.destroy', $um->id) }}" method="POST" onsubmit="return confirm('Remove this payout method?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">Remove</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-500">You have not added any payout method.</p>
                @endforelse
            </div>
            <div class="mt-6">
                <a href="{{ route('provider.freelancer.withdraw.page') }}" class="w-full inline-flex justify-center rounded-[4px] px-4 py-2 bg-black text-white text-sm font-bold hover:bg-slate-800 transition-colors">
                    Go to withdraw
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
