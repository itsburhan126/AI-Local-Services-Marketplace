@extends('layouts.admin')

@section('content')
<div class="p-8 max-w-5xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.kyc.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition-colors mb-4 font-medium">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
        <div class="flex justify-between items-start" x-data="{ showRejectModal: false }">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Review KYC Request</h1>
                <p class="text-slate-500 mt-1">Review submitted documents for {{ $user->name }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showRejectModal = true" type="button" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl font-bold hover:bg-red-50 transition-colors shadow-sm">
                    <i class="fas fa-times mr-2"></i> Reject
                </button>
                
                <form action="{{ route('admin.kyc.approve', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this request?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-check mr-2"></i> Approve Request
                    </button>
                </form>
            </div>

            <!-- Rejection Modal -->
            <div x-show="showRejectModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" 
                 style="display: none;">
                
                <div @click.away="showRejectModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 m-4 transform transition-all">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-slate-900">Reject KYC Request</h3>
                        <button @click="showRejectModal = false" class="text-slate-400 hover:text-slate-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.kyc.reject', $user->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Reason for Rejection <span class="text-red-500">*</span></label>
                            <textarea name="rejection_reason" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all resize-none" placeholder="Please explain why the KYC documents were rejected..." required></textarea>
                            <p class="text-xs text-slate-500 mt-2">This note will be visible to the user.</p>
                        </div>
                        
                        <div class="flex gap-3 justify-end">
                            <button type="button" @click="showRejectModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">User Information</h3>
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ $user->profile_photo_url }}" class="w-16 h-16 rounded-full object-cover border-2 border-slate-100">
                    <div>
                        <p class="font-bold text-slate-900 text-lg">{{ $user->name }}</p>
                        <p class="text-slate-500 text-sm">{{ $user->email }}</p>
                        <span class="inline-block mt-2 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 capitalize">
                            {{ $user->service_rule }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Phone Number</label>
                        <p class="font-medium text-slate-900">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Joined Date</label>
                        <p class="font-medium text-slate-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Submitted At</label>
                        <p class="font-medium text-slate-900">{{ isset($user->kyc_data['submitted_at']) ? \Carbon\Carbon::parse($user->kyc_data['submitted_at'])->format('M d, Y h:i A') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Submitted Documents</h3>
                
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-slate-700">Document Type</h4>
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm font-bold capitalize">
                            {{ $user->kyc_data['type'] ?? 'Unknown' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- Front Side -->
                    <div>
                        <h4 class="font-bold text-slate-700 mb-3">Front Side</h4>
                        @if(isset($user->kyc_data['front']))
                            <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50 group">
                                <img src="{{ Storage::url($user->kyc_data['front']) }}" class="w-full h-auto object-contain max-h-[400px]">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                    <a href="{{ Storage::url($user->kyc_data['front']) }}" target="_blank" class="p-3 bg-white rounded-full text-slate-900 hover:text-indigo-600 transition-colors">
                                        <i class="fas fa-expand-alt"></i>
                                    </a>
                                    <a href="{{ Storage::url($user->kyc_data['front']) }}" download class="p-3 bg-white rounded-full text-slate-900 hover:text-indigo-600 transition-colors">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-8 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center text-slate-500">
                                No front side document uploaded.
                            </div>
                        @endif
                    </div>

                    <!-- Back Side -->
                    <div>
                        <h4 class="font-bold text-slate-700 mb-3">Back Side</h4>
                        @if(isset($user->kyc_data['back']))
                            <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-50 group">
                                <img src="{{ Storage::url($user->kyc_data['back']) }}" class="w-full h-auto object-contain max-h-[400px]">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                    <a href="{{ Storage::url($user->kyc_data['back']) }}" target="_blank" class="p-3 bg-white rounded-full text-slate-900 hover:text-indigo-600 transition-colors">
                                        <i class="fas fa-expand-alt"></i>
                                    </a>
                                    <a href="{{ Storage::url($user->kyc_data['back']) }}" download class="p-3 bg-white rounded-full text-slate-900 hover:text-indigo-600 transition-colors">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-8 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center text-slate-500">
                                No back side document uploaded.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
