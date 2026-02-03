@extends('layouts.freelancer')

@section('title', 'Profile')

@section('content')
@php
    $user = Auth::user();
    $profile = $user->providerProfile;
    $headline = $profile->company_name ?? 'Add a Professional Headline';
    $about = $profile->about ?? 'Tell us about yourself...';
    $languages = $profile->languages ? implode(', ', $profile->languages) : 'English';
    $location = $profile->address ?? 'Bangladesh'; // Default or pull from user
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Main Profile Info -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- 1. Profile Header Card (Professional Design) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <!-- Cover Image Area -->
                <div class="h-48 w-full bg-slate-100 relative group">
                    @if($profile->cover_image)
                        <img src="{{ asset('storage/'.$profile->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                    @endif
                    
                    <!-- Top Right Actions (Overlay) -->
                    <div class="absolute top-4 right-4 flex gap-2">
                        <a href="{{ route('page.show', $user->username ?? 'profile') }}" target="_blank" class="px-4 py-2 bg-white/90 backdrop-blur hover:bg-white text-slate-700 rounded-lg text-sm font-semibold shadow-sm transition-all flex items-center gap-2">
                            <i class="fas fa-eye"></i> <span class="hidden sm:inline">Preview</span>
                        </a>
                        <button @click="navigator.clipboard.writeText('{{ route('page.show', $user->username ?? 'profile') }}'); $el.innerHTML = '<i class=\'fas fa-check\'></i>'; setTimeout(() => $el.innerHTML = '<i class=\'fas fa-share-alt\'></i>', 2000)" class="px-4 py-2 bg-white/90 backdrop-blur hover:bg-white text-slate-700 rounded-lg text-sm font-semibold shadow-sm transition-all">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Bar -->
                <div class="px-8 pb-8 relative">
                    <div class="flex flex-col lg:flex-row items-start gap-6">
                        <!-- Avatar (Overlapping) -->
                        <div class="-mt-16 relative shrink-0">
                            <div class="w-32 h-32 rounded-full ring-4 ring-white bg-white overflow-hidden shadow-lg">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-400 border-4 border-white rounded-full" title="Online"></div>
                        </div>

                        <!-- Info Section -->
                        <div class="flex-1 pt-4 w-full">
                            <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
                                        @if($user->kyc_status === 'verified')
                                            <i class="fas fa-check-circle text-emerald-500 text-lg" title="Verified Identity"></i>
                                        @endif
                                    </div>
                                    <p class="text-slate-500 text-sm mb-3">@if($user->username) {{ '@'.$user->username }} @endif</p>
                                    
                                    <p class="text-lg text-slate-800 font-medium mb-4">{{ $headline }}</p>

                                    <!-- Badges Row -->
                                    <div class="flex flex-wrap gap-3 mb-4">
                                        <!-- Seller Level -->
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100 text-xs font-semibold">
                                            <i class="fas fa-medal"></i> {{ $profile->seller_level ?? 'Level 1' }}
                                        </div>
                                        
                                        <!-- Location -->
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 border border-slate-100 text-xs font-semibold">
                                            <i class="fas fa-map-marker-alt"></i> {{ $location }}
                                        </div>

                                        <!-- Language -->
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 border border-slate-100 text-xs font-semibold">
                                            <i class="fas fa-language"></i> {{ $languages }}
                                        </div>

                                        <!-- Joined Date -->
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 border border-slate-100 text-xs font-semibold">
                                            <i class="far fa-calendar"></i> Joined {{ optional($user->created_at)->format('M Y') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Actions -->
                                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                    <a href="{{ route('provider.freelancer.profile.edit') }}" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </a>
                                    <a href="{{ route('provider.freelancer.verification.index') }}" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-shield-alt"></i> Verification
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. About Section -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">About</h2>
                </div>
                <div>
                    <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $about }}</p>
                </div>
            </div>

            <!-- 2.0 Professional Details -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Professional Details</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-bold text-slate-700 mb-1">Company Name</div>
                        <div class="text-slate-700">{{ $profile->company_name ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-700 mb-1">Logo</div>
                        @if($profile->logo)
                            <a href="{{ asset('storage/'.$profile->logo) }}" target="_blank" class="text-indigo-600 hover:underline">View</a>
                        @else
                            <span class="text-slate-700">—</span>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm font-bold text-slate-700 mb-1">Cover Image</div>
                        @if($profile->cover_image)
                            <a href="{{ asset('storage/'.$profile->cover_image) }}" target="_blank" class="text-indigo-600 hover:underline">View</a>
                        @else
                            <span class="text-slate-700">—</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2.1 Languages & Skills -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Languages & Skills</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-bold text-slate-700 mb-2">Languages</div>
                        <div class="flex flex-wrap gap-2">
                            @forelse(($profile->languages ?? []) as $l)
                                <span class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-medium">{{ $l }}</span>
                            @empty
                                <span class="text-slate-500">—</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-700 mb-2">Skills</div>
                        <div class="flex flex-wrap gap-2">
                            @forelse(($profile->skills ?? []) as $s)
                                <span class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-primary-50 text-primary-700 text-sm font-medium">{{ $s }}</span>
                            @empty
                                <span class="text-slate-500">—</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2.2 Personal Details -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Personal Details</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm font-bold text-slate-700 mb-2">Phone</div>
                        <div class="text-slate-700">{{ $user->phone ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-700 mb-2">Country</div>
                        <div class="text-slate-700">{{ $profile->country ?: '—' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm font-bold text-slate-700 mb-2">Address</div>
                        <div class="text-slate-700">{{ $profile->address ?: '—' }}</div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <a href="{{ route('provider.freelancer.profile.edit') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-100">Edit Profile</a>
                </div>
            </div>

            <!-- Identity Verification CTA -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Identity Verification</h2>
                        <p class="text-slate-500 text-sm mt-1">Verify your identity to build trust and unlock more features.</p>
                    </div>
                    @if($user->kyc_status === 'verified')
                        <span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    @elseif($user->kyc_status === 'pending')
                        <span class="px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @else
                        <span class="px-4 py-2 bg-slate-100 text-slate-700 rounded-full text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-shield-alt"></i> Not Verified
                        </span>
                    @endif
                </div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-slate-600 text-sm">Manage your verification in a dedicated page.</div>
                    <a href="{{ route('provider.freelancer.verification.index') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-100 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i> Go to Verification
                    </a>
                </div>
            </div>

            <!-- 3. Portfolio Section -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Portfolio of past projects</h2>
                    <p class="text-slate-500 text-sm mb-6">Attract and impress potential clients by displaying your best work.</p>
                    <button class="px-6 py-2 border border-primary-600 text-primary-600 rounded-lg font-bold hover:bg-primary-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i> Start portfolio
                    </button>
                </div>
                <div class="hidden md:block">
                    <div class="w-32 h-24 bg-slate-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-images text-4xl text-slate-300"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Quick Links -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-900 mb-4">Quick Links</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('provider.freelancer.gigs.index') }}" class="flex items-center justify-between text-slate-600 hover:text-primary-600 group transition-colors p-2 rounded hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-box-open text-slate-400 group-hover:text-primary-600 transition-colors"></i>
                                Gigs
                            </span>
                            <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-primary-600 transition-colors"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('provider.freelancer.orders.index') }}" class="flex items-center justify-between text-slate-600 hover:text-primary-600 group transition-colors p-2 rounded hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-shopping-bag text-slate-400 group-hover:text-primary-600 transition-colors"></i>
                                Orders
                            </span>
                            <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-primary-600 transition-colors"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('provider.freelancer.earnings') }}" class="flex items-center justify-between text-slate-600 hover:text-primary-600 group transition-colors p-2 rounded hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-wallet text-slate-400 group-hover:text-primary-600 transition-colors"></i>
                                Earnings
                            </span>
                            <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-primary-600 transition-colors"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('provider.freelancer.verification.index') }}" class="flex items-center justify-between text-slate-600 hover:text-primary-600 group transition-colors p-2 rounded hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-shield-alt text-slate-400 group-hover:text-primary-600 transition-colors"></i>
                                Verification
                            </span>
                            <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-primary-600 transition-colors"></i>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Availability/Status (Optional extra) -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                 <h3 class="font-bold text-slate-900 mb-4">Availability</h3>
                 <div class="flex items-center gap-3">
                     <div class="w-20 h-10 bg-green-100 rounded-full flex items-center p-1 cursor-pointer">
                         <div class="w-8 h-8 bg-white rounded-full shadow-sm ml-auto"></div>
                     </div>
                     <span class="text-sm font-semibold text-green-600">Online</span>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
