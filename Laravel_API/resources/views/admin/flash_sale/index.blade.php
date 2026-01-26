@extends('layouts.admin')

@section('header', 'Flash Sale')

@section('content')
<div class="space-y-8 pb-20" x-data="{ showGraph: false }">
    
    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Campaign Overview</h2>
            <p class="text-sm text-slate-500">Manage your limited-time offers and deals</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Analytics Toggle -->
            <button @click="showGraph = !showGraph" 
                class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 font-medium hover:bg-slate-50 transition-all flex items-center gap-2 text-xs uppercase tracking-wide">
                <i class="fas fa-chart-line" :class="showGraph ? 'text-indigo-500' : 'text-slate-400'"></i>
                <span x-text="showGraph ? 'Hide Analytics' : 'View Analytics'">View Analytics</span>
            </button>
            
            <!-- Live Activity Link -->
            <a href="{{ route('admin.flash-sale.activity') }}" 
               class="px-4 py-2 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition-all flex items-center gap-2 text-xs uppercase tracking-wide shadow-sm hover:shadow-md">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Live Activity
            </a>

            <div class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 {{ $flashSale->is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                <span class="w-2 h-2 rounded-full {{ $flashSale->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                {{ $flashSale->is_active ? 'Active' : 'Inactive' }}
            </div>
        </div>
    </div>
    
    <!-- Analytics Graph Section (Collapsible) -->
    <div x-show="showGraph" x-transition.duration.300ms class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Performance Analytics</h3>
                <p class="text-xs text-slate-500">Last 7 Days vs Previous Period</p>
            </div>
             <select class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
            </select>
        </div>
        <div class="h-64 w-full relative" x-init="
            // Fetch data and init chart if visible
            fetch('{{ route('admin.flash-sale.analytics') }}')
                .then(res => res.json())
                .then(data => {
                    const ctx = document.getElementById('flashSaleChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.dates,
                            datasets: [{
                                label: 'Visitors',
                                data: data.visitors,
                                borderColor: '#6366f1',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                tension: 0.4,
                                fill: true
                            }, {
                                label: 'Bookings',
                                data: data.bookings,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
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
                                y: { beginAtZero: true, grid: { display: true, borderDash: [5, 5] } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                });
        ">
            <canvas id="flashSaleChart"></canvas>
        </div>
    </div>
    
    <!-- Ultra Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Visitor Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-20 transform group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-6xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2 text-indigo-100">
                    <i class="fas fa-eye animate-pulse"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Today's Visitors</span>
                </div>
                <h2 class="text-4xl font-bold mb-1">{{ number_format($todaysVisitors) }}</h2>
                <div class="h-1 w-full bg-black/20 rounded-full mt-3 overflow-hidden">
                    <div class="h-full bg-white/50 w-2/3 animate-[shimmer_2s_infinite]"></div>
                </div>
                <p class="text-[10px] mt-2 text-indigo-100">Live traffic monitoring</p>
            </div>
        </div>

        <!-- Total Items Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-indigo-200 transition-all">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2 text-slate-500">
                    <i class="fas fa-bolt text-amber-500"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Flash Sale Items</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $totalItems }}</h2>
                <p class="text-xs text-slate-400 mt-1">Active deals in campaign</p>
            </div>
        </div>

        <!-- Requests Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-red-200 transition-all">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 text-slate-500">
                        <i class="fas fa-inbox text-red-500"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Requests Pending</span>
                    </div>
                    @if($pendingRequests > 0)
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    @endif
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $pendingRequests }}</h2>
                <p class="text-xs text-slate-400 mt-1">Provider submissions</p>
            </div>
        </div>

        <!-- Total Booked Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-emerald-200 transition-all">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2 text-slate-500">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Total Booked</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $totalBooked }}</h2>
                <p class="text-xs text-slate-400 mt-1">All time conversion</p>
            </div>
        </div>

        <!-- Total Pending Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-orange-200 transition-all">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2 text-slate-500">
                    <i class="fas fa-clock text-orange-500"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Total Pending</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $pendingBookings }}</h2>
                <p class="text-xs text-slate-400 mt-1">Awaiting confirmation</p>
            </div>
        </div>

        <!-- Today Booked Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-blue-200 transition-all">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2 text-slate-500">
                    <i class="fas fa-calendar-check text-blue-500"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Today Booked</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $todayBooked }}</h2>
                <p class="text-xs text-slate-400 mt-1">New sales today</p>
            </div>
        </div>

        <!-- Today Pending Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden group hover:border-pink-200 transition-all">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-pink-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2 text-slate-500">
                    <i class="fas fa-hourglass-half text-pink-500"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Today Pending</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $todayPending }}</h2>
                <p class="text-xs text-slate-400 mt-1">New requests today</p>
            </div>
        </div>
    </div>

    <!-- Main Config & Actions -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Configuration Form -->
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-cog text-slate-400"></i> Settings & Configuration
                </h3>
            </div>

            <form action="{{ route('admin.flash-sale.update-config') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Title & Subtitle -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Campaign Title</label>
                        <input type="text" name="title" value="{{ $flashSale->title }}" required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50 focus:bg-white font-bold text-slate-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subtitle</label>
                        <input type="text" name="subtitle" value="{{ $flashSale->subtitle }}" placeholder="e.g. 24 Hours Only!"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <!-- Timing -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Start Time</label>
                        <input type="datetime-local" name="start_time" value="{{ $flashSale->start_time ? $flashSale->start_time->format('Y-m-d\TH:i') : '' }}"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">End Time</label>
                        <input type="datetime-local" name="end_time" value="{{ $flashSale->end_time ? $flashSale->end_time->format('Y-m-d\TH:i') : '' }}"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <!-- Appearance -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Card Bg Color</label>
                        <div class="flex items-center gap-3 p-2 border border-slate-200 rounded-xl bg-slate-50">
                            <input type="color" name="bg_color" value="{{ $flashSale->bg_color }}" class="h-8 w-8 rounded cursor-pointer border-0 p-0 overflow-hidden">
                            <span class="text-xs font-mono text-slate-600">{{ $flashSale->bg_color }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Text Color</label>
                        <div class="flex items-center gap-3 p-2 border border-slate-200 rounded-xl bg-slate-50">
                            <input type="color" name="text_color" value="{{ $flashSale->text_color }}" class="h-8 w-8 rounded cursor-pointer border-0 p-0 overflow-hidden">
                            <span class="text-xs font-mono text-slate-600">{{ $flashSale->text_color }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Default Discount</label>
                        <div class="relative">
                            <input type="number" name="default_discount_percentage" value="{{ $flashSale->default_discount_percentage }}" min="0" max="100" required
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50 focus:bg-white text-center font-bold">
                            <span class="absolute right-4 top-2.5 text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                </div>

                <!-- Banner Image -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Section Banner Image</label>
                    <div class="flex items-center gap-4">
                        @if($flashSale->banner_image)
                        <div class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 shadow-sm shrink-0">
                            <img src="{{ asset('storage/'.$flashSale->banner_image) }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <input type="file" name="banner_image" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-bold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                            transition-all
                        "/>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $flashSale->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700">Enable Flash Sale Section</span>
                    </label>
                    
                    <button type="submit" class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Actions Column -->
        <div class="space-y-6">
            <!-- Add Services Card -->
            <a href="{{ route('admin.flash-sale.add-items-page') }}" class="block group bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 shadow-lg hover:shadow-indigo-200 transition-all text-center relative overflow-hidden h-64 flex flex-col items-center justify-center">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute inset-0 bg-white/5 group-hover:bg-white/10 transition-all"></div>
                
                <div class="w-16 h-16 bg-white/20 rounded-2xl rotate-3 group-hover:rotate-6 transition-all flex items-center justify-center mb-4 backdrop-blur-sm shadow-inner">
                    <i class="fas fa-layer-group text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2 relative z-10">Add Services</h3>
                <p class="text-indigo-100 text-sm max-w-xs mx-auto relative z-10">Browse top rated, requested, or all services and add them in bulk.</p>
                <div class="mt-6 px-6 py-2 bg-white/20 rounded-full text-white text-xs font-bold backdrop-blur-md group-hover:bg-white/30 transition-all">
                    Open Selection Tool <i class="fas fa-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Add Manual Item Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden transition-all" x-data="{ manualOpen: false }">
                 <div x-show="!manualOpen" class="flex flex-col items-center justify-center text-center py-8">
                    <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-400 group-hover:bg-slate-100 transition-all border border-slate-100">
                        <i class="fas fa-edit text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Add Custom Item</h3>
                    <p class="text-xs text-slate-500 mb-4 max-w-[200px] mx-auto">Manually add an item without linking to a service.</p>
                    <button @click="manualOpen = true" class="px-6 py-2 border-2 border-slate-200 rounded-xl font-bold text-xs text-slate-600 hover:border-slate-800 hover:text-slate-800 transition-all uppercase tracking-wide">
                        Create Custom
                    </button>
                 </div>

                 <div x-show="manualOpen" class="w-full text-left" x-transition>
                    <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">New Manual Item</h3>
                        <button @click="manualOpen = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                    </div>
                    <form action="{{ route('admin.flash-sale.store-item') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="custom_title" required placeholder="Title (e.g. Special Deal)" class="w-full px-4 py-2 rounded-lg border-slate-200 focus:border-indigo-500 text-sm bg-slate-50 focus:bg-white">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="number" step="0.01" name="manual_price" required placeholder="Price ($)" class="w-full px-4 py-2 rounded-lg border-slate-200 focus:border-indigo-500 text-sm bg-slate-50 focus:bg-white">
                            </div>
                            <div>
                                <input type="number" name="discount_percentage" value="{{ $flashSale->default_discount_percentage }}" placeholder="Discount %" class="w-full px-4 py-2 rounded-lg border-slate-200 focus:border-indigo-500 text-sm bg-slate-50 focus:bg-white">
                            </div>
                        </div>
                        <div>
                            <input type="file" name="custom_image" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 transition-all text-sm shadow-md">Add Custom Item</button>
                    </form>
                 </div>
            </div>
        </div>
    </div>

    <!-- Items Grid -->
    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Active Flash Sale Items</h3>
                <p class="text-sm text-slate-500 mt-1">Drag and drop to reorder sequence</p>
            </div>
            <div class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-4 py-2 rounded-full shadow-sm">
                TOTAL ITEMS: {{ $items->count() }}
            </div>
        </div>

        <div id="flash-items-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($items as $item)
                <div class="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 cursor-move" data-id="{{ $item->id }}">
                    <!-- Analytics Overlay Trigger -->
                    <a href="{{ route('admin.flash-sale.item-analytics', $item->id) }}" class="absolute inset-0 z-20" title="View Analytics"></a>
                    
                    <!-- Image -->
                    <div class="h-48 bg-slate-100 relative overflow-hidden">
                        @php
                            $img = $item->custom_image 
                                ? asset('storage/'.$item->custom_image) 
                                : ($item->service && $item->service->image 
                                    ? (str_starts_with($item->service->image, 'http') ? $item->service->image : asset('storage/'.$item->service->image)) 
                                    : null);
                        @endphp
                        @if($img)
                            <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                                <i class="fas fa-image text-3xl opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                        
                        <!-- Hover Action Text -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
                            <span class="bg-white/90 backdrop-blur text-indigo-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                <i class="fas fa-chart-pie mr-2"></i> View Analytics
                            </span>
                        </div>

                        <!-- Discount Badge -->
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10">
                            -{{ $item->discount_percentage }}%
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <h4 class="font-bold text-slate-800 text-sm mb-1 line-clamp-2 h-10 leading-tight">
                            {{ $item->custom_title ?? ($item->service ? $item->service->name : 'No Title') }}
                        </h4>
                        
                        <div class="flex items-center gap-2 mb-4 mt-2">
                            @if($item->service)
                                <span class="text-slate-400 line-through text-xs">${{ $item->service->price }}</span>
                                <span class="text-indigo-600 font-bold text-base">
                                    ${{ number_format($item->service->price * (1 - $item->discount_percentage/100), 2) }}
                                </span>
                            @else
                                <span class="text-indigo-600 font-bold text-base">
                                    {{ $item->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="bg-slate-50 p-2 rounded-lg text-center border border-slate-100">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Views</p>
                                <p class="text-xs font-bold text-slate-700">{{ number_format($item->total_views) }}</p>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-lg text-center border border-slate-100">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Actions</p>
                                <p class="text-xs font-bold text-emerald-600">{{ number_format($item->actions) }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-slate-50 relative z-30">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded cursor-grab active:cursor-grabbing">
                                <i class="fas fa-grip-vertical mr-1"></i> Order: {{ $item->order }}
                            </span>
                            
                            <form action="{{ route('admin.flash-sale.destroy-item', $item->id) }}" method="POST" onsubmit="return confirm('Remove this item?');" class="relative z-30">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Remove Item">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fas fa-box-open text-4xl"></i>
                    </div>
                    <p class="text-slate-400 text-lg font-medium">No items in this flash sale yet.</p>
                    <p class="text-slate-400 text-sm">Add services or custom items to get started.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- SortableJS & AlpineJS -->
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // SortableJS Initialization
        var el = document.getElementById('flash-items-grid');
        if(el) {
            var sortable = Sortable.create(el, {
                animation: 200,
                ghostClass: 'opacity-50',
                dragClass: 'scale-105',
                onEnd: function (evt) {
                    var ids = [];
                    Array.from(el.children).forEach(function (card) {
                        if(card.hasAttribute('data-id')) {
                            ids.push(card.getAttribute('data-id'));
                        }
                    });

                    fetch('{{ route("admin.flash-sale.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: ids })
                    })
                    .then(res => res.json())
                    .then(data => console.log('Reordered'))
                    .catch(err => console.error(err));
                }
            });
        }
    });
</script>
@endsection
