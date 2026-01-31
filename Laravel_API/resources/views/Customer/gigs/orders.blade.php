@extends('layouts.customer')

@section('title', 'Manage Orders')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header & Breadcrumbs -->
        <div class="mb-8">
            <nav class="flex mb-3" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="/" class="hover:text-indigo-600 transition-colors">Home</a></li>
                    <li><svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li><span class="text-gray-900 font-medium">Orders</span></li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Manage Orders</h1>
            <p class="mt-2 text-gray-600">Track and manage your service purchases</p>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <!-- Status Tabs -->
                <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg overflow-x-auto w-full sm:w-auto">
                    @foreach(['all', 'active', 'completed', 'cancelled'] as $status)
                        <a href="{{ route('customer.orders.index', array_merge(request()->except('page'), ['status' => $status])) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md focus:outline-none transition-colors whitespace-nowrap {{ (request('status', 'all') == $status) ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                           {{ ucfirst($status) }}
                        </a>
                    @endforeach
                </div>

                <!-- Search -->
                <form action="{{ route('customer.orders.index') }}" method="GET" class="relative w-full sm:w-64">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Search orders...">
                </form>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- Gig Image -->
                            <div class="flex-shrink-0 relative h-24 w-32 bg-gray-100 rounded-lg overflow-hidden border border-gray-100">
                                <img class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300" 
                                     loading="lazy"
                                     src="{{ $order->gig->thumbnail_image ? asset('storage/' . $order->gig->thumbnail_image) : asset('images/default-gig.jpg') }}" 
                                     alt="{{ $order->gig->title }}"
                                     onload="this.classList.remove('opacity-0')"
                                     onerror="this.src='https://via.placeholder.com/128x96?text=Gig'">
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-0.5 rounded">#{{ $order->order_number ?? strtoupper(substr($order->id, 0, 8)) }}</span>
                                            <span class="text-xs text-gray-500">• {{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-indigo-600 transition-colors">
                                            <a href="{{ route('customer.gigs.order.details', $order->id) }}">
                                                {{ $order->gig->title }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <span class="text-gray-400 mr-1">Seller:</span>
                                                <span class="font-medium text-gray-900">{{ $order->gig->provider->name ?? 'Unknown' }}</span>
                                            </div>
                                            <span class="text-gray-300">|</span>
                                            <div class="text-sm text-gray-600">
                                                <span class="text-gray-400 mr-1">Package:</span>
                                                <span class="font-medium text-gray-900">{{ $order->package->name ?? 'Standard' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status & Price -->
                                    <div class="flex flex-row md:flex-col items-center md:items-end justify-between gap-4 md:gap-2">
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</div>
                                            <div class="text-xs text-gray-500">Total Price</div>
                                        </div>
                                        <div>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'active' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                                    'delivered' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                ];
                                                $statusClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-current opacity-75"></span>
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions Footer -->
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Due on <span class="font-medium text-gray-900">{{ $order->created_at->addDays($order->package->delivery_time ?? 3)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('customer.chat.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Contact Seller
                            </a>
                            <a href="{{ route('customer.gigs.order.details', $order->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16 px-4">
                <div class="mx-auto h-24 w-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No orders found</h3>
                <p class="text-gray-500 max-w-sm mx-auto mb-8">You haven't placed any orders yet, or no orders match your current filters.</p>
                <a href="{{ route('customer.gigs.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    Explore Services
                </a>
            </div>
        @endif
    </div>
</div>
@endsection