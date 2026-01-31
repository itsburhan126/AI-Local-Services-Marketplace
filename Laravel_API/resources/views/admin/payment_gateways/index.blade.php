@extends('layouts.admin')

@section('title', 'Payment Gateways')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Payment Gateways</h1>
            <p class="text-gray-500 mt-1">Manage payment methods and API credentials</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Gateway</th>
                        <th class="px-4 py-4">Mode</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($gateways as $gateway)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shadow-sm flex items-center justify-center text-xl">
                                    @if($gateway->name === 'paypal')
                                        <i class="fab fa-paypal text-blue-600"></i>
                                    @elseif($gateway->name === 'stripe')
                                        <i class="fab fa-stripe text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-credit-card text-gray-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $gateway->title }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($gateway->name) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @if($gateway->mode === 'sandbox')
                                <span class="px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700 text-sm font-semibold border border-yellow-100">
                                    <i class="fas fa-flask mr-1"></i> Sandbox
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg bg-green-50 text-green-700 text-sm font-semibold border border-green-100">
                                    <i class="fas fa-check-circle mr-1"></i> Live
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($gateway->is_active)
                                <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-semibold">Active</span>
                            @else
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.payment-gateways.edit', $gateway->id) }}" 
                                   class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-md shadow-indigo-500/30 hover:bg-indigo-700 transition-all">
                                    Configure
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            No gateways found. Run seeders.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
