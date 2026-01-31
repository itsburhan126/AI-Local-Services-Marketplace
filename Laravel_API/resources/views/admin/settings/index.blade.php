@extends('layouts.admin')

@section('title', 'Company Settings')

@section('content')
<div class="content-transition">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 font-jakarta">Company Settings</h1>
        <p class="text-gray-500 mt-2">Manage your company details and global configurations.</p>
    </div>

    <div class="max-w-4xl">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <!-- Site Configuration Section -->
            <div class="glass-panel p-8 rounded-2xl mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl mr-4">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Site Configuration</h2>
                        <p class="text-gray-500 text-sm">General settings for your website identity.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- App Name -->
                    <div class="space-y-2">
                        <label for="app_name" class="text-sm font-semibold text-gray-700">Site Name</label>
                        <input type="text" name="app_name" id="app_name" 
                            value="{{ $settings['app_name'] ?? config('app.name') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. Findlancer">
                    </div>

                    <!-- Copyright Text -->
                    <div class="space-y-2">
                        <label for="copyright_text" class="text-sm font-semibold text-gray-700">Copyright Text</label>
                        <input type="text" name="copyright_text" id="copyright_text" 
                            value="{{ $settings['copyright_text'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. © 2024 Findlancer International Ltd.">
                    </div>

                    <!-- Site Description -->
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label for="site_description" class="text-sm font-semibold text-gray-700">Site Description</label>
                        <textarea name="site_description" id="site_description" rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="Brief description of your site for SEO and footer...">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Social Media Section -->
            <div class="glass-panel p-8 rounded-2xl mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xl mr-4">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Social Media Links</h2>
                        <p class="text-gray-500 text-sm">Connect your social media profiles.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Facebook -->
                    <div class="space-y-2">
                        <label for="facebook_url" class="text-sm font-semibold text-gray-700">Facebook URL</label>
                        <input type="url" name="facebook_url" id="facebook_url" 
                            value="{{ $settings['facebook_url'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="https://facebook.com/yourpage">
                    </div>

                    <!-- Twitter -->
                    <div class="space-y-2">
                        <label for="twitter_url" class="text-sm font-semibold text-gray-700">Twitter URL</label>
                        <input type="url" name="twitter_url" id="twitter_url" 
                            value="{{ $settings['twitter_url'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="https://twitter.com/yourhandle">
                    </div>

                    <!-- Instagram -->
                    <div class="space-y-2">
                        <label for="instagram_url" class="text-sm font-semibold text-gray-700">Instagram URL</label>
                        <input type="url" name="instagram_url" id="instagram_url" 
                            value="{{ $settings['instagram_url'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="https://instagram.com/yourprofile">
                    </div>

                    <!-- LinkedIn -->
                    <div class="space-y-2">
                        <label for="linkedin_url" class="text-sm font-semibold text-gray-700">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" id="linkedin_url" 
                            value="{{ $settings['linkedin_url'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="https://linkedin.com/company/yourpage">
                    </div>

                    <!-- YouTube -->
                    <div class="space-y-2">
                        <label for="youtube_url" class="text-sm font-semibold text-gray-700">YouTube URL</label>
                        <input type="url" name="youtube_url" id="youtube_url" 
                            value="{{ $settings['youtube_url'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="https://youtube.com/c/yourchannel">
                    </div>
                </div>
            </div>

            <!-- Company Details Section -->
            <div class="glass-panel p-8 rounded-2xl mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mr-4">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Company Information</h2>
                        <p class="text-gray-500 text-sm">These details will appear on invoices and emails.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <div class="space-y-2">
                        <label for="company_name" class="text-sm font-semibold text-gray-700">Company Name</label>
                        <input type="text" name="company_name" id="company_name" 
                            value="{{ $settings['company_name'] ?? 'AI Local Services' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. Acme Corp">
                    </div>

                    <!-- Company Email -->
                    <div class="space-y-2">
                        <label for="company_email" class="text-sm font-semibold text-gray-700">Company Email</label>
                        <input type="email" name="company_email" id="company_email" 
                            value="{{ $settings['company_email'] ?? 'support@example.com' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. support@acme.com">
                    </div>

                    <!-- Company Phone -->
                    <div class="space-y-2">
                        <label for="company_phone" class="text-sm font-semibold text-gray-700">Company Phone</label>
                        <input type="text" name="company_phone" id="company_phone" 
                            value="{{ $settings['company_phone'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. +1 234 567 890">
                    </div>

                    <!-- Company Address -->
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label for="company_address" class="text-sm font-semibold text-gray-700">Company Address</label>
                        <textarea name="company_address" id="company_address" rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. 123 Business St, Tech City, USA">{{ $settings['company_address'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Currency Settings Section -->
            <div class="glass-panel p-8 rounded-2xl mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-xl mr-4">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Currency Settings</h2>
                        <p class="text-gray-500 text-sm">Configure your platform's currency format.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Currency Symbol -->
                    <div class="space-y-2">
                        <label for="currency_symbol" class="text-sm font-semibold text-gray-700">Currency Symbol</label>
                        <input type="text" name="currency_symbol" id="currency_symbol" 
                            value="{{ $settings['currency_symbol'] ?? '$' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. $">
                    </div>

                    <!-- Currency Code -->
                    <div class="space-y-2">
                        <label for="currency_code" class="text-sm font-semibold text-gray-700">Currency Code</label>
                        <input type="text" name="currency_code" id="currency_code" 
                            value="{{ $settings['currency_code'] ?? 'USD' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. USD">
                    </div>

                    <!-- Service Fee -->
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label for="service_fee" class="text-sm font-semibold text-gray-700">Service Fee (%)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-500 font-bold">%</span>
                            <input type="number" step="0.01" min="0" max="100" name="service_fee" id="service_fee" 
                                value="{{ $settings['service_fee'] ?? '0.00' }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                                placeholder="5.00">
                        </div>
                        <p class="text-xs text-gray-500">This percentage will be added to the total amount paid by the buyer.</p>
                    </div>
                </div>
            </div>

            <!-- Pusher Settings Section -->
            <div class="glass-panel p-8 rounded-2xl mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center text-xl mr-4">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Real-time Chat Settings (Pusher)</h2>
                        <p class="text-gray-500 text-sm">Configure Pusher for real-time messaging capabilities.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- App ID -->
                    <div class="space-y-2">
                        <label for="pusher_app_id" class="text-sm font-semibold text-gray-700">App ID</label>
                        <input type="text" name="pusher_app_id" id="pusher_app_id" 
                            value="{{ $settings['pusher_app_id'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. 123456">
                    </div>

                    <!-- App Key -->
                    <div class="space-y-2">
                        <label for="pusher_app_key" class="text-sm font-semibold text-gray-700">App Key</label>
                        <input type="text" name="pusher_app_key" id="pusher_app_key" 
                            value="{{ $settings['pusher_app_key'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. 29384723984723">
                    </div>

                    <!-- App Secret -->
                    <div class="space-y-2">
                        <label for="pusher_app_secret" class="text-sm font-semibold text-gray-700">App Secret</label>
                        <input type="password" name="pusher_app_secret" id="pusher_app_secret" 
                            value="{{ $settings['pusher_app_secret'] ?? '' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="Enter Pusher Secret">
                    </div>

                    <!-- App Cluster -->
                    <div class="space-y-2">
                        <label for="pusher_app_cluster" class="text-sm font-semibold text-gray-700">App Cluster</label>
                        <input type="text" name="pusher_app_cluster" id="pusher_app_cluster" 
                            value="{{ $settings['pusher_app_cluster'] ?? 'mt1' }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="e.g. mt1">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
