@extends('layouts.admin')

@section('title', 'Customer Profile')

@section('content')
<div class="content-transition">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-lg hover:border-transparent transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Customer Profile</h1>
            <p class="text-sm text-gray-500">View customer details and history</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="glass-panel rounded-2xl p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-indigo-500 to-purple-600 opacity-10"></div>
                
                <div class="relative">
                    <div class="w-24 h-24 mx-auto rounded-full p-1 bg-white shadow-xl mb-4">
                        <img src="{{ $user->profile_photo_url }}" class="w-full h-full rounded-full object-cover">
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-indigo-500 font-medium text-sm mb-6">Member since {{ $user->created_at->format('M Y') }}</p>

                    <div class="space-y-4 text-left bg-gray-50 p-6 rounded-xl">
                        <div class="flex items-center gap-3 text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm text-indigo-500">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span class="text-sm font-medium">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm text-indigo-500">
                                <i class="fas fa-phone"></i>
                            </div>
                            <span class="text-sm font-medium">{{ $user->phone ?? 'No phone added' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm text-indigo-500">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span class="text-sm font-medium">Customer</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="p-4 rounded-xl bg-blue-50 text-blue-600">
                            <p class="text-2xl font-bold">{{ $user->total_bookings }}</p>
                            <p class="text-xs uppercase font-bold opacity-70">Total Orders</p>
                        </div>
                        <div class="p-4 rounded-xl bg-green-50 text-green-600">
                            <p class="text-2xl font-bold">{{ $user->completed_bookings_count }}</p>
                            <p class="text-xs uppercase font-bold opacity-70">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-history text-indigo-500"></i> Recent Orders
                </h3>
                
                @if($recent_bookings->count() > 0)
                <div class="space-y-4">
                    @foreach($recent_bookings as $booking)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden">
                                <img src="{{ $booking->service->image ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $booking->service->name ?? 'Service Removed' }}</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $booking->created_at->format('M d, Y') }} • 
                                    <span class="{{ $booking->status == 'completed' ? 'text-green-500' : ($booking->status == 'cancelled' ? 'text-red-500' : 'text-amber-500') }} font-medium capitalize">
                                        {{ $booking->status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800">${{ number_format($booking->total_amount, 2) }}</p>
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-xs text-indigo-500 font-medium hover:underline">View Order</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-inbox text-2xl"></i>
                    </div>
                    <p class="text-gray-500">No orders found for this customer.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
