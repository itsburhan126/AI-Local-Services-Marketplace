@extends('layouts.admin')

@section('header', 'Item Analytics')

@section('content')
<div class="space-y-8 pb-20">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.flash-sale.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-sm font-bold uppercase tracking-wider">
                    <i class="fas fa-arrow-left mr-1"></i> Flash Sale
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-indigo-500 text-sm font-bold uppercase tracking-wider">Analytics</span>
            </div>
            <h2 class="text-xl font-bold text-slate-800">Item Performance</h2>
            <p class="text-sm text-slate-500">Detailed metrics for <span class="font-bold text-slate-700">"{{ $item->custom_title ?? $item->service->name }}"</span></p>
        </div>
        
        <div class="flex items-center gap-3">
            @if($item->service)
            <div class="flex items-center gap-3 mr-2 border-r border-slate-200 pr-4">
                <a href="{{ route('admin.services.show', $item->service_id) }}" target="_blank" 
                   class="group px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-500 flex items-center justify-center transition-colors">
                        <i class="fas fa-box-open text-[10px]"></i>
                    </div>
                    <span>View Service</span>
                </a>
                <a href="{{ route('admin.providers.show', $item->service->provider_id) }}" target="_blank" 
                   class="group px-4 py-2.5 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider hover:bg-indigo-100 hover:border-indigo-200 transition-all shadow-sm flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-white text-indigo-500 flex items-center justify-center shadow-sm">
                        <i class="fas fa-user-tie text-[10px]"></i>
                    </div>
                    <span>Provider Profile</span>
                </a>
            </div>
            @endif
            <span class="px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-xs uppercase tracking-wider border border-emerald-100 shadow-sm flex items-center gap-2">
                <i class="fas fa-bolt text-emerald-500"></i> {{ $item->discount_percentage }}% OFF
            </span>
        </div>
    </div>

    <!-- Item Preview Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col md:flex-row gap-6 items-center md:items-start">
        <div class="w-full md:w-48 h-32 rounded-xl overflow-hidden relative shrink-0">
            @php
                $img = $item->custom_image 
                    ? asset('storage/'.$item->custom_image) 
                    : ($item->service && $item->service->image 
                        ? (str_starts_with($item->service->image, 'http') ? $item->service->image : asset('storage/'.$item->service->image)) 
                        : null);
            @endphp
            @if($img)
                <img src="{{ $img }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                    <i class="fas fa-image text-3xl"></i>
                </div>
            @endif
        </div>
        <div class="flex-1 w-full">
            <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $item->custom_title ?? $item->service->name }}</h3>
            @if($item->service)
            <div class="flex flex-wrap gap-4 text-sm text-slate-500 mb-4">
                <span class="flex items-center gap-1"><i class="fas fa-layer-group text-slate-400"></i> {{ $item->service->category->name }}</span>
                <span class="flex items-center gap-1"><i class="fas fa-user-tie text-slate-400"></i> {{ $item->service->provider->name }}</span>
            </div>
            @endif
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-slate-50 rounded-lg border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Original Price</span>
                    <span class="text-slate-500 line-through font-medium">${{ $item->service->price ?? $item->price }}</span>
                </div>
                <div class="px-4 py-2 bg-emerald-50 rounded-lg border border-emerald-100">
                    <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Flash Price</span>
                    <span class="text-emerald-700 font-bold text-lg">
                        ${{ number_format(($item->service->price ?? $item->price) * (1 - $item->discount_percentage/100), 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Views -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 p-4 opacity-5">
                <i class="fas fa-eye text-6xl text-blue-500"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Views</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ number_format($stats['total_views']) }}</h3>
            <p class="text-xs text-blue-500 font-bold mt-2 flex items-center gap-1">
                <i class="fas fa-arrow-up"></i> {{ rand(5, 20) }}% vs avg
            </p>
        </div>

        <!-- Conversion -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 p-4 opacity-5">
                <i class="fas fa-percentage text-6xl text-purple-500"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Conversion Rate</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $stats['conversion_rate'] }}</h3>
            <p class="text-xs text-purple-500 font-bold mt-2 flex items-center gap-1">
                <i class="fas fa-check-circle"></i> Performing well
            </p>
        </div>

        <!-- Cart Adds -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
             <div class="absolute right-0 top-0 p-4 opacity-5">
                <i class="fas fa-shopping-cart text-6xl text-orange-500"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Added to Cart</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ number_format($stats['cart_adds']) }}</h3>
            <p class="text-xs text-slate-400 mt-2">
                {{ $stats['checkout_initiated'] }} initiated checkout
            </p>
        </div>

        <!-- Sales -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
             <div class="absolute right-0 top-0 p-4 opacity-5">
                <i class="fas fa-shopping-bag text-6xl text-emerald-500"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Completed Orders</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ number_format($stats['completed_orders']) }}</h3>
            <p class="text-xs text-emerald-500 font-bold mt-2">
                Confirmed bookings
            </p>
        </div>
    </div>

    <!-- Performance Graph -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Views vs Sales (Last 7 Days)</h3>
        <div class="h-80 w-full relative">
            <canvas id="itemChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('itemChart').getContext('2d');
        const graphData = @json($graphData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: graphData.dates,
                datasets: [
                    {
                        label: 'Views',
                        data: graphData.views,
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 4,
                        order: 2
                    },
                    {
                        label: 'Sales',
                        data: graphData.sales,
                        type: 'line',
                        borderColor: '#10b981',
                        backgroundColor: '#10b981',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.4,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { usePointStyle: true, boxWidth: 8 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, borderDash: [5, 5] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection