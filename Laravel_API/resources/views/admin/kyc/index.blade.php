@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">KYC Requests</h1>
            <p class="text-slate-500 mt-1">Manage identity verification requests from freelancers.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Pending Review</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $pendingRequests->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <p class="text-slate-500 text-sm font-medium">Verified Users</p>
                        <a href="{{ route('admin.kyc.verified') }}" class="text-xs px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors">
                            View All
                        </a>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $verifiedUsers->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Rejected</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $rejectedUsers->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-900">Pending Requests</h2>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                {{ $pendingRequests->count() }} Pending
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Document Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Submitted At</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingRequests as $user)
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
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 capitalize">
                                {{ $user->kyc_data['type'] ?? 'Unknown' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ isset($user->kyc_data['submitted_at']) ? \Carbon\Carbon::parse($user->kyc_data['submitted_at'])->diffForHumans() : 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.kyc.show', $user->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-colors">
                                Review
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-check-circle text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-lg font-medium text-slate-900">All caught up!</p>
                                <p class="text-sm">No pending KYC requests at the moment.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>
@endsection
