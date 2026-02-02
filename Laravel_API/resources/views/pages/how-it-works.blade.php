@extends('layouts.customer')

@section('title', 'How Findlancer Works')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-indigo-900 py-20 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-indigo-800 opacity-50 blur-3xl"></div>
            <div class="absolute top-1/2 right-0 w-64 h-64 rounded-full bg-emerald-600 opacity-20 blur-2xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6 font-display">
                How Findlancer Works
            </h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto">
                Secure, simple, and efficient. Whether you're hiring or freelancing, we make it easy to get things done.
            </p>
        </div>
    </div>

    <!-- For Clients Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-emerald-600 font-semibold tracking-wide uppercase text-sm">For Clients</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2 font-display">Get work done in 4 easy steps</h2>
            </div>

            <div class="grid md:grid-cols-4 gap-8 relative">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-gray-200 -z-10"></div>

                @foreach($clientSteps as $index => $step)
                <!-- Step {{ $index + 1 }} -->
                <div class="relative bg-white p-8 rounded-2xl shadow-soft hover:shadow-premium transition-all duration-300 text-center group">
                    <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-white shadow-sm group-hover:scale-110 transition-transform">
                        <i class="{{ $step->icon }} text-3xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $index + 1 }}. {{ $step->title }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $step->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- For Freelancers Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="order-2 md:order-1">
                    <span class="text-indigo-600 font-semibold tracking-wide uppercase text-sm">For Freelancers</span>
                    <h2 class="text-3xl font-bold text-gray-900 mt-2 mb-6 font-display">Monetize your skills</h2>
                    
                    <div class="space-y-8">
                        @foreach($freelancerSteps as $step)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                                <i class="{{ $step->icon }} text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $step->title }}</h4>
                                <p class="text-gray-600 mt-1">{{ $step->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        <a href="{{ route('provider.freelancer.register') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30 transition-all">
                            Become a Seller
                        </a>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl transform rotate-3 opacity-10"></div>
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1471&q=80" alt="Freelancers working" class="relative rounded-3xl shadow-2xl" onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=Freelancers+Working';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-900 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to get started?</h2>
            <p class="text-indigo-200 mb-8 text-lg">Join our community of thousands of talented professionals and satisfied clients.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('customer.register') }}" class="px-8 py-3 bg-white text-indigo-900 font-bold rounded-full hover:bg-indigo-50 transition-colors">
                    Join Now
                </a>
                <a href="{{ route('customer.gigs.index') }}" class="px-8 py-3 bg-transparent border border-white text-white font-bold rounded-full hover:bg-white/10 transition-colors">
                    Browse Services
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
