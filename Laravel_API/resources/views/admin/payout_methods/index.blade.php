@extends('layouts.admin')

@section('title', 'Payout Methods')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Payout Methods</h1>
            <p class="text-gray-500 mt-1">Manage withdrawal methods for freelancers</p>
        </div>
        <a href="{{ route('admin.payout-methods.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Add Method
        </a>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Method</th>
                        <th class="px-4 py-4">Limits</th>
                        <th class="px-4 py-4">Processing Time</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($methods as $method)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shadow-sm flex items-center justify-center">
                                    @if($method->logo)
                                        <img src="{{ asset('storage/' . $method->logo) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-university text-gray-300 text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $method->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($method->description, 30) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-600">
                                <p>Min: ${{ number_format($method->min_amount, 2) }}</p>
                                @if($method->max_amount)
                                <p>Max: ${{ number_format($method->max_amount, 2) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-sm text-gray-600">{{ $method->processing_time_days }} Days</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-lg {{ $method->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} text-sm font-semibold">
                                {{ $method->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.payout-methods.edit', $method->id) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.payout-methods.destroy', $method->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-red-600 transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-wallet text-3xl text-gray-300"></i>
                                <p>No payout methods found</p>
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