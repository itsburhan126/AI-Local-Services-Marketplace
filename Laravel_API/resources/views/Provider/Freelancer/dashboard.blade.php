@extends('layouts.freelancer')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-12">
    <!-- Welcome & Stats Section -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-900 text-white shadow-xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 h-80 w-80 rounded-full bg-primary-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 h-80 w-80 rounded-full bg-indigo-500/20 blur-3xl"></div>
        
        <div class="relative p-8 md:p-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/10 border border-white/20 text-xs font-medium text-white/90 backdrop-blur-sm">
                            {{ $sellerLevel }}
                        </span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-2">Welcome back, {{ Auth::guard('web')->user()->name ?? 'Freelancer' }}! 👋</h2>
                    <p class="text-slate-300 max-w-xl">You have <span class="text-white font-semibold">{{ $activeOrdersCount }} active orders</span> and <span class="text-white font-semibold">{{ $newMessagesCount }} new messages</span> waiting for your response.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10 min-w-[160px]">
                        <p class="text-xs text-slate-300 uppercase tracking-wider font-semibold mb-1">Wallet Balance</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold text-white">${{ number_format($walletBalance, 2) }}</span>
                        </div>
                        <p class="text-xs text-emerald-400 mt-1">Available</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10 min-w-[160px]">
                        <p class="text-xs text-slate-300 uppercase tracking-wider font-semibold mb-1">Joined</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold text-white">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                        <p class="text-xs text-emerald-400 mt-1">Member since</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Profile Completion -->
            <div class="mt-8 pt-6 border-t border-white/10">
                <div class="flex items-center justify-between gap-4 mb-2">
                    <span class="text-sm font-medium text-slate-300">Profile Completion</span>
                    <span class="text-sm font-bold text-white">{{ $completion }}%</span>
                </div>
                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary-500 to-indigo-500 w-[{{ $completion }}%] rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]" style="width: {{ $completion }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Average Rating -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-primary-100 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <span class="flex items-center text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-full">
                     {{ number_format($averageRating, 1) }} / 5.0
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Average Rating</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($averageRating, 1) }}</h3>
                <p class="text-xs text-slate-400 mt-1">Based on {{ $totalReviews }} reviews</p>
            </div>
        </div>

        <!-- Clicks -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-primary-100 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-mouse-pointer text-xl"></i>
                </div>
                <span class="flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i> Total
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Gig Views</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalGigViews) }}</h3>
                <p class="text-xs text-slate-400 mt-1">All time views</p>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-primary-100 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-box-open text-xl"></i>
                </div>
                <span class="flex items-center text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-full">
                    {{ $activeOrdersCount }} Active
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Orders</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalOrdersCount }}</h3>
                <p class="text-xs text-slate-400 mt-1">Completed orders: {{ $completedOrdersCount }}</p>
            </div>
        </div>

        <!-- Success Score -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:border-primary-100 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-medal text-xl"></i>
                </div>
                <span class="flex items-center text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-full">
                    {{ $sellerLevel }}
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Success Score</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $successScore }}%</h3>
                <p class="text-xs text-slate-400 mt-1">Based on reviews</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column: Chart & Orders -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Analytics Chart -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Earnings Overview</h3>
                        <p class="text-sm text-slate-500">Your earnings performance over time</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select class="text-sm border border-slate-200 bg-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-slate-600 font-medium cursor-pointer transition-shadow">
                            <option>Last 30 Days</option>
                            <option>Last 3 Months</option>
                            <option>This Year</option>
                        </select>
                        <button class="p-2 text-slate-400 hover:text-primary-600 transition-colors">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                <div class="h-80 w-full relative">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Active Orders -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Active Orders</h3>
                        <p class="text-sm text-slate-500">Manage your ongoing projects</p>
                    </div>
                    <a href="{{ route('provider.freelancer.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors bg-primary-50 hover:bg-primary-100 px-4 py-2 rounded-lg">
                        View All Orders <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Client / Gig</th>
                                <th class="px-6 py-4">Timeline</th>
                                <th class="px-6 py-4">Price</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($activeOrders as $order)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            @if($order->user && $order->user->avatar)
                                                <img src="{{ asset('storage/' . $order->user->avatar) }}" alt="{{ $order->user->name }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm ring-2 ring-white shadow-sm">
                                                    {{ strtoupper(substr($order->user->name ?? 'U', 0, 2)) }}
                                                </div>
                                            @endif
                                            <span class="absolute -bottom-1 -right-1 h-3 w-3 bg-green-500 border-2 border-white rounded-full"></span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 group-hover:text-primary-600 transition-colors">{{ $order->user->name ?? 'Unknown User' }}</p>
                                            <p class="text-xs text-slate-500">{{ Str::limit($order->gig->title ?? ($order->package->name ?? 'Order #' . $order->id), 30) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-700">{{ $order->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-slate-500 font-medium">{{ $order->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('provider.freelancer.orders.show', $order->id) }}" class="text-slate-400 hover:text-primary-600 transition-colors p-2 hover:bg-slate-100 rounded-lg">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                                    No active orders found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Messages & Activity -->
        <div class="space-y-8">
            <!-- Messages Widget -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[400px]">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Messages</h3>
                    <a href="{{ route('provider.freelancer.chat.index') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">View All</a>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-slate-50 scrollbar-thin scrollbar-thumb-slate-200">
                    @forelse($recentMessages as $message)
                    <div class="p-4 hover:bg-slate-50 transition-colors cursor-pointer relative group">
                        <a href="{{ route('provider.freelancer.chat.index') }}" class="flex gap-3">
                            <div class="relative">
                                @if($message->sender && $message->sender->avatar)
                                    <img src="{{ asset('storage/' . $message->sender->avatar) }}" alt="{{ $message->sender->name }}" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs">
                                        {{ strtoupper(substr($message->sender->name ?? 'U', 0, 2)) }}
                                    </div>
                                @endif
                                <span class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="text-sm font-bold text-slate-800 group-hover:text-primary-600 transition-colors">{{ $message->sender->name ?? 'Unknown' }}</h4>
                                    <span class="text-xs text-slate-400">{{ $message->created_at->shortAbsoluteDiffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-600 truncate font-medium">{{ Str::limit($message->message, 40) }}</p>
                            </div>
                        </a>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('provider.freelancer.chat.index') }}" class="h-8 w-8 rounded-full bg-white shadow-sm border border-slate-200 text-slate-400 hover:text-primary-600 flex items-center justify-center">
                                <i class="fas fa-reply text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500 text-sm">
                        No recent messages.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity / To-Do -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">To-Do List</h3>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($todoList as $todo)
                    <a href="{{ $todo['link'] }}" class="flex items-start gap-3 p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-primary-200 hover:shadow-sm transition-all group">
                        <div class="mt-1 text-slate-400 group-hover:text-primary-600">
                            <i class="{{ $todo['icon'] }}"></i>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium text-slate-700 block group-hover:text-primary-600 transition-colors">{{ $todo['title'] }}</span>
                            <span class="text-xs text-slate-500">{{ $todo['subtitle'] }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 text-green-500 mb-3">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-sm text-slate-500">All caught up! No pending tasks.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Config -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(14, 165, 233, 0.2)'); // primary-500 with opacity
    gradient.addColorStop(1, 'rgba(14, 165, 233, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($earningsLabels),
            datasets: [{
                label: 'Earnings',
                data: @json($earningsData),
                borderColor: '#0ea5e9',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#0ea5e9',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#0ea5e9',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: {
                        size: 13,
                        family: "'Inter', sans-serif"
                    },
                    bodyFont: {
                        size: 13,
                        family: "'Inter', sans-serif"
                    },
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 11
                        },
                        color: '#64748b',
                        callback: function(value) {
                            return '$' + value;
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 11
                        },
                        color: '#64748b',
                        padding: 10
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>
@endsection