@extends('layouts.freelancer')

@section('title', 'Settings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Navigation -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden sticky top-24">
                <div class="p-4 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Settings</h2>
                </div>
                <nav class="p-2 space-y-1">
                    <a href="#account" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-indigo-50 text-indigo-600 font-medium">
                        <i class="fas fa-user-circle w-5 text-center"></i> Account
                    </a>
                    <a href="#security" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium transition-colors">
                        <i class="fas fa-lock w-5 text-center"></i> Security
                    </a>
                    <a href="#notifications" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium transition-colors">
                        <i class="fas fa-bell w-5 text-center"></i> Notifications
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 space-y-8">
            
            <!-- Account Settings -->
            <div id="account" class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Account Information</h2>
                    <p class="text-slate-500 text-sm mt-1">Update your personal details and contact info.</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('provider.freelancer.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                                <input type="text" name="username" value="{{ Auth::user()->username }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                                <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full px-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="{{ Auth::user()->phone }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-100">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div id="security" class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Security</h2>
                    <p class="text-slate-500 text-sm mt-1">Manage your password and security preferences.</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('provider.freelancer.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="password">
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" class="w-full max-w-md px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">New Password</label>
                                <input type="password" name="new_password" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-white border border-slate-300 text-slate-700 px-6 py-2 rounded-lg font-bold hover:bg-slate-50 transition-colors">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notifications (Placeholder) -->
            <div id="notifications" class="bg-white rounded-xl border border-slate-200 shadow-sm opacity-50 cursor-not-allowed">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Notifications</h2>
                    <p class="text-slate-500 text-sm mt-1">Control when and how you want to be notified.</p>
                </div>
                <div class="p-6 text-center py-12">
                    <i class="fas fa-bell-slash text-4xl text-slate-300 mb-3"></i>
                    <h3 class="font-bold text-slate-600">Coming Soon</h3>
                    <p class="text-slate-400 text-sm">Notification settings will be available in the next update.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
