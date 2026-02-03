@extends('layouts.customer')

@section('title', 'Account Settings')

@section('content')
<div class="min-h-screen bg-gray-50/50 pb-12">
    <!-- Header Background -->
    <div class="h-64 bg-gradient-to-r from-gray-900 to-gray-800 w-full relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute bottom-0 left-0 w-full h-px bg-white/10"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Sidebar -->
            <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                <!-- User Card Mini -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 flex items-center gap-4 mb-6 transition-transform hover:-translate-y-1 duration-300">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md">
                    <div class="overflow-hidden">
                        <h3 class="font-bold text-gray-900 truncate">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-24">
                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-6 py-4 text-gray-600 hover:bg-gray-50 hover:text-black transition-all border-b border-gray-100 group">
                        <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 flex items-center justify-center transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="font-medium">My Profile</span>
                    </a>
                    
                    <a href="{{ route('customer.settings') }}" class="flex items-center gap-3 px-6 py-4 text-black bg-blue-50/50 border-l-4 border-blue-600 border-b border-gray-100 transition-all">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-cog text-sm"></i>
                        </div>
                        <span class="font-medium">Account Settings</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-6 py-4 text-gray-600 hover:bg-gray-50 hover:text-black transition-all border-b border-gray-100 group">
                         <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-purple-50 group-hover:text-purple-600 flex items-center justify-center transition-colors">
                            <i class="fas fa-bell text-sm"></i>
                        </div>
                        <span class="font-medium">Notifications</span>
                    </a>

                    <form method="POST" action="{{ route('customer.logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-6 py-4 text-red-600 hover:bg-red-50 transition-all text-left group">
                            <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 group-hover:bg-red-100 flex items-center justify-center transition-colors">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                            </div>
                            <span class="font-medium">Sign Out</span>
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 space-y-8">
                
                <!-- Security Settings -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Security Settings</h3>
                        </div>
                        <p class="text-gray-500 text-sm ml-12">Manage your password and account security preferences.</p>
                    </div>
                    
                    <div class="p-8">
                        <form action="{{ route('customer.settings.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_id" value="security">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="current_password" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-gray-50 focus:bg-white" placeholder="Enter your current password">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                            <i class="fas fa-key"></i>
                                        </span>
                                        <input type="password" name="new_password" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-gray-50 focus:bg-white" placeholder="New password">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <input type="password" name="new_password_confirmation" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-gray-50 focus:bg-white" placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Password must be at least 8 characters</span>
                                </div>
                                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2">
                                    <span>Update Password</span>
                                    <i class="fas fa-arrow-right text-sm"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notification Preferences -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                                <i class="fas fa-bell text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Notification Preferences</h3>
                        </div>
                        <p class="text-gray-500 text-sm ml-12">Choose how you want to be notified about activity.</p>
                    </div>
                    
                    <div class="p-8">
                        <form action="{{ route('customer.settings.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_id" value="notifications">
                            
                            <div class="space-y-6">
                                <!-- Email Notifications -->
                                <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-purple-100 hover:bg-purple-50/30 transition-all">
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 text-gray-400">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">Email Notifications</h4>
                                            <p class="text-xs text-gray-500 mt-1">Receive emails about your account activity and promotions.</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_notifications" class="sr-only peer" {{ ($user->settings['email_notifications'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                                
                                <!-- Order Updates -->
                                <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-purple-100 hover:bg-purple-50/30 transition-all">
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 text-gray-400">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">Order Updates</h4>
                                            <p class="text-xs text-gray-500 mt-1">Get notified when there are updates to your orders or deliveries.</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="order_updates" class="sr-only peer" {{ ($user->settings['order_updates'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>

                                <!-- Security Alerts -->
                                <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-purple-100 hover:bg-purple-50/30 transition-all">
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 text-gray-400">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">Security Alerts</h4>
                                            <p class="text-xs text-gray-500 mt-1">Receive alerts about suspicious activity or login attempts.</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="security_alerts" class="sr-only peer" {{ ($user->settings['security_alerts'] ?? true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="flex justify-end pt-4">
                                <button type="submit" class="px-6 py-2.5 bg-white text-gray-900 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm flex items-center gap-2">
                                    <span>Save Preferences</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50/50 rounded-2xl border border-red-100 overflow-hidden" x-data="{ showDeleteModal: false }">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-red-100 rounded-lg text-red-600">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-red-900">Danger Zone</h3>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Delete Account</h4>
                                <p class="text-xs text-gray-500 mt-1">Once you delete your account, there is no going back. Please be certain.</p>
                            </div>
                            <button @click="showDeleteModal = true" class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-all shadow-lg hover:shadow-red-500/30 flex items-center gap-2">
                                <i class="fas fa-trash-alt"></i>
                                <span>Delete Account</span>
                            </button>
                        </div>
                    </div>

                    <!-- Delete Account Modal -->
                    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <form action="{{ route('customer.settings.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Delete Account</h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">Are you sure you want to delete your account? All of your data will be permanently removed. This action cannot be undone.</p>
                                                    
                                                    <div class="mt-4">
                                                        <label for="password" class="block text-sm font-medium text-gray-700">Please enter your password to confirm:</label>
                                                        <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm p-2 border">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Delete Account
                                        </button>
                                        <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection