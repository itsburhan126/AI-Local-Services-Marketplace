@extends('layouts.freelancer')

@section('title', 'Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header & Tabs -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">Analytics</h1>
        
        <div class="border-b border-slate-200">
            <nav class="-mb-px flex space-x-8">
                <a href="#" class="border-primary-500 text-slate-900 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                    Overview
                </a>
                <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                    Repeat business
                </a>
            </nav>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm mb-8">
        <div class="grid grid-cols-1 md:grid-cols-5 divide-y md:divide-y-0 md:divide-x divide-slate-100">
            
            <!-- Earnings to date -->
            <div class="p-6 text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Earnings to date</span>
                    <i class="fas fa-question-circle text-slate-300 text-xs cursor-help" title="Total earnings since joining"></i>
                </div>
                <p class="text-3xl font-bold text-slate-800">$611.20</p>
            </div>

            <!-- Avg. Selling Price -->
            <div class="p-6 text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Avg. selling price</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">$34.03</p>
            </div>

            <!-- On-time delivery -->
            <div class="p-6 text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">On-time delivery</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">100%</p>
            </div>

            <!-- Orders completed -->
            <div class="p-6 text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Orders completed</span>
                </div>
                <p class="text-3xl font-bold text-slate-800">23</p>
            </div>

            <!-- Earned in Current Month -->
            <div class="p-6 text-center hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Earned in {{ date('F') }}</span>
                    <i class="fas fa-question-circle text-slate-300 text-xs cursor-help" title="Earnings for this month"></i>
                </div>
                <p class="text-3xl font-bold text-slate-800">$24.00</p>
            </div>

        </div>
    </div>

    <!-- Overview Chart Section -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h2 class="text-lg font-bold text-slate-800">Overview</h2>
            
            <div class="relative mt-2 sm:mt-0" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="text-sm font-medium text-slate-600 hover:text-slate-800 flex items-center gap-1">
                    Last 30 days <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>
                <div x-show="open" 
                     x-transition 
                     class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border border-slate-100 z-10 py-1" 
                     style="display: none;">
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">Last 30 days</a>
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Last 3 months</a>
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Last year</a>
                </div>
            </div>
        </div>

        <!-- Custom Legend -->
        <div class="flex flex-wrap gap-6 mb-6 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-cyan-400"></span>
                <span class="text-slate-500">Sales</span>
                <span class="font-bold text-slate-700">$0</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                <span class="text-slate-500">Cancelled</span>
                <span class="font-bold text-slate-700">$0</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-800"></span>
                <span class="text-slate-500">Completed</span>
                <span class="font-bold text-slate-700">0</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-slate-500">New Orders</span>
                <span class="font-bold text-slate-700">0</span>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="relative h-64 w-full">
            <canvas id="analyticsChart"></canvas>
        </div>
        
        <!-- X-Axis Labels Simulation (if needed precise control, otherwise Chart.js handles it) -->
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        
        // Generate dates for the last 30 days
        const labels = [];
        for (let i = 29; i >= 0; i--) {
            const d = new Date();
            d.setDate(d.getDate() - i);
            labels.push(d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }

        // Mock Data
        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Sales',
                    data: Array(30).fill(0).map(() => Math.floor(Math.random() * 5)), // Mostly zeros/low
                    borderColor: '#22d3ee', // Cyan-400
                    backgroundColor: '#22d3ee',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.1
                },
                {
                    label: 'New Orders',
                    data: Array(30).fill(0).map(() => Math.floor(Math.random() * 2)), 
                    borderColor: '#22c55e', // Green-500
                    backgroundColor: '#22c55e',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.1
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Using custom legend
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        boxWidth: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: '#f1f5f9',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 10
                            },
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [5, 5],
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            callback: function(value) {
                                return '$' + value;
                            },
                            stepSize: 1
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        };

        new Chart(ctx, config);
    });
</script>
@endpush
@endsection
