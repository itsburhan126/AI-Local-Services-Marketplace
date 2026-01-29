@extends('layouts.freelancer')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="w-full max-w-screen-2xl mx-auto" x-data="{ activeTab: 'activity' }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('provider.freelancer.orders.index') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-primary-600 hover:border-primary-200 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900">Order #{{ $order->id }}</h1>
                    @php
                        $statusStyles = [
                            'pending' => ['bg-yellow-100', 'text-yellow-700'],
                            'accepted' => ['bg-blue-100', 'text-blue-700'],
                            'in_progress' => ['bg-indigo-100', 'text-indigo-700'],
                            'ready' => ['bg-purple-100', 'text-purple-700'],
                            'completed' => ['bg-green-100', 'text-green-700'],
                            'cancelled' => ['bg-red-100', 'text-red-700'],
                            'refunded' => ['bg-red-100', 'text-red-700'],
                        ];
                        $style = $statusStyles[$order->status] ?? ['bg-slate-100', 'text-slate-700'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $style[0] }} {{ $style[1] }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
                <p class="text-slate-500 text-sm mt-1">
                    Placed on {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('h:i A') }}
                </p>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center gap-3">
            @if($order->status === 'pending')
                <form action="{{ route('provider.freelancer.orders.decline', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                        Decline
                    </button>
                </form>
                <form action="{{ route('provider.freelancer.orders.accept', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 shadow-sm shadow-primary-200 transition-colors">
                        Accept Order
                    </button>
                </form>
            @elseif(in_array($order->status, ['accepted', 'in_progress']))
                <form action="{{ route('provider.freelancer.orders.deliver', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-md shadow-green-200 transition-all flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Deliver Now
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left Column (Main Content) -->
        <div class="xl:col-span-2 space-y-6">
            
            <!-- Tabs Navigation -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-1.5 flex gap-1 overflow-x-auto">
                <button @click="activeTab = 'activity'" 
                    :class="{ 'bg-primary-50 text-primary-700 shadow-sm': activeTab === 'activity', 'text-slate-600 hover:bg-slate-50': activeTab !== 'activity' }"
                    class="flex-1 min-w-[100px] py-2.5 px-4 rounded-lg text-sm font-semibold transition-all text-center">
                    Activity & Status
                </button>
                <button @click="activeTab = 'details'" 
                    :class="{ 'bg-primary-50 text-primary-700 shadow-sm': activeTab === 'details', 'text-slate-600 hover:bg-slate-50': activeTab !== 'details' }"
                    class="flex-1 min-w-[100px] py-2.5 px-4 rounded-lg text-sm font-semibold transition-all text-center">
                    Order Details
                </button>
                <button @click="activeTab = 'requirements'" 
                    :class="{ 'bg-primary-50 text-primary-700 shadow-sm': activeTab === 'requirements', 'text-slate-600 hover:bg-slate-50': activeTab !== 'requirements' }"
                    class="flex-1 min-w-[100px] py-2.5 px-4 rounded-lg text-sm font-semibold transition-all text-center">
                    Requirements
                </button>
            </div>

            <!-- Tab: Activity -->
            <div x-show="activeTab === 'activity'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                
                <!-- Status Stepper -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6">Order Progress</h3>
                    <div class="relative">
                        <div class="absolute top-4 left-0 w-full h-0.5 bg-slate-100"></div>
                        <div class="grid grid-cols-4 relative">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shadow ring-4 ring-white z-10">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">Placed</p>
                                    <p class="text-[10px] text-slate-500">{{ $order->created_at->format('M d') }}</p>
                                </div>
                            </div>
                            <!-- Step 2 -->
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['accepted', 'in_progress', 'ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center shadow ring-4 ring-white z-10 transition-colors">
                                    <i class="fas fa-handshake text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold {{ in_array($order->status, ['accepted', 'in_progress', 'ready', 'completed']) ? 'text-slate-900' : 'text-slate-400' }}">Accepted</p>
                                </div>
                            </div>
                            <!-- Step 3 -->
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['in_progress', 'ready', 'completed']) ? 'bg-primary-500 text-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center shadow ring-4 ring-white z-10 transition-colors">
                                    <i class="fas fa-tools text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold {{ in_array($order->status, ['in_progress', 'ready', 'completed']) ? 'text-slate-900' : 'text-slate-400' }}">In Progress</p>
                                </div>
                            </div>
                            <!-- Step 4 -->
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['completed']) ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-400' }} flex items-center justify-center shadow ring-4 ring-white z-10 transition-colors">
                                    <i class="fas fa-flag-checkered text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold {{ in_array($order->status, ['completed']) ? 'text-slate-900' : 'text-slate-400' }}">Delivered</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages / Chat Placeholder -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col min-h-[400px]">
                    <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="far fa-comments text-slate-400"></i> Messages
                        </h3>
                        <span class="text-xs text-slate-400">Encrypted & Secure</span>
                    </div>
                    
                    <div class="flex-1 p-6 bg-slate-50/30 overflow-y-auto space-y-4">
                        <!-- System Message -->
                        <div class="flex justify-center">
                            <span class="bg-slate-200 text-slate-600 text-xs px-3 py-1 rounded-full">
                                Order started on {{ $order->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <!-- Buyer Message (Mockup) -->
                        <div class="flex gap-3">
                            <img src="{{ $order->user->profile_photo_url ?? asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full object-cover mt-1">
                            <div class="bg-white border border-slate-200 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                <p class="text-slate-700 text-sm">Hi! I just placed the order. Please let me know if you need anything else.</p>
                                <span class="text-[10px] text-slate-400 mt-1 block">{{ $order->created_at->format('h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="p-4 bg-white border-t border-slate-100">
                        <div class="relative">
                            <textarea placeholder="Type your message here..." class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-300 focus:ring-4 focus:ring-primary-50 transition-all resize-none h-14"></textarea>
                            <button class="absolute right-2 top-2 bottom-2 w-10 bg-primary-600 hover:bg-primary-700 text-white rounded-lg flex items-center justify-center transition-colors">
                                <i class="fas fa-paper-plane text-sm"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-2 px-1">
                            <div class="flex gap-2 text-slate-400">
                                <button class="hover:text-slate-600 transition-colors"><i class="fas fa-paperclip"></i></button>
                                <button class="hover:text-slate-600 transition-colors"><i class="far fa-smile"></i></button>
                            </div>
                            <span class="text-xs text-slate-400">Press Enter to send</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Details -->
            <div x-show="activeTab === 'details'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex gap-6">
                            @if($order->gig)
                                <img src="{{ $order->gig->thumbnail_image ? asset('storage/' . $order->gig->thumbnail_image) : 'https://via.placeholder.com/300' }}" class="w-32 h-24 object-cover rounded-lg shadow-sm border border-slate-100">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $order->gig->title }}</h3>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-semibold">Gig</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-sm text-slate-500">{{ $order->gig->category->name ?? 'Category' }}</span>
                                    </div>
                                    <p class="text-slate-600 text-sm leading-relaxed">{{ $order->gig->description }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-8">
                            <h4 class="font-bold text-slate-800 mb-4">Package Features</h4>
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-200">
                                    <span class="font-bold text-slate-700">{{ $order->package->name ?? ucfirst($order->package->tier ?? 'Standard') }}</span>
                                    <span class="font-bold text-slate-900 text-lg">${{ number_format($order->package->price ?? 0, 2) }}</span>
                                </div>
                                <ul class="space-y-2">
                                    @if(isset($order->package->delivery_time))
                                        <li class="flex items-center gap-3 text-sm text-slate-600">
                                            <i class="far fa-clock text-primary-500 w-5"></i>
                                            {{ $order->package->delivery_time }} Days Delivery
                                        </li>
                                    @endif
                                    @if(isset($order->package->revisions))
                                        <li class="flex items-center gap-3 text-sm text-slate-600">
                                            <i class="fas fa-sync-alt text-primary-500 w-5"></i>
                                            {{ $order->package->revisions }} Revisions
                                        </li>
                                    @endif
                                    <!-- Add more features dynamically if available -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Requirements -->
            <div x-show="activeTab === 'requirements'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Buyer Requirements</h3>
                    @if($order->notes)
                        <div class="prose prose-slate max-w-none bg-slate-50 p-6 rounded-xl border border-slate-100">
                            {{ $order->notes }}
                        </div>
                    @else
                        <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                <i class="fas fa-clipboard-list text-slate-300"></i>
                            </div>
                            <p class="text-slate-500 font-medium">No requirements provided</p>
                            <p class="text-xs text-slate-400">The buyer hasn't submitted any specific instructions.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column (Sidebar) -->
        <div class="space-y-6">
            
            <!-- Countdown / Delivery Time -->
            @if($order->scheduled_at || ($order->package && $order->package->delivery_time))
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg shadow-slate-200 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-xl"></div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Time Left to Deliver</h3>
                <div class="text-3xl font-mono font-bold mb-2">
                    @if($order->scheduled_at)
                         {{ $order->scheduled_at->diffForHumans(null, true) }}
                    @else
                         {{ $order->package->delivery_time }} Days
                    @endif
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-300 bg-white/10 px-3 py-1.5 rounded-lg w-fit">
                    <i class="far fa-calendar"></i>
                    Due: {{ $order->scheduled_at ? $order->scheduled_at->format('M d, Y') : \Carbon\Carbon::parse($order->created_at)->addDays($order->package->delivery_time ?? 3)->format('M d, Y') }}
                </div>
            </div>
            @endif

            <!-- Customer Info -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">About the Buyer</h3>
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ $order->user->avatar ? asset('storage/' . $order->user->avatar) : asset('images/default-avatar.png') }}" class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-sm">
                    <div>
                        <h4 class="font-bold text-slate-900">{{ $order->user->name }}</h4>
                        <p class="text-xs text-slate-500">{{ $order->user->country ?? 'Global Member' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-slate-50 p-3 rounded-lg text-center">
                        <span class="block text-xs text-slate-400 uppercase">Orders</span>
                        <span class="font-bold text-slate-700">12</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg text-center">
                        <span class="block text-xs text-slate-400 uppercase">Rating</span>
                        <span class="font-bold text-slate-700 flex items-center justify-center gap-1">
                            5.0 <i class="fas fa-star text-yellow-400 text-xs"></i>
                        </span>
                    </div>
                </div>
                <a href="{{ route('provider.freelancer.chat.index', ['user_id' => $order->user->id]) }}" class="w-full py-3 px-4 bg-white border-2 border-slate-200 hover:border-primary-600 hover:text-primary-600 text-slate-600 font-bold rounded-xl transition-all duration-200 flex items-center justify-center gap-2 group">
                    <i class="far fa-envelope group-hover:animate-bounce"></i> Contact Buyer
                </a>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-900 mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Item Total</span>
                        <span class="font-medium">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    @if(isset($order->extras))
                        @foreach($order->extras as $extra)
                        <div class="flex justify-between text-slate-500 text-xs">
                            <span>+ {{ $extra['title'] }}</span>
                            <span>${{ number_format($extra['price'], 2) }}</span>
                        </div>
                        @endforeach
                    @endif
                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="font-bold text-xl text-primary-600">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
