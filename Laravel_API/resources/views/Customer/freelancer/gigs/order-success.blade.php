@extends('layouts.customer')

@section('title', 'Order Successful')

@section('content')
<div class="min-h-screen bg-white relative overflow-hidden">
    <!-- Confetti Canvas -->
    <canvas id="confetti" class="absolute inset-0 pointer-events-none z-50"></canvas>

    <!-- Success Hero Section -->
    <div class="relative pt-16 pb-12 sm:pt-24 sm:pb-16 text-center">
        <div class="relative inline-block mb-6">
            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto animate-bounce">
                <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="absolute -top-1 -right-1">
                <span class="flex h-6 w-6 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-6 w-6 bg-emerald-500"></span>
                </span>
            </div>
        </div>

        <h1 class="text-4xl font-bold text-gray-900 tracking-tight sm:text-5xl mb-4 font-jakarta">
            Payment Successful!
        </h1>
        <p class="text-xl text-gray-500 mb-8 max-w-2xl mx-auto">
            Your order <span class="font-mono font-bold text-gray-900">#{{ $order->id }}</span> has been confirmed.
            <br class="hidden sm:block" />
            Wait for the provider to accept your request.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
            <a href="{{ route('customer.gigs.order.details', $order->id) }}" 
               class="min-w-[200px] inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                View Order Details
            </a>
            <a href="{{ route('customer.dashboard') }}" 
               class="min-w-[200px] inline-flex justify-center items-center px-6 py-3 border-2 border-gray-100 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-200 transition-all">
                Go to Dashboard
            </a>
        </div>
    </div>

    <!-- Similar Gigs Section -->
    @if($relatedGigs->count() > 0)
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Similar Services</h2>
                    <p class="text-gray-500 mt-1">Explore other gigs in this category</p>
                </div>
                
                <!-- Scroll Controls -->
                <div class="flex gap-2">
                    <button id="scrollLeft" class="p-2 rounded-full border border-gray-200 hover:bg-gray-50 text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button id="scrollRight" class="p-2 rounded-full border border-gray-200 hover:bg-gray-50 text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Horizontal Scroll Container -->
            <div class="relative group">
                <div id="gigsContainer" class="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory scrollbar-hide scroll-smooth">
                    @foreach($relatedGigs as $gig)
                        <div class="min-w-[280px] sm:min-w-[320px] snap-start">
                            @include('Customer.components.gig-card', ['gig' => $gig])
                        </div>
                    @endforeach
                </div>
                
                <!-- Gradient Fade Effects -->
                <div class="absolute inset-y-0 left-0 w-12 bg-gradient-to-r from-white to-transparent pointer-events-none"></div>
                <div class="absolute inset-y-0 right-0 w-12 bg-gradient-to-l from-white to-transparent pointer-events-none"></div>
            </div>
        </div>
    @endif
</div>

<!-- Confetti Script -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confetti Animation
        var duration = 3 * 1000;
        var animationEnd = Date.now() + duration;
        var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        var interval = setInterval(function() {
            var timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            var particleCount = 50 * (timeLeft / duration);
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
            confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
        }, 250);

        // Horizontal Scroll Logic
        const container = document.getElementById('gigsContainer');
        const leftBtn = document.getElementById('scrollLeft');
        const rightBtn = document.getElementById('scrollRight');

        if(container && leftBtn && rightBtn) {
            leftBtn.addEventListener('click', () => {
                container.scrollBy({ left: -340, behavior: 'smooth' });
            });

            rightBtn.addEventListener('click', () => {
                container.scrollBy({ left: 340, behavior: 'smooth' });
            });
        }
    });
</script>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
