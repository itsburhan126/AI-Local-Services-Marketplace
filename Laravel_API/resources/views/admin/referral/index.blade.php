@extends('layouts.admin')

@section('header')
Referral Campaign
@endsection

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6 space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Referral Campaign Settings</h1>
            <p class="mt-1 text-slate-500">Manage the 'Refer a Friend' card content and visibility.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-xl" role="alert">
        <span class="font-medium">Success!</span> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-xl" role="alert">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Content Card -->
    <div class="relative overflow-hidden bg-white shadow-xl rounded-3xl ring-1 ring-slate-900/5 max-w-3xl">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        
        <div class="p-8">
            <form action="{{ route('admin.referral.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Enable/Disable Toggle -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div>
                        <h3 class="font-semibold text-slate-900">Enable Referral Campaign</h3>
                        <p class="text-sm text-slate-500">Show this section on the mobile app home screen</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="referral_enabled" value="1" class="sr-only peer" {{ ($settings['referral_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <!-- Title -->
                <div>
                    <label for="referral_title" class="block mb-2 text-sm font-semibold text-slate-700">Title</label>
                    <input type="text" name="referral_title" id="referral_title" 
                        value="{{ $settings['referral_title'] ?? 'Refer a friend & get up to $200' }}"
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                        placeholder="e.g. Refer a friend & get up to $200">
                </div>

                <!-- Description -->
                <div>
                    <label for="referral_description" class="block mb-2 text-sm font-semibold text-slate-700">Description</label>
                    <textarea name="referral_description" id="referral_description" rows="3"
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                        placeholder="e.g. Invite your friends to try our services.">{{ $settings['referral_description'] ?? 'Invite your friends to try our services.' }}</textarea>
                </div>

                <!-- Link -->
                <div>
                    <label for="referral_link" class="block mb-2 text-sm font-semibold text-slate-700">Action Link / Route</label>
                    <input type="text" name="referral_link" id="referral_link" 
                        value="{{ $settings['referral_link'] ?? '/referral' }}"
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                        placeholder="e.g. /referral">
                </div>

                <!-- Image -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">Banner Image</label>
                    <div class="flex items-start gap-6">
                        @if(isset($settings['referral_image']))
                        <div class="relative w-40 h-24 overflow-hidden rounded-lg border border-slate-200 shadow-sm">
                            <img src="{{ $settings['referral_image'] }}" alt="Referral Banner" class="w-full h-full object-cover">
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <input type="file" name="referral_image" id="referral_image"
                                class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                transition-all"
                            >
                            <p class="mt-2 text-xs text-slate-500">Recommended size: 800x400px. JPG, PNG allowed.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-semibold text-white transition-all duration-200 bg-indigo-600 rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
