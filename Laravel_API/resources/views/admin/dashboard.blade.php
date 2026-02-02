@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="content-transition">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 font-jakarta">Dashboard Overview</h1>
        <p class="text-gray-500 mt-2">Welcome back, Admin! Here's what's happening today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl -mr-16 -mt-16 transition-all group-hover:bg-indigo-500/20"></div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up mr-1"></i>
                <span class="font-medium">+12.5%</span>
                <span class="text-gray-400 ml-2">from last month</span>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl -mr-16 -mt-16 transition-all group-hover:bg-purple-500/20"></div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalBookings) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up mr-1"></i>
                <span class="font-medium">+8.2%</span>
                <span class="text-gray-400 ml-2">from last month</span>
            </div>
        </div>

        <!-- Active Providers -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl -mr-16 -mt-16 transition-all group-hover:bg-blue-500/20"></div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Providers</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalProviders) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-blue-600">
                <span class="font-medium">New Joiners</span>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute top-0 right-0 w-32 h-32 bg-pink-500/10 rounded-full blur-2xl -mr-16 -mt-16 transition-all group-hover:bg-pink-500/20"></div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Customers</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalCustomers) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="flex items-center text-sm text-pink-600">
                <span class="font-medium">Growing Fast</span>
            </div>
        </div>
    </div>

    <div class="glass-panel p-6 rounded-2xl mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Revenue Analytics</h2>
                <p class="text-sm text-gray-500">Monthly revenue overview for {{ date('Y') }}</p>
            </div>
            <select class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5">
                <option selected>This Year</option>
                <option value="last">Last Year</option>
            </select>
        </div>
        <div class="relative h-80 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Bookings Table -->
        <div class="lg:col-span-2 glass-panel rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-800">Recent Bookings</h2>
                <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                            <th class="pb-3 pl-2">Service</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50/50">
                        @forelse($recentBookings as $booking)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="py-3 pl-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-100 flex items-center justify-center text-indigo-500 text-xs">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <span class="font-medium text-gray-700 text-sm">{{ $booking->service->name ?? 'Unknown Service' }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $booking->user ? $booking->user->profile_photo_url : 'https://ui-avatars.com/api/?name=Guest&background=4F46E5&color=ffffff&rounded=true&bold=true' }}" 
                                         class="w-8 h-8 rounded-full object-cover shadow-sm border border-gray-100">
                                    <div class="text-sm font-medium text-gray-700">{{ $booking->user->name ?? 'Guest' }}</div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $booking->status == 'completed' ? 'bg-green-100 text-green-600' : 
                                       ($booking->status == 'pending' ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-500') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-right font-medium text-gray-700">
                                ${{ number_format($booking->total_amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 text-sm">No recent bookings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions / Mini Chart Placeholder -->
        <div class="glass-panel rounded-2xl p-6 flex flex-col h-full">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Quick Actions</h2>
            <div class="space-y-4 flex-1">
                <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 hover:bg-amber-50 hover:text-amber-600 transition-all group border border-transparent hover:border-amber-100 relative">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 group-hover:text-amber-700">Withdrawals</h4>
                        <p class="text-xs text-gray-500">Review requests</p>
                    </div>
                    @if($pendingWithdrawals > 0)
                    <span class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                        {{ $pendingWithdrawals }} Pending
                    </span>
                    @endif
                </a>

                <a href="{{ route('admin.push-notifications.create') }}" class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 transition-all group border border-transparent hover:border-indigo-100">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 group-hover:text-indigo-700">Send Notification</h4>
                        <p class="text-xs text-gray-500">Blast message to users</p>
                    </div>
                </a>

                <a href="{{ route('admin.ai.settings') }}" class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 hover:bg-purple-50 hover:text-purple-600 transition-all group border border-transparent hover:border-purple-100">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 group-hover:text-purple-700">AI Settings</h4>
                        <p class="text-xs text-gray-500">Configure AI tools</p>
                    </div>
                </a>

                <a href="{{ route('admin.services.create') }}" class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 hover:bg-pink-50 hover:text-pink-600 transition-all group border border-transparent hover:border-pink-100">
                    <div class="w-10 h-10 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 group-hover:text-pink-700">Add Service</h4>
                        <p class="text-xs text-gray-500">Create new service</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
