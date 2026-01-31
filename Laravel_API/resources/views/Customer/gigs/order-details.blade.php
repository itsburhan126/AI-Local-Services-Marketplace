@extends('layouts.customer')

@section('title', 'Order Details #' . $order->id)

@section('content')
<div class="bg-gray-50 min-h-screen py-12 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Header -->
        <div class="mb-10">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            <span class="text-sm font-medium text-gray-500">Orders</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            <span class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Order Details</h1>
                    <div class="flex items-center gap-3 mt-2 text-gray-600">
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            #{{ $order->id }}
                        </span>
                        <span class="text-sm">Placed on {{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('customer.gigs.order.invoice', $order->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Invoice
                    </a>
                    @if($order->status === 'completed')
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Leave Review
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Tracker -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 mb-8">
            <div class="relative">
                <!-- Progress Bar Background -->
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2 rounded-full"></div>
                
                <!-- Active Progress Bar (Dynamic Width) -->
                @php
                    $progress = 0;
                    if($order->status === 'pending') $progress = 15;
                    elseif(in_array($order->status, ['accepted', 'approved', 'in_progress'])) $progress = 50;
                    elseif($order->status === 'delivered') $progress = 85;
                    elseif($order->status === 'completed') $progress = 100;
                @endphp
                <div class="absolute top-1/2 left-0 h-1 bg-emerald-500 -translate-y-1/2 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>

                <!-- Steps -->
                <div class="relative flex justify-between">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center group">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white bg-emerald-600 shadow-md z-10">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm font-bold text-gray-900">Placed</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('M d, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center group">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white {{ in_array($order->status, ['accepted', 'in_progress', 'delivered', 'completed']) ? 'bg-emerald-600 shadow-md' : 'bg-gray-200' }} z-10 transition-colors duration-300">
                            @if(in_array($order->status, ['accepted', 'in_progress', 'delivered', 'completed']))
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            @else
                                <span class="text-gray-500 text-sm font-semibold">2</span>
                            @endif
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm font-bold {{ in_array($order->status, ['accepted', 'in_progress', 'delivered', 'completed']) ? 'text-gray-900' : 'text-gray-400' }}">In Progress</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center group">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white {{ in_array($order->status, ['delivered', 'completed']) ? 'bg-emerald-600 shadow-md' : 'bg-gray-200' }} z-10 transition-colors duration-300">
                            @if(in_array($order->status, ['delivered', 'completed']))
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @else
                                <span class="text-gray-500 text-sm font-semibold">3</span>
                            @endif
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm font-bold {{ in_array($order->status, ['delivered', 'completed']) ? 'text-gray-900' : 'text-gray-400' }}">Delivered</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex flex-col items-center group">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white {{ $order->status === 'completed' ? 'bg-emerald-600 shadow-md' : 'bg-gray-200' }} z-10 transition-colors duration-300">
                             @if($order->status === 'completed')
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <span class="text-gray-500 text-sm font-semibold">4</span>
                            @endif
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm font-bold {{ $order->status === 'completed' ? 'text-gray-900' : 'text-gray-400' }}">Completed</p>
                            @if($order->status === 'completed')
                                <p class="text-xs text-gray-500 mt-1">{{ $order->updated_at->format('M d') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Gig Details & Requirements -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Service Details Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">Service Details</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($order->gig->category->name ?? 'Service') }}
                        </span>
                    </div>
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-48 h-48 flex-shrink-0">
                                <img src="{{ asset('storage/' . $order->gig->thumbnail_image) }}" alt="{{ $order->gig->title }}" class="w-full h-full object-cover rounded-xl shadow-sm hover:shadow-md transition-shadow">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    <a href="{{ route('customer.gigs.show', $order->gig->slug) }}" class="hover:text-emerald-600 transition-colors">
                                        {{ $order->gig->title }}
                                    </a>
                                </h3>
                                <div class="flex flex-wrap gap-4 mb-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $order->package->delivery_days }} Days Delivery
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        {{ $order->package->revisions }} Revisions
                                    </span>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Package Features</h4>
                                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @if(isset($order->package->features) && is_array($order->package->features))
                                            @foreach($order->package->features as $feature)
                                                <li class="flex items-start text-sm text-gray-700">
                                                    <svg class="w-4 h-4 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Requirements -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                     <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">Order Requirements</h2>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        @if($order->scheduled_at)
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Appointment Time</h4>
                                <p class="text-gray-600 mt-1">{{ $order->scheduled_at->format('l, F j, Y') }} at {{ $order->scheduled_at->format('h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->address)
                        <div class="flex items-start gap-4">
                             <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Service Location</h4>
                                <p class="text-gray-600 mt-1">{{ $order->address }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->notes)
                        <div class="flex items-start gap-4">
                             <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900">Instructions & Notes</h4>
                                <div class="mt-2 p-4 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 leading-relaxed">
                                    {{ $order->notes }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-8">
                
                <!-- Seller Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Service Provider</h3>
                    <div class="flex items-center gap-4 mb-6">
                        <img src="{{ $order->provider->profile_photo_url ? asset('storage/' . $order->provider->profile_photo_url) : 'https://ui-avatars.com/api/?name='.urlencode($order->provider->name).'&background=10b981&color=fff' }}" 
                             alt="{{ $order->provider->name }}" 
                             class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-sm"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order->provider->name) }}&background=10b981&color=fff'">
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">{{ $order->provider->name }}</h4>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span>{{ number_format($order->provider->rating ?? 5.0, 1) }} ({{ $order->provider->reviews_count ?? 0 }} reviews)</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('customer.chat.index', ['user_id' => $order->provider->id]) }}" class="block w-full text-center py-2.5 bg-gray-900 text-white rounded-xl font-bold text-sm hover:bg-gray-800 transition-colors shadow-lg shadow-gray-200">
                        Message Provider
                    </a>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Summary</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Package Price</span>
                            <span class="font-medium text-gray-900">{{ $currency_symbol ?? '$' }}{{ number_format($order->total_amount - $order->service_fee, 2) }}</span>
                        </div>
                        @php
                            $packagePrice = $order->total_amount - $order->service_fee;
                            $feePercentage = $packagePrice > 0 ? ($order->service_fee / $packagePrice) * 100 : 0;
                        @endphp
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Service Fee @if($feePercentage > 0) ({{ number_format($feePercentage, 2) }}%) @endif</span>
                            <span class="font-medium text-gray-900">{{ $currency_symbol ?? '$' }}{{ number_format($order->service_fee, 2) }}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-end">
                            <span class="text-sm font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-extrabold text-emerald-600">{{ $currency_symbol ?? '$' }}{{ number_format($order->total_amount, 2) }}</span>
                        </div>

                        <div class="mt-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="text-emerald-700 font-medium">Payment Status</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase
                                    @if($order->payment_status === 'paid') bg-green-200 text-green-800
                                    @elseif($order->payment_status === 'pending') bg-yellow-200 text-yellow-800
                                    @else bg-red-200 text-red-800 @endif">
                                    {{ $order->payment_status }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-emerald-700 font-medium">Method</span>
                                <span class="font-bold text-emerald-900 capitalize">{{ $order->payment_method }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Box -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl">
                    <h4 class="font-bold text-lg mb-2">Need Help?</h4>
                    <p class="text-indigo-100 text-sm mb-4 leading-relaxed">If you have any issues with this order, please contact our support team immediately.</p>
                    <button onclick="showComingSoonToast()" class="w-full py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-bold transition-colors">
                        Contact Support
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showComingSoonToast() {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'toast bg-white border-l-4 border-blue-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] transform translate-x-full animate-slide-in';
        toast.innerHTML = `
            <div class="text-blue-500">
                <i class="fas fa-info-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Coming Soon</h4>
                <p class="text-sm text-gray-600">This feature is under development.</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.replace('animate-slide-in', 'animate-slide-out');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>
@endpush
@endsection
