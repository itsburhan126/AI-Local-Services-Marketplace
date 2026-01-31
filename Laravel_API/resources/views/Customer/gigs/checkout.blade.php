@extends('layouts.customer')

@section('title', 'Checkout - ' . $gig->title)

@section('content')
<div x-data="{ paymentMethod: '{{ $gateways->first()->name ?? '' }}', isLoading: false }">
    
    <!-- Loading Overlay -->
    <div x-show="isLoading" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-white/90 backdrop-blur-sm flex items-center justify-center">
        <div class="text-center">
            <div class="relative w-24 h-24 mx-auto mb-8">
                <div class="absolute inset-0 border-4 border-gray-100 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-emerald-500 rounded-full border-t-transparent animate-spin"></div>
                <i class="fas fa-lock absolute inset-0 flex items-center justify-center text-emerald-500 text-2xl animate-pulse"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2 font-display">Processing Secure Payment</h2>
            <p class="text-gray-500 animate-pulse">Please wait while we redirect you...</p>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-display">Checkout</h1>
            <p class="text-gray-500 mt-2">Complete your order details below.</p>
        </div>

        @if(session('error'))
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('customer.gigs.order.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-12" @submit="isLoading = true">
            @csrf
            <input type="hidden" name="gig_id" value="{{ $gig->id }}">
            <input type="hidden" name="gig_package_id" value="{{ $selectedPackage->id }}">
            
            <!-- Left Column: Billing & Details -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Order Details -->
                <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2 font-display">
                        <i class="fas fa-clipboard-list text-emerald-500"></i> Order Requirements
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Delivery Days</label>
                            <div class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 font-medium flex items-center gap-2">
                                <i class="fas fa-clock text-emerald-500"></i>
                                {{ $selectedPackage->delivery_days }} Days Delivery
                            </div>
                            <!-- Hidden date input calculated from today + delivery days -->
                            @php
                                $estimatedDate = \Carbon\Carbon::now()->addDays((int)$selectedPackage->delivery_days)->format('Y-m-d');
                            @endphp
                            <input type="hidden" name="date" value="{{ $estimatedDate }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
                            <input type="time" name="time" required value="09:00"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-black focus:border-black transition-colors outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Service Address (if applicable)</label>
                            <textarea name="address" rows="2" placeholder="Enter full address for on-site services..."
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-black focus:border-black transition-colors outline-none"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Order Notes / Requirements</label>
                            <textarea name="notes" rows="4" placeholder="Describe your requirements in detail..."
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-black focus:border-black transition-colors outline-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2 font-display">
                        <i class="fas fa-wallet text-emerald-500"></i> Payment Method
                    </h2>
                    
                    <div class="space-y-4">
                        @if($gateways->isEmpty())
                            <div class="p-4 rounded-xl border border-yellow-200 bg-yellow-50 text-yellow-800">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="font-bold">No Payment Methods Available</span>
                                </div>
                                <p class="text-sm mt-1">Please contact support or try again later.</p>
                            </div>
                        @else
                            @foreach($gateways as $gateway)
                                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                                       :class="paymentMethod === '{{ $gateway->name }}' ? '{{ $gateway->name === 'paypal' ? 'border-blue-500 bg-blue-50/30 ring-1 ring-blue-500' : ($gateway->name === 'stripe' ? 'border-indigo-500 bg-indigo-50/30 ring-1 ring-indigo-500' : 'border-emerald-500 bg-emerald-50/30 ring-1 ring-emerald-500') }}' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="payment_method" value="{{ $gateway->name }}" class="hidden" x-model="paymentMethod">
                                    <div class="flex-1 flex items-center gap-4">
                                        @if($gateway->name === 'paypal')
                                            <i class="fab fa-paypal text-2xl text-[#003087] w-8 text-center"></i>
                                        @elseif($gateway->name === 'stripe')
                                            <i class="fab fa-stripe text-3xl text-[#635BFF] w-8 text-center"></i>
                                        @else
                                            <i class="fas fa-credit-card text-2xl text-emerald-600 w-8 text-center"></i>
                                        @endif
                                        
                                        <div>
                                            <span class="block font-bold text-gray-900">{{ $gateway->title }}</span>
                                            <span class="block text-sm text-gray-500">
                                                @if($gateway->name === 'paypal')
                                                    Pay safely with your PayPal account.
                                                @elseif($gateway->name === 'stripe')
                                                    Pay with credit card via Stripe.
                                                @else
                                                    Secure payment with SSL encryption.
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                         :class="paymentMethod === '{{ $gateway->name }}' ? '{{ $gateway->name === 'paypal' ? 'border-blue-500' : ($gateway->name === 'stripe' ? 'border-indigo-500' : 'border-emerald-500') }}' : 'border-gray-300'">
                                         <div class="w-2.5 h-2.5 rounded-full" 
                                              :class="'{{ $gateway->name === 'paypal' ? 'bg-blue-500' : ($gateway->name === 'stripe' ? 'bg-indigo-500' : 'bg-emerald-500') }}'"
                                              x-show="paymentMethod === '{{ $gateway->name }}'"></div>
                                    </div>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-lg shadow-gray-200/50">
                        <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
                            <!-- Gig Image -->
                            @php
                                $checkoutImage = 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop';
                                if (!empty($gig->thumbnail_image)) {
                                    if (filter_var($gig->thumbnail_image, FILTER_VALIDATE_URL)) {
                                         $checkoutImage = $gig->thumbnail_image;
                                    } else {
                                         $checkoutImage = asset('storage/' . $gig->thumbnail_image);
                                    }
                                }
                            @endphp
                            <div class="w-20 h-20 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                <img src="{{ $checkoutImage }}" alt="{{ $gig->title }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 line-clamp-2 leading-tight">{{ $gig->title }}</h3>
                                <span class="inline-block mt-2 px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md">
                                    {{ $selectedPackage->name }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-bold text-gray-900">${{ number_format($selectedPackage->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Service Fee</span>
                                <span class="font-bold text-gray-900">$0.00</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="text-3xl font-extrabold text-gray-900 tracking-tight">${{ number_format($selectedPackage->price, 2) }}</span>
                            </div>
                        </div>

                        <ul class="space-y-3 mb-8 text-sm text-gray-600">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-clock text-emerald-500 w-5"></i> 
                                <span>{{ $selectedPackage->delivery_time }} Days Delivery</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-sync-alt text-emerald-500 w-5"></i> 
                                <span>{{ $selectedPackage->revisions }} Revisions</span>
                            </li>
                        </ul>

                        <button type="submit" class="w-full bg-black text-white font-bold py-4 rounded-xl hover:bg-gray-800 transition-all shadow-xl shadow-gray-200 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <span>Confirm & Pay</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        
                        <div class="mt-6 flex items-center justify-center gap-2 text-xs text-gray-400">
                            <i class="fas fa-shield-alt"></i>
                            <span>SSL Secure Payment</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection