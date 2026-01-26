@extends('layouts.admin')

@section('title', 'Service Details')

@section('content')
<div class="content-transition">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-lg hover:border-transparent transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 font-jakarta">{{ $service->name }}</h1>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-medium text-xs uppercase">{{ $service->category->name }}</span>
                    <span>•</span>
                    @if($service->reviews->count() > 0)
                    <div class="flex items-center gap-1 text-yellow-500">
                        <i class="fas fa-star text-xs"></i>
                        <span class="font-bold text-gray-700">{{ number_format($service->reviews->avg('rating'), 1) }}</span>
                        <span class="text-gray-400 text-xs">({{ $service->reviews->count() }} reviews)</span>
                    </div>
                    <span>•</span>
                    @endif
                    <span>ID: #{{ $service->id }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.services.edit', $service->id) }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all">
            <i class="fas fa-edit mr-2"></i> Edit Service
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Image & Gallery -->
            <div class="glass-panel rounded-2xl p-6">
                <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden mb-4 bg-gray-100">
                    <img src="{{ $service->image ?? 'https://via.placeholder.com/800x450' }}" class="w-full h-full object-cover">
                </div>
                @if($service->gallery && count($service->gallery) > 0)
                <div class="grid grid-cols-4 gap-4">
                    @foreach($service->gallery as $image)
                    <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ $image }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Description -->
            <div class="glass-panel rounded-2xl p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Description</h3>
                <div class="prose prose-indigo max-w-none text-gray-600">
                    {!! nl2br(e($service->description)) !!}
                </div>
            </div>

            <!-- Reviews & Ratings -->
            <div class="glass-panel rounded-2xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-500"></i> Reviews & Ratings
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-bold">{{ $service->reviews->count() }}</span>
                    </h3>
                </div>

                @if($service->reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($service->reviews as $review)
                        <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                            <div class="flex items-start gap-4">
                                <img src="{{ $review->customer->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($review->customer->name ?? 'User') }}" 
                                     class="w-10 h-10 rounded-full bg-gray-100 object-cover">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-bold text-gray-800">{{ $review->customer->name ?? 'Unknown User' }}</h4>
                                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-yellow-400 text-xs mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                        <span class="text-gray-400 ml-1 font-medium">{{ number_format($review->rating, 1) }}</span>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ $review->review }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="far fa-star text-gray-300 text-2xl"></i>
                        </div>
                        <h3 class="text-gray-800 font-bold mb-1">No Reviews Yet</h3>
                        <p class="text-gray-500 text-sm">This service hasn't received any reviews yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Price Card -->
            <div class="glass-panel rounded-2xl p-6">
                <p class="text-sm font-bold text-gray-400 uppercase mb-2">Price Details</p>
                <div class="flex items-end gap-2 mb-4">
                    <span class="text-4xl font-bold text-gray-800">${{ number_format($service->price, 2) }}</span>
                    @if($service->discount_price)
                    <span class="text-lg text-gray-400 line-through mb-1">${{ number_format($service->discount_price, 2) }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                    <i class="far fa-clock"></i>
                    <span>Duration: <strong>{{ $service->duration_minutes }} mins</strong></span>
                </div>
            </div>

            <!-- Provider Card -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Service Provider</h3>
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $service->provider->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($service->provider->name) }}" class="w-12 h-12 rounded-full bg-gray-100">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $service->provider->name }}</h4>
                        <div class="flex items-center gap-1 text-xs text-yellow-500">
                            <i class="fas fa-star"></i>
                            <span class="font-bold text-gray-700">{{ $service->provider->providerProfile->rating ?? '0.0' }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.providers.show', $service->provider->id) }}" class="block w-full py-2 rounded-lg border border-gray-200 text-gray-600 font-medium text-center hover:bg-gray-50 transition-colors">
                    View Provider Profile
                </a>
            </div>

            <!-- Status -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Status</h3>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Active Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Featured</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $service->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $service->is_featured ? 'Featured' : 'Standard' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
