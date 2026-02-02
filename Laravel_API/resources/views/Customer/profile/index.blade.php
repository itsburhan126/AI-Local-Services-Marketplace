@extends('layouts.customer')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-gray-50/50 pb-12">
    <!-- Header Background -->
    <div class="h-64 bg-gradient-to-r from-gray-900 to-gray-800 w-full relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="absolute inset-0 bg-black/10"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Sidebar -->
            <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-6 text-center relative">
                        <div class="relative inline-block group">
                            <img src="{{ $user->profile_photo_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover mx-auto transform transition-transform group-hover:scale-105">
                            <label for="avatar-upload" class="absolute bottom-2 right-2 bg-black text-white p-2 rounded-full cursor-pointer hover:bg-gray-800 transition-colors shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                        </div>
                        
                        <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        
                        <div class="mt-4 flex items-center justify-center gap-2">
                            @if($user->kyc_status === 'verified')
                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    Identity Verified
                                </span>
                            @elseif($user->kyc_status === 'pending')
                                <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Verification Pending
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Unverified</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 px-6 py-4">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Member Since</span>
                                <span class="font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Country</span>
                                <span class="font-medium text-gray-900">USA</span> <!-- Placeholder -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Menu -->
                <nav class="mt-6 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <a href="{{ route('customer.settings') }}" class="flex items-center gap-3 px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Account Settings
                    </a>
                    <a href="#" class="flex items-center gap-3 px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Payment Methods
                    </a>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 space-y-6">
                
                <!-- Personal Information Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                        <button form="profile-form" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Save Changes</button>
                    </div>

                    <form id="profile-form" action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        @method('PUT')
                        
                        <input type="file" id="avatar-upload" name="avatar" class="hidden" onchange="document.getElementById('profile-form').submit()">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-0 transition-all bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-black focus:ring-0 transition-all bg-gray-50 focus:bg-white">
                        </div>
                    </form>
                </div>

                <!-- Identity Verification Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-50 transition-opacity group-hover:opacity-75"></div>
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Identity Verification</h3>
                                @if($user->kyc_status === 'verified')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        <i class="fas fa-check-circle mr-1"></i> Verified
                                    </span>
                                @elseif($user->kyc_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                        Unverified
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 max-w-lg">Complete your KYC verification to unlock all features, get a verified badge, and increase trust with sellers on the marketplace.</p>
                        </div>

                        <a href="{{ route('customer.verification.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all transform hover:-translate-y-0.5 shadow-lg shadow-indigo-500/30 whitespace-nowrap">
                            {{ $user->kyc_status === 'verified' ? 'View Verification Status' : ($user->kyc_status === 'pending' ? 'Check Verification Status' : 'Start Verification') }}
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>

                    @if(!$user->kyc_status || $user->kyc_status === 'rejected')
                    <div class="mt-8 pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Secure Account</p>
                                <p class="text-xs text-gray-500">Enhanced protection</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Verified Badge</p>
                                <p class="text-xs text-gray-500">Stand out to sellers</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Higher Limits</p>
                                <p class="text-xs text-gray-500">Fewer restrictions</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
