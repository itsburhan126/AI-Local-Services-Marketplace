@extends('layouts.admin')

@section('content')
<div class="p-8" x-data="{ showRejectModal: false, rejectUrl: '' }">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Verified Users</h1>
            <p class="text-slate-500 mt-1">List of all verified users.</p>
        </div>
        <a href="{{ route('admin.kyc.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-900">Verified Users List</h2>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                {{ $verifiedUsers->total() }} Verified
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Verified At</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($verifiedUsers as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                <div>
                                    <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $user->updated_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <button @click="rejectUrl = '{{ route('admin.kyc.reject', $user->id) }}'; showRejectModal = true" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-50 transition-colors">
                                <i class="fas fa-ban"></i> Unverify
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users-slash text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-lg font-medium text-slate-900">No verified users</p>
                                <p class="text-sm">Verified users will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4">
            {{ $verifiedUsers->links() }}
        </div>
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
                <h3 class="text-xl font-bold text-slate-900">Unverify User</h3>
                <button @click="showRejectModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form :action="rejectUrl" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Reason for Unverification <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all resize-none" placeholder="Please explain why the KYC verification is being revoked..." required></textarea>
                    <p class="text-xs text-slate-500 mt-2">This note will be visible to the user.</p>
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="showRejectModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">
                        Confirm Unverify
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
