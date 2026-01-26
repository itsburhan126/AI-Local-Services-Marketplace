@extends('layouts.admin')

@section('title', 'Edit Service Type')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.service_types.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Edit Service Type</h1>
            <p class="text-gray-500 mt-1">Update service type details</p>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="glass-panel rounded-2xl p-8 relative overflow-hidden">
            <form action="{{ route('admin.service_types.update', $serviceType->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Service Type Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $serviceType->name) }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                               placeholder="e.g., Web Development, Graphic Design">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $serviceType->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>

                    <div class="pt-4 flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all">
                            Update Service Type
                        </button>
                        <a href="{{ route('admin.service_types.index') }}" class="px-6 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
