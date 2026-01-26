@extends('layouts.admin')

@section('title', 'Provider Management')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Providers</h1>
            <p class="text-gray-500 mt-1">Manage service providers and approvals</p>
        </div>
        <div class="flex gap-3">
             <div class="glass-panel px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-medium text-gray-600 cursor-pointer hover:bg-white/50 transition-colors">
                <i class="fas fa-filter text-indigo-500"></i>
                <span>Filter</span>
             </div>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Provider</th>
                        <th class="px-4 py-4">Company</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Verification</th>
                        <th class="px-4 py-4">Joined</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($providers as $provider)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden shadow-sm">
                                    @if($provider->avatar)
                                        <img src="{{ $provider->avatar }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-indigo-100 text-indigo-500 font-bold">
                                            {{ substr($provider->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $provider->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $provider->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-medium text-gray-700">
                                {{ $provider->providerProfile->company_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $provider->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                {{ ucfirst($provider->status) }}
                            </span>
                        </td>
                         <td class="px-4 py-4">
                            @if($provider->providerProfile && $provider->providerProfile->is_verified)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-600 flex items-center gap-1 w-fit">
                                    <i class="fas fa-check-circle text-[10px]"></i> Verified
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    Unverified
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">
                            {{ $provider->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.providers.show', $provider->id) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                <p>No providers found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4">
            {{ $providers->links() }}
        </div>
    </div>
</div>
@endsection
