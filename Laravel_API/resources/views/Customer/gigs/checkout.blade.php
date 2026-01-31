<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - {{ $gig->title }} | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        body: ['"Inter"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            500: '#10b981', 
                            600: '#059669', 
                            900: '#064e3b', 
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-body text-slate-600 antialiased" x-data="{ paymentMethod: 'paypal' }">
    
    <!-- Navbar (Simplified for checkout) -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-black rounded-xl flex items-center justify-center text-white font-bold text-xl">f</div>
                <span class="text-2xl font-bold text-gray-900 tracking-tight">findlancer</span>
            </a>
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500">
                <i class="fas fa-lock text-emerald-500"></i> 
                <span>Secure Checkout</span>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
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

        <form action="{{ route('customer.gigs.order.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            @csrf
            <input type="hidden" name="gig_id" value="{{ $gig->id }}">
            <input type="hidden" name="gig_package_id" value="{{ $selectedPackage->id }}">
            
            <!-- Left Column: Billing & Details -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Order Details -->
                <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
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
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-wallet text-emerald-500"></i> Payment Method
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- PayPal -->
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                               :class="paymentMethod === 'paypal' ? 'border-blue-500 bg-blue-50/30 ring-1 ring-blue-500' : 'border-gray-200 hover:border-gray-300'">
                            <input type="radio" name="payment_method" value="paypal" class="hidden" x-model="paymentMethod">
                            <div class="flex-1 flex items-center gap-4">
                                 <i class="fab fa-paypal text-2xl text-[#003087] w-8 text-center"></i>
                                 <div>
                                    <span class="block font-bold text-gray-900">PayPal</span>
                                    <span class="block text-sm text-gray-500">Pay safely with your PayPal account.</span>
                                 </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                 :class="paymentMethod === 'paypal' ? 'border-blue-500' : 'border-gray-300'">
                                 <div class="w-2.5 h-2.5 rounded-full bg-blue-500" x-show="paymentMethod === 'paypal'"></div>
                            </div>
                        </label>

                        <!-- Stripe -->
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                               :class="paymentMethod === 'stripe' ? 'border-indigo-500 bg-indigo-50/30 ring-1 ring-indigo-500' : 'border-gray-200 hover:border-gray-300'">
                            <input type="radio" name="payment_method" value="stripe" class="hidden" x-model="paymentMethod">
                            <div class="flex-1 flex items-center gap-4">
                                 <i class="fab fa-stripe text-3xl text-[#635BFF] w-8 text-center"></i>
                                 <div>
                                    <span class="block font-bold text-gray-900">Stripe</span>
                                    <span class="block text-sm text-gray-500">Pay with credit card via Stripe.</span>
                                 </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                 :class="paymentMethod === 'stripe' ? 'border-indigo-500' : 'border-gray-300'">
                                 <div class="w-2.5 h-2.5 rounded-full bg-indigo-500" x-show="paymentMethod === 'stripe'"></div>
                            </div>
                        </label>

                        <!-- Credit Card -->
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                               :class="paymentMethod === 'card' ? 'border-emerald-500 bg-emerald-50/30 ring-1 ring-emerald-500' : 'border-gray-200 hover:border-gray-300'">
                            <input type="radio" name="payment_method" value="card" class="hidden" x-model="paymentMethod">
                            <div class="flex-1 flex items-center gap-4">
                                 <i class="fas fa-credit-card text-2xl text-emerald-600 w-8 text-center"></i>
                                 <div>
                                    <span class="block font-bold text-gray-900">Credit or Debit Card</span>
                                    <span class="block text-sm text-gray-500">Secure payment with SSL encryption.</span>
                                 </div>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                 :class="paymentMethod === 'card' ? 'border-emerald-500' : 'border-gray-300'">
                                 <div class="w-2.5 h-2.5 rounded-full bg-emerald-500" x-show="paymentMethod === 'card'"></div>
                            </div>
                        </label>
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
    </main>
</body>
</html>