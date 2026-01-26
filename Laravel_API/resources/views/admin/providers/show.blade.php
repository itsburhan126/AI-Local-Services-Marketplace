@extends('layouts.admin')

@section('title', $provider->providerProfile->mode === 'freelancer' ? 'Freelancer Details' : 'Provider Details')

@section('content')
<div class="content-transition">
    <!-- Header -->
    <div class="flex justify-between items-start mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.providers.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 font-jakarta">{{ $provider->providerProfile->mode === 'freelancer' ? 'Freelancer Details' : 'Provider Details' }}</h1>
                <p class="text-gray-500 mt-1">View and manage {{ $provider->providerProfile->mode === 'freelancer' ? 'freelancer' : 'provider' }} information</p>
            </div>
        </div>
        
        <div class="flex gap-3">
            @if($provider->status !== 'active')
            <form action="{{ route('admin.providers.status', $provider->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="active">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Approve {{ $provider->providerProfile->mode === 'freelancer' ? 'Freelancer' : 'Provider' }}
                </button>
            </form>
            @else
            <form action="{{ route('admin.providers.status', $provider->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="inactive">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-white border border-red-200 text-red-600 font-medium hover:bg-red-50 transition-all flex items-center gap-2">
                    <i class="fas fa-ban"></i> Suspend {{ $provider->providerProfile->mode === 'freelancer' ? 'Freelancer' : 'Provider' }}
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Info -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Profile Card -->
            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-user-circle text-9xl text-indigo-500 transform rotate-12 translate-x-8 -translate-y-8"></i>
                </div>
                
                <div class="flex items-start gap-6 relative z-10">
                    <div class="w-24 h-24 rounded-2xl bg-gray-200 overflow-hidden shadow-lg border-4 border-white">
                        <img src="{{ $provider->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($provider->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             alt="{{ $provider->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($provider->name) }}&color=7F9CF5&background=EBF4FF';">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-xl font-bold text-gray-800">{{ $provider->name }}</h2>
                            @if($provider->providerProfile && $provider->providerProfile->is_verified)
                                <i class="fas fa-check-circle text-blue-500 text-lg" title="Verified Provider"></i>
                            @endif
                        </div>
                        <p class="text-gray-500 mb-4 flex items-center gap-2">
                            <i class="fas fa-envelope text-indigo-400"></i> {{ $provider->email }}
                        </p>
                        
                        <div class="flex flex-wrap gap-3">
                            <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-medium border border-indigo-100">
                                <i class="fas fa-briefcase mr-1"></i> {{ $provider->providerProfile->company_name ?? 'Individual' }}
                            </span>
                            <span class="px-3 py-1 rounded-lg bg-purple-50 text-purple-600 text-sm font-medium border border-purple-100">
                                <i class="fas fa-star mr-1"></i> {{ $provider->providerProfile->rating ?? '0.0' }} Rating
                            </span>
                             <span class="px-3 py-1 rounded-lg bg-orange-50 text-orange-600 text-sm font-medium border border-orange-100">
                                <i class="fas fa-percent mr-1"></i> {{ $provider->providerProfile->commission_rate ?? '0' }}% Commission
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services & Gigs -->
            <div class="glass-panel rounded-2xl p-6">
                @php
                    $isFreelancer = $provider->providerProfile->mode === 'freelancer';
                    $hasServices = !$isFreelancer && $provider->services && $provider->services->count() > 0;
                    $hasGigs = $isFreelancer && $provider->gigs && $provider->gigs->count() > 0;
                @endphp

                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-concierge-bell text-indigo-500"></i> {{ $isFreelancer ? 'Gigs' : 'Services' }}
                </h3>

                @if(!$hasServices && !$hasGigs)
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-2 opacity-50"></i>
                        <p>No {{ $isFreelancer ? 'gigs' : 'services' }} listed by this {{ $isFreelancer ? 'freelancer' : 'provider' }}.</p>
                    </div>
                @endif

                @if($hasServices)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach($provider->services as $index => $service)
                            <div class="service-item {{ $index >= 10 ? 'hidden' : '' }} p-4 rounded-xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                        <img src="{{ $service->image ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-800 truncate group-hover:text-indigo-600 transition-colors">{{ $service->name }}</h4>
                                        <p class="text-xs text-gray-500 truncate">{{ $service->category->name ?? 'Uncategorized' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-indigo-600 font-bold text-sm">${{ number_format($service->price, 2) }}</span>
                                            <span class="text-xs text-gray-400">• {{ $service->duration_minutes }} mins</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.services.show', $service->id) }}" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-200 transition-all">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($hasGigs)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($provider->gigs as $index => $gig)
                            <div class="service-item {{ $index >= 10 ? 'hidden' : '' }} p-4 rounded-xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                        <img src="{{ $gig->thumbnail_image ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-800 truncate group-hover:text-indigo-600 transition-colors">{{ $gig->title }}</h4>
                                        <p class="text-xs text-gray-500 truncate">{{ $gig->category->name ?? 'Uncategorized' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($gig->is_active)
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-600">Active</span>
                                            @else
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Inactive</span>
                                            @endif
                                            <span class="text-xs text-gray-400">• {{ $gig->view_count }} views</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.gigs.show', $gig->id) }}" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-200 transition-all">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Documents -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-indigo-500"></i> Documents
                </h3>
                
                @if($provider->providerProfile && $provider->providerProfile->documents)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($provider->providerProfile->documents as $doc)
                        <div class="p-4 rounded-xl border border-dashed border-gray-300 hover:border-indigo-500 hover:bg-indigo-50/30 transition-all group cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-pdf text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Document {{ $loop->iteration }}</p>
                                    <p class="text-xs text-gray-500">Click to view</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-folder-open text-4xl mb-2 opacity-50"></i>
                        <p>No documents uploaded</p>
                    </div>
                @endif
            </div>
            
             <!-- About -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-indigo-500"></i> About
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ $provider->providerProfile->about ?? 'No description provided.' }}
                </p>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
             <!-- Status Card -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Account Status</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 rounded-xl bg-gray-50">
                        <span class="text-gray-600">Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $provider->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                            {{ ucfirst($provider->status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 rounded-xl bg-gray-50">
                        <span class="text-gray-600">Verified</span>
                         @if($provider->providerProfile && $provider->providerProfile->is_verified)
                            <span class="text-blue-600 font-medium text-sm"><i class="fas fa-check-circle"></i> Yes</span>
                        @else
                            <span class="text-gray-400 font-medium text-sm">No</span>
                        @endif
                    </div>
                    
                    <div class="flex justify-between items-center p-3 rounded-xl bg-gray-50">
                        <span class="text-gray-600">Joined</span>
                        <span class="text-gray-800 font-medium text-sm">{{ $provider->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Address -->
             <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Location</h3>
                
                <div class="flex items-start gap-3 text-gray-600 mb-4">
                    <i class="fas fa-map-marker-alt text-red-500 mt-1"></i>
                    <p>{{ $provider->providerProfile->address ?? 'No address provided' }}</p>
                </div>
                
                <!-- Placeholder Map -->
                <div class="w-full h-40 rounded-xl bg-gray-200 overflow-hidden relative">
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-100 text-gray-400">
                        <i class="fas fa-map text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
