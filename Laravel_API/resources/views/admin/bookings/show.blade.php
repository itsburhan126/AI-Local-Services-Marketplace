@extends('layouts.admin')

@section('title', 'Booking #' . $booking->id)

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-start mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.bookings.index') }}" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Order #{{ $booking->id }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                    {{ $booking->status == 'completed' ? 'bg-green-100 text-green-700' : 
                       ($booking->status == 'confirmed' ? 'bg-blue-100 text-blue-700' : 
                       ($booking->status == 'pending' ? 'bg-amber-100 text-amber-700' : 
                       ($booking->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'))) }}">
                    {{ $booking->status }}
                </span>
            </div>
            <p class="text-gray-500 ml-11">Placed on {{ $booking->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        
        <div class="flex gap-3">
            @if($booking->status === 'pending')
            <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600 font-medium hover:bg-red-100 transition-colors" onclick="return confirm('Cancel this booking?')">
                    Reject Order
                </button>
            </form>
            <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all">
                    Approve Order
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Info -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-briefcase text-indigo-500"></i> Service Details
                </h3>
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 rounded-xl bg-gray-100 overflow-hidden">
                        <img src="{{ $booking->service->image ?? 'https://via.placeholder.com/150' }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-800">
                            <a href="{{ route('admin.services.show', $booking->service_id) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $booking->service->name ?? 'Service Removed' }}
                            </a>
                        </h4>
                        <p class="text-gray-500 text-sm mt-1">{{ $booking->service->description ?? 'No description available' }}</p>
                        <div class="mt-3 flex items-center gap-4 text-sm">
                            <span class="flex items-center gap-1 text-gray-600">
                                <i class="far fa-clock"></i> {{ $booking->service->duration_minutes ?? 60 }} mins
                            </span>
                            <span class="flex items-center gap-1 text-gray-600">
                                <i class="fas fa-tag"></i> {{ $booking->service->category->name ?? 'General' }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end gap-2">
                        <span class="text-2xl font-bold text-indigo-600">${{ number_format($booking->total_amount, 2) }}</span>
                        <a href="{{ route('admin.services.show', $booking->service_id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold uppercase hover:bg-indigo-100 transition-colors">
                            View Service
                        </a>
                    </div>
                </div>
            </div>

            <!-- Schedule & Location -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-red-500"></i> Location & Schedule
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Scheduled For</p>
                        <p class="text-gray-800 font-medium text-lg">{{ $booking->scheduled_at->format('l, F d, Y') }}</p>
                        <p class="text-indigo-600 font-bold">{{ $booking->scheduled_at->format('h:i A') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Service Address</p>
                        <p class="text-gray-800 font-medium">{{ $booking->address ?? 'No address provided' }}</p>
                        @if(isset($booking->coordinates))
                        <a href="https://maps.google.com/?q={{ json_decode($booking->coordinates)->lat }},{{ json_decode($booking->coordinates)->lng }}" target="_blank" class="text-xs text-indigo-500 hover:underline mt-1 block">
                            View on Map <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
                
                @if($booking->notes)
                <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Customer Notes</p>
                    <p class="text-gray-700 italic">"{{ $booking->notes }}"</p>
                </div>
                @endif
            </div>
            
            <!-- Financials -->
            <div class="glass-panel rounded-2xl p-6">
                 <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-green-500"></i> Financial Breakdown
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Service Price</span>
                        <span>${{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Platform Commission</span>
                        <span class="text-red-500">-${{ number_format($booking->commission_amount, 2) }}</span>
                    </div>
                    <div class="h-px bg-gray-100 my-2"></div>
                    <div class="flex justify-between font-bold text-lg">
                        <span class="text-gray-800">Provider Earnings</span>
                        <span class="text-green-600">${{ number_format($booking->provider_amount, 2) }}</span>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-between bg-gray-50 p-4 rounded-xl">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase">Payment Status</p>
                        <p class="font-bold {{ $booking->payment_status == 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-gray-400 uppercase">Payment Method</p>
                        <p class="font-bold text-gray-800">{{ ucfirst($booking->payment_method ?? 'Cash') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: People -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Customer</h3>
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $booking->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($booking->user->name) }}" class="w-12 h-12 rounded-full bg-gray-100">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $booking->user->name }}</h4>
                        <p class="text-xs text-gray-500">Member since {{ $booking->user->created_at->format('M Y') }}</p>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-envelope w-5 text-center"></i>
                        <span>{{ $booking->user->email }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-phone w-5 text-center"></i>
                        <span>{{ $booking->user->phone ?? 'No phone' }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $booking->user->id) }}" class="block w-full mt-6 py-2 rounded-lg border border-gray-200 text-gray-600 font-medium text-center hover:bg-gray-50 transition-colors">
                    View Profile
                </a>
            </div>

            <!-- Provider Card -->
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Assigned Provider</h3>
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $booking->provider->profile_photo_url }}" class="w-12 h-12 rounded-full bg-gray-100 object-cover">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $booking->provider->name }}</h4>
                        <div class="flex items-center gap-1 text-xs text-yellow-500">
                            <i class="fas fa-star"></i>
                            <span class="font-bold text-gray-700">{{ $booking->provider->providerProfile->rating ?? '0.0' }}</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-building w-5 text-center"></i>
                        <span>{{ $booking->provider->providerProfile->company_name ?? 'Freelancer' }}</span>
                    </div>
                     <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-phone w-5 text-center"></i>
                        <span>{{ $booking->provider->phone ?? 'No phone' }}</span>
                    </div>
                </div>
                 <a href="{{ route('admin.providers.show', $booking->provider->id) }}" class="block w-full mt-6 py-2 rounded-lg bg-indigo-50 text-indigo-600 font-medium text-center hover:bg-indigo-100 transition-colors">
                    View Provider
                </a>
            </div>
            
            <!-- Quick Actions -->
            <div class="glass-panel rounded-2xl p-6">
                 <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Admin Actions</h3>
                 <div class="space-y-2">
                     <a href="{{ route('admin.bookings.invoice', $booking->id) }}" class="w-full text-left px-4 py-3 rounded-xl hover:bg-gray-50 flex items-center gap-3 text-gray-700 transition-colors group">
                         <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                             <i class="fas fa-file-download"></i>
                         </div>
                         <span class="font-medium">Download Invoice</span>
                     </a>
                      <button onclick="document.getElementById('messageModal').classList.remove('hidden')" class="w-full text-left px-4 py-3 rounded-xl hover:bg-gray-50 flex items-center gap-3 text-gray-700 transition-colors group">
                         <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all">
                             <i class="fas fa-comment-dots"></i>
                         </div>
                         <span class="font-medium">Message Parties</span>
                     </button>
                 </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div id="messageModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="document.getElementById('messageModal').classList.add('hidden')"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-paper-plane text-purple-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Contact Parties</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Choose who you want to contact regarding Order #{{ $booking->id }}.</p>
                                
                                <div class="space-y-3">
                                    <a href="{{ route('admin.chat.index', ['user_id' => $booking->user->id]) }}" 
                                       class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all group">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 group-hover:bg-white flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500 group-hover:text-indigo-600"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-gray-900 group-hover:text-indigo-700">Chat with Customer</p>
                                            <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                                        </div>
                                        <i class="fas fa-comment-dots ml-auto text-gray-400 group-hover:text-indigo-500"></i>
                                    </a>

                                    <a href="{{ route('admin.chat.index', ['user_id' => $booking->provider->id]) }}" 
                                       class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all group">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 group-hover:bg-white flex items-center justify-center">
                                            <i class="fas fa-briefcase text-gray-500 group-hover:text-indigo-600"></i>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-gray-900 group-hover:text-indigo-700">Chat with Provider</p>
                                            <p class="text-xs text-gray-500">{{ $booking->provider->email }}</p>
                                        </div>
                                        <i class="fas fa-comment-dots ml-auto text-gray-400 group-hover:text-indigo-500"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="document.getElementById('messageModal').classList.add('hidden')">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
