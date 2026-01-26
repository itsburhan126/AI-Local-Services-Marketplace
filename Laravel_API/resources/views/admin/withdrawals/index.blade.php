@extends('layouts.admin')

@section('header', 'Withdrawal Requests')

@section('content')
<div class="content-transition">
    <div class="glass-panel rounded-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Withdrawal Requests</h2>
                <p class="text-sm text-gray-500">Manage provider payout requests</p>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-sm font-semibold hover:bg-indigo-100 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="pb-3 pl-4">Provider</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Method</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="py-4 pl-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                    {{ substr($withdrawal->provider->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $withdrawal->provider->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $withdrawal->provider->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 font-bold text-gray-800">${{ number_format($withdrawal->amount, 2) }}</td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-university text-gray-400"></i>
                                <span class="text-sm text-gray-600 capitalize">{{ $withdrawal->method }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-500">
                            {{ $withdrawal->created_at->format('M d, Y') }}
                            <br>
                            <span class="text-xs text-gray-400">{{ $withdrawal->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="py-4">
                            @if($withdrawal->status == 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-600 border border-amber-200">
                                    Pending
                                </span>
                            @elseif($withdrawal->status == 'approved')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600 border border-green-200">
                                    Paid
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600 border border-red-200">
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-right pr-4">
                            @if($withdrawal->status == 'pending')
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.withdrawals.update', $withdrawal->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 hover:text-green-700 transition-all" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.withdrawals.update', $withdrawal->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-all" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400 text-2xl">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <p class="text-lg font-medium">No withdrawal requests found</p>
                                <p class="text-sm">Providers haven't requested any payouts yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>
@endsection
