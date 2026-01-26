@extends('layouts.admin')

@section('title', 'Provider Subscriptions')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Provider Subscriptions</h1>
            <p class="text-gray-500 mt-1">Monitor active provider subscriptions</p>
        </div>
        <div class="flex gap-3">
             <div class="glass-panel px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-medium text-gray-600 cursor-pointer hover:bg-white/50 transition-colors">
                <i class="fas fa-filter text-indigo-500"></i>
                <span>Filter Status</span>
             </div>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Provider</th>
                        <th class="px-4 py-4">Plan</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Dates</th>
                        <th class="px-4 py-4">Payment</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden shadow-sm">
                                    @if($sub->user->avatar)
                                        <img src="{{ $sub->user->avatar }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-indigo-100 text-indigo-500 font-bold">
                                            {{ substr($sub->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $sub->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $sub->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-medium text-gray-700">{{ $sub->plan->name }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-600',
                                    'expired' => 'bg-red-100 text-red-600',
                                    'cancelled' => 'bg-gray-100 text-gray-500',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm">
                                <p class="text-gray-800"><span class="text-gray-500 text-xs">Start:</span> {{ $sub->start_date->format('M d, Y') }}</p>
                                <p class="text-gray-800"><span class="text-gray-500 text-xs">End:</span> {{ $sub->end_date->format('M d, Y') }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">${{ number_format($sub->amount_paid, 2) }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $sub->payment_id ?? 'N/A' }}</p>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-history text-3xl text-gray-300"></i>
                                <p>No subscriptions found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection
