@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Coupons</h1>
            <p class="text-gray-500 mt-1">Manage discount codes</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Create Coupon
        </a>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Code</th>
                        <th class="px-4 py-4">Discount</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Expires</th>
                        <th class="px-4 py-4">Usage</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold font-mono text-sm">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <span class="font-bold text-gray-800 font-mono tracking-wide">{{ $coupon->code }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-bold text-gray-800">
                                {{ $coupon->type == 'fixed' ? '$' : '' }}{{ $coupon->value }}{{ $coupon->type == 'percent' ? '%' : '' }}
                            </span>
                            <span class="text-xs text-gray-500 block">Min: ${{ $coupon->min_purchase ?? '0' }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $coupon->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                        </td>
                         <td class="px-4 py-4 text-sm text-gray-500">
                            {{ $coupon->uses_count ?? 0 }} / {{ $coupon->max_uses ?? '∞' }}
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-ticket-alt text-3xl text-gray-300"></i>
                                <p>No coupons found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4">
            {{ $coupons->links() }}
        </div>
    </div>
</div>
@endsection
