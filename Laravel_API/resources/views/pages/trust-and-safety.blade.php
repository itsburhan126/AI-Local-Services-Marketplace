@extends('layouts.customer')

@section('title', 'Trust & Safety')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-indigo-900 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500 rounded-full blur-3xl opacity-20 -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-500 rounded-full blur-3xl opacity-20 -ml-10 -mb-10"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
            <div class="inline-flex items-center justify-center p-3 bg-white/10 rounded-2xl mb-8 backdrop-blur-sm border border-white/10">
                <i class="fas fa-shield-alt text-4xl text-emerald-400"></i>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 font-display">
                Your Trust, Our Priority
            </h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto leading-relaxed">
                We've built a secure ecosystem where you can focus on what matters most—getting great work done.
            </p>
        </div>
    </div>

    <!-- Stats/Trust Indicators -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-100">
                <div class="p-4">
                    <div class="text-3xl font-bold text-gray-900 mb-1">100%</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Payment Protection</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-bold text-gray-900 mb-1">24/7</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Support Team</div>
                </div>
                <div class="p-4">
                    <div class="text-3xl font-bold text-gray-900 mb-1">Verified</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Professionals</div>
                </div>
                <div class="p-4 border-r-0">
                    <div class="text-3xl font-bold text-gray-900 mb-1">Secure</div>
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Data Encryption</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Features -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold tracking-wide uppercase text-sm">Safety First</span>
            <h2 class="text-3xl font-bold text-gray-900 mt-2 font-display">Built for Peace of Mind</h2>
        </div>

        @if(isset($items) && $items->count() > 0)
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($items as $item)
            <div class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-{{ $item->bg_color ?? 'indigo-50' }} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <i class="{{ $item->icon ?? 'fas fa-shield-alt' }} text-2xl text-{{ $item->text_color ?? 'indigo-600' }}"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $item->title }}</h3>
                <p class="text-gray-500 leading-relaxed">
                    {{ $item->description }}
                </p>
            </div>
            @endforeach
        </div>
        @else
        <!-- Fallback Static Content -->
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Secure Payments -->
            <div class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-lock text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Secure Payments</h3>
                <p class="text-gray-500 leading-relaxed">
                    We hold payments in escrow until the work is approved. This ensures freelancers get paid for their work and clients get what they paid for.
                </p>
            </div>
            <!-- Verified Profiles -->
            <div class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-check text-2xl text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Verified Profiles</h3>
                <p class="text-gray-500 leading-relaxed">
                    We verify identities and skills to ensure you're working with real professionals. Look for the "Verified" badge on profiles.
                </p>
            </div>
            <!-- 24/7 Support -->
            <div class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-headset text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
                <p class="text-gray-500 leading-relaxed">
                    Our support team is always here to help. Whether you have a question or an issue, we're just a message away.
                </p>
            </div>
        </div>
        @endif
    </div>

    <!-- Detailed Sections -->
    <div class="bg-gray-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Buying Safely -->
            <div class="bg-white rounded-3xl p-10 md:p-14 shadow-lg shadow-gray-200/50 border border-gray-100 flex flex-col md:flex-row gap-16 items-center">
                <div class="flex-1">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wide mb-6">For Clients</div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-display">Buying Safely</h2>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-emerald-600 text-xs"></i>
                            </div>
                            <span class="text-gray-600 text-lg"><strong>Check Reviews:</strong> Always read reviews from previous clients to gauge the freelancer's quality.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-emerald-600 text-xs"></i>
                            </div>
                            <span class="text-gray-600 text-lg"><strong>Communicate Clearly:</strong> Use our chat system to discuss project details before hiring.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-emerald-600 text-xs"></i>
                            </div>
                            <span class="text-gray-600 text-lg"><strong>Keep it on Platform:</strong> Never pay outside of Findlancer. Paying outside voids our protection.</span>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/2 flex justify-center">
                    <img src="https://illustrations.popsy.co/amber/payment-processed.svg" alt="Safe Payment" class="w-full max-w-md drop-shadow-2xl hover:scale-105 transition-transform duration-500" onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=Safe+Payment';">
                </div>
            </div>

            <!-- Selling Safely -->
            <div class="bg-white rounded-3xl p-10 md:p-14 shadow-lg shadow-gray-200/50 border border-gray-100 flex flex-col md:flex-row-reverse gap-16 items-center">
                <div class="flex-1">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wide mb-6">For Freelancers</div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-display">Selling Safely</h2>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-indigo-600 text-xs"></i>
                            </div>
                            <span class="text-gray-600 text-lg"><strong>Set Clear Terms:</strong> Define your scope of work clearly in your Gig description.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-indigo-600 text-xs"></i>
                            </div>
                            <span class="text-gray-600 text-lg"><strong>Don't Start Without an Order:</strong> Wait for an official order notification before starting work.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center mt-1">
                                <i class="fas fa-check text-indigo-600 text-xs"></i>
                            </div>
                            <span class="text-gray-600 text-lg"><strong>Report Suspicious Activity:</strong> If a user asks for personal info or outside payment, report them.</span>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/2 flex justify-center">
                    <img src="https://illustrations.popsy.co/amber/success.svg" alt="Selling Safe" class="w-full max-w-md drop-shadow-2xl hover:scale-105 transition-transform duration-500" onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=Selling+Safely';">
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
