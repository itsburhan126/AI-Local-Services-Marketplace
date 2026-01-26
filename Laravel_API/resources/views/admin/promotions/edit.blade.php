@extends('layouts.admin')

@section('title', 'Edit Promotion')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.promotions.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Edit Promotion</h1>
            <p class="text-gray-500 mt-1">Update campaign details</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-3xl">
        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Campaign Title</label>
                <input type="text" name="title" value="{{ old('title', $promotion->title) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Current Banner</label>
                <div class="h-32 rounded-xl overflow-hidden bg-gray-100 mb-2">
                    <img src="{{ $promotion->image }}" alt="Current Banner" class="w-full h-full object-cover">
                </div>
                <label class="text-sm font-semibold text-gray-700">Change Banner (Optional)</label>
                <div class="relative group">
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                </div>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Target Type</label>
                    <select name="type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                        <option value="service" {{ old('type', $promotion->type) == 'service' ? 'selected' : '' }}>Service</option>
                        <option value="category" {{ old('type', $promotion->type) == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="url" {{ old('type', $promotion->type) == 'url' ? 'selected' : '' }}>External URL</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Target ID / URL</label>
                    <input type="text" name="target_id" value="{{ old('target_id', $promotion->target_id) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('target_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" {{ $promotion->is_active ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <span class="text-gray-700 font-medium group-hover:text-indigo-600 transition-colors">Active Campaign</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Update Promotion
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
