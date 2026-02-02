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
            
            <!-- 1. Profile Header Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm relative group">
                <!-- Top Right Actions -->
                <div class="absolute top-6 right-6 flex gap-3">
                    <button @click="navigator.clipboard.writeText('{{ route('page.show', $user->username ?? 'profile') }}'); $el.innerHTML = '<i class=\'fas fa-check mr-2\'></i> Copied!'; setTimeout(() => $el.innerHTML = '<i class=\'fas fa-share-alt mr-2\'></i> Share', 2000)" class="px-4 py-1.5 border border-slate-300 rounded text-slate-700 hover:bg-slate-50 transition-colors text-sm font-semibold flex items-center min-w-[100px] justify-center">
                        <i class="fas fa-share-alt mr-2"></i> Share
                    </button>
                    <a href="{{ route('page.show', $user->username ?? 'profile') }}" target="_blank" class="px-4 py-1.5 border border-slate-300 rounded text-slate-700 hover:bg-slate-50 transition-colors text-sm font-semibold flex items-center">
                        <i class="fas fa-eye mr-2"></i> Preview
                    </a>
                </div>

                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <!-- Avatar -->
                    <div class="relative shrink-0">
                        <div class="w-32 h-32 rounded-full bg-slate-100 border-4 border-white shadow-md overflow-hidden">
                             <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute bottom-2 right-2 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 pt-2 w-full pr-0 md:pr-32">
                        
                        <!-- Name & Username -->
                        <div class="flex items-center gap-3 mb-1" x-data="{ editing: false, name: '{{ $user->name }}' }">
                            <template x-if="!editing">
                                <div class="flex items-center gap-3 group/name">
                                    <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
                                    <button @click="editing = true" class="text-slate-400 hover:text-slate-600 opacity-0 group-hover/name:opacity-100 transition-opacity">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                            </template>
                            <template x-if="editing">
                                <form action="{{ route('provider.freelancer.profile.update') }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" x-model="name" class="px-2 py-1 border rounded text-lg font-bold w-full max-w-xs">
                                    <button type="submit" class="text-green-600 hover:text-green-700"><i class="fas fa-check"></i></button>
                                    <button type="button" @click="editing = false" class="text-red-500 hover:text-red-600"><i class="fas fa-times"></i></button>
                                </form>
                            </template>
                        </div>
                        
                        <div class="text-slate-500 text-sm mb-4">@if($user->username) {{ '@'.$user->username }} @else No username @endif</div>

                        <!-- Professional Headline -->
                        <div class="mb-4" x-data="{ editing: false, headline: '{{ $headline }}' }">
                            <template x-if="!editing">
                                <div class="flex items-center gap-2 group/headline">
                                    <p class="text-slate-600 border border-transparent pl-0 py-1 rounded hover:bg-slate-50 hover:border-slate-200 transition-all cursor-pointer truncate" @click="editing = true">
                                        {{ $headline }}
                                        <i class="fas fa-pencil-alt ml-2 text-slate-400 opacity-0 group-hover/headline:opacity-100 text-xs"></i>
                                    </p>
                                </div>
                            </template>
                            <template x-if="editing">
                                <form action="{{ route('provider.freelancer.profile.update') }}" method="POST" class="flex items-center gap-2 w-full">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="professional_headline" x-model="headline" class="px-2 py-1 border rounded text-slate-600 w-full">
                                    <button type="submit" class="text-green-600 hover:text-green-700"><i class="fas fa-check"></i></button>
                                    <button type="button" @click="editing = false" class="text-red-500 hover:text-red-600"><i class="fas fa-times"></i></button>
                                </form>
                            </template>
                        </div>

                        <!-- Meta Info (Location & Languages) -->
                        <div class="flex flex-col gap-2 text-slate-500 text-sm">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt w-4 text-center"></i>
                                <span>{{ $location }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2" x-data="{ editing: false, langs: '{{ $languages }}' }">
                                <i class="fas fa-language w-4 text-center"></i>
                                <template x-if="!editing">
                                    <div class="flex items-center gap-2 group/lang cursor-pointer" @click="editing = true">
                                        <span>Speaks {{ $languages }}</span>
                                        <i class="fas fa-pencil-alt text-slate-400 opacity-0 group-hover/lang:opacity-100 text-xs"></i>
                                    </div>
                                </template>
                                <template x-if="editing">
                                    <form action="{{ route('provider.freelancer.profile.update') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="languages" x-model="langs" class="px-2 py-0.5 border rounded text-xs w-64" placeholder="English, Spanish...">
                                        <button type="submit" class="text-green-600 hover:text-green-700"><i class="fas fa-check"></i></button>
                                        <button type="button" @click="editing = false" class="text-red-500 hover:text-red-600"><i class="fas fa-times"></i></button>
                                    </form>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. About Section -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm" x-data="{ editing: false, description: `{{ $about }}` }">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-slate-900">About</h2>
                </div>

                <template x-if="!editing">
                    <div>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $about }}</p>
                        <button @click="editing = true" class="mt-6 px-6 py-2 border border-slate-300 rounded-full text-slate-600 hover:bg-slate-50 font-semibold transition-colors text-sm">
                            Edit details
                        </button>
                    </div>
                </template>

                <template x-if="editing">
                    <form action="{{ route('provider.freelancer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <textarea name="description" x-model="description" rows="8" class="w-full border border-slate-300 rounded-lg p-4 text-slate-600 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"></textarea>
                        <div class="flex gap-3 mt-4">
                            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-full font-bold hover:bg-slate-800 transition-colors text-sm">Save Changes</button>
                            <button type="button" @click="editing = false" class="px-6 py-2 border border-slate-300 rounded-full text-slate-600 hover:bg-slate-50 font-semibold transition-colors text-sm">Cancel</button>
                        </div>
                    </form>
                </template>
            </div>

            <!-- KYC Verification Section -->
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm" id="kyc-section">
                <div class="flex justify-between items-center mb-6">
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
                            <i class="fas fa-clock"></i> Pending Approval
                        </span>
                    @else
                        <span class="px-4 py-2 bg-slate-100 text-slate-700 rounded-full text-sm font-bold flex items-center gap-2">
                            <i class="fas fa-shield-alt"></i> Not Verified
                        </span>
                    @endif
                </div>

                @if(!$user->kyc_status || $user->kyc_status === 'rejected')
                    @if($user->kyc_status === 'rejected')
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-start gap-3">
                            <i class="fas fa-exclamation-circle mt-1"></i>
                            <div>
                                <p class="font-bold">Verification Rejected</p>
                                <p class="text-sm">Your previous attempt was rejected. Please ensure your documents are clear and valid.</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('provider.freelancer.kyc.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Document Type</label>
                                <select name="document_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                    <option value="passport">Passport</option>
                                    <option value="national_id">National ID Card</option>
                                    <option value="driving_license">Driving License</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all" placeholder="+1 234 567 8900">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors cursor-pointer relative group/upload">
                                <input type="file" name="document_front" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" accept="image/*" required onchange="this.nextElementSibling.classList.add('hidden'); this.nextElementSibling.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.nextElementSibling.querySelector('img').src = window.URL.createObjectURL(this.files[0])">
                                <div class="pointer-events-none group-hover/upload:scale-105 transition-transform">
                                    <i class="fas fa-id-card text-4xl text-slate-300 mb-3"></i>
                                    <p class="font-bold text-slate-700">Front Side</p>
                                    <p class="text-xs text-slate-400 mt-1">Upload clear image</p>
                                </div>
                                <div class="hidden pointer-events-none absolute inset-0 p-2">
                                    <img src="" class="w-full h-full object-contain rounded-lg">
                                </div>
                            </div>

                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors cursor-pointer relative group/upload">
                                <input type="file" name="document_back" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" accept="image/*" onchange="this.nextElementSibling.classList.add('hidden'); this.nextElementSibling.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.nextElementSibling.querySelector('img').src = window.URL.createObjectURL(this.files[0])">
                                <div class="pointer-events-none group-hover/upload:scale-105 transition-transform">
                                    <i class="fas fa-id-card text-4xl text-slate-300 mb-3"></i>
                                    <p class="font-bold text-slate-700">Back Side</p>
                                    <p class="text-xs text-slate-400 mt-1">Upload clear image (Optional)</p>
                                </div>
                                <div class="hidden pointer-events-none absolute inset-0 p-2">
                                    <img src="" class="w-full h-full object-contain rounded-lg">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 transform hover:-translate-y-0.5 flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Submit for Verification
                            </button>
                        </div>
                    </form>
                @endif
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
