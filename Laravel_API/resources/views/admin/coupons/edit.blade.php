@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.coupons.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Edit Coupon</h1>
            <p class="text-gray-500 mt-1">Update discount code details</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-3xl">
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Coupon Code</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50 font-mono uppercase"
                        placeholder="e.g. SUMMER2024">
                    @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Discount Type</label>
                    <select name="type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                        <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Discount Value</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                        placeholder="0.00">
                    @error('value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Min. Purchase Amount</label>
                    <input type="number" step="0.01" name="min_purchase" value="{{ old('min_purchase', $coupon->min_purchase) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                        placeholder="0.00">
                    @error('min_purchase') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Max Usage Limit</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                        placeholder="Leave empty for unlimited">
                    @error('max_uses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Expiration Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('expires_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <span class="text-gray-700 font-medium group-hover:text-indigo-600 transition-colors">Active Coupon</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Update Coupon
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
