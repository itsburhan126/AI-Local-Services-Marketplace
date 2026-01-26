@extends('layouts.admin')

@section('title', 'Bookings Management')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Bookings</h1>
            <p class="text-gray-500 mt-1">Monitor and manage all service orders</p>
        </div>
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Search ID, customer, provider..." 
                class="pl-10 pr-4 py-2.5 rounded-xl border-none bg-white shadow-sm focus:ring-2 focus:ring-indigo-500/20 w-64 text-sm text-gray-700">
            <i class="fas fa-search absolute left-3.5 top-3 text-gray-400"></i>
        </form>
    </div>

    <!-- Status Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
        @php
            $statuses = ['all', 'pending', 'confirmed', 'completed', 'cancelled', 'disputed'];
            $currentStatus = request('status', 'all');
        @endphp
        @foreach($statuses as $status)
        <a href="{{ route('admin.bookings.index', ['status' => $status == 'all' ? null : $status]) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-all whitespace-nowrap
           {{ ($status == 'all' && !$currentStatus) || $currentStatus == $status 
              ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' 
              : 'bg-white text-gray-600 hover:bg-gray-50' }}">
            {{ ucfirst($status) }}
        </a>
        @endforeach
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Order ID</th>
                        <th class="px-4 py-4">Service</th>
                        <th class="px-4 py-4">Customer</th>
                        <th class="px-4 py-4">Provider</th>
                        <th class="px-4 py-4">Scheduled</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Amount</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <span class="font-mono text-sm font-bold text-gray-700">#{{ $booking->id }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-indigo-100 flex items-center justify-center text-indigo-500 text-xs">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <span class="font-medium text-gray-700 text-sm">{{ $booking->service->name ?? 'Deleted Service' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-800">{{ $booking->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-800">{{ $booking->provider->name ?? 'Unassigned' }}</div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <div>{{ $booking->scheduled_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $booking->scheduled_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $booking->status == 'completed' ? 'bg-green-100 text-green-600' : 
                                   ($booking->status == 'confirmed' ? 'bg-blue-100 text-blue-600' : 
                                   ($booking->status == 'pending' ? 'bg-amber-100 text-amber-600' : 
                                   ($booking->status == 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500'))) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right font-bold text-gray-800">
                            ${{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                               class="inline-flex w-8 h-8 rounded-lg items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                                <p>No bookings found matching criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
