@extends('layouts.freelancer')

@section('title', 'Manage Orders')

@section('content')
<div class="w-full space-y-6" x-data="{ activeTab: 'pending' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manage Orders</h2>
            <p class="text-slate-500 mt-1">Track and manage your gig orders</p>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Pending Orders</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $pendingOrders->count() }}</h3>
            </div>
            <div class="h-10 w-10 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-lg"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Active Orders</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $activeOrders->count() }}</h3>
            </div>
            <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-spinner text-lg"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Completed Orders</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $completedOrders->count() }}</h3>
            </div>
            <div class="h-10 w-10 bg-green-50 text-green-600 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="flex space-x-8 px-4">
            <button @click="activeTab = 'pending'" :class="activeTab === 'pending' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Pending
            </button>
            <button @click="activeTab = 'active'" :class="activeTab === 'active' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Active
            </button>
            <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Completed
            </button>
        </div>
    </div>

    <!-- Order Lists -->
    <div class="space-y-4">
        
        <!-- Pending Tab -->
        <div x-show="activeTab === 'pending'" class="space-y-4">
            @forelse($pendingOrders as $order)
                @include('Provider.Freelancer.orders.partials.order-card', ['order' => $order])
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-slate-200 border-dashed">
                    <div class="h-16 w-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">No pending orders</h3>
                    <p class="text-slate-500 mt-1">New orders will appear here.</p>
                </div>
            @endforelse
        </div>

        <!-- Active Tab -->
        <div x-show="activeTab === 'active'" class="space-y-4" style="display: none;">
            @forelse($activeOrders as $order)
                @include('Provider.Freelancer.orders.partials.order-card', ['order' => $order])
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-slate-200 border-dashed">
                    <div class="h-16 w-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">No active orders</h3>
                    <p class="text-slate-500 mt-1">Orders in progress will appear here.</p>
                </div>
            @endforelse
        </div>

        <!-- Completed Tab -->
        <div x-show="activeTab === 'completed'" class="space-y-4" style="display: none;">
            @forelse($completedOrders as $order)
                @include('Provider.Freelancer.orders.partials.order-card', ['order' => $order])
            @empty
                <div class="text-center py-12 bg-white rounded-xl border border-slate-200 border-dashed">
                    <div class="h-16 w-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">No completed orders</h3>
                    <p class="text-slate-500 mt-1">Your order history will appear here.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
