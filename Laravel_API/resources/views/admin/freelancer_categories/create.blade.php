@extends('layouts.admin')

@section('title', 'Add Freelancer Category')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.freelancer-categories.index', ['parent_id' => $parentId]) }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Add {{ $parentId ? 'Subcategory' : 'Freelancer Category' }}</h1>
            <p class="text-gray-500 mt-1">Create a new freelancer service category</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-3xl">
        <form action="{{ route('admin.freelancer-categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="type" value="freelancer">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Category Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                        placeholder="e.g. Web Development">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Parent -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Parent Category</label>
                    <select name="parent_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                        <option value="">None (Root Category)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ (old('parent_id') == $parent->id || $parentId == $parent->id) ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Commission -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Commission Rate (%)</label>
                <div class="relative">
                    <input type="number" name="commission_rate" step="0.01" min="0" max="100" value="{{ old('commission_rate', 0) }}"
                        class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500">
                        <i class="fas fa-percent text-sm"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400">Platform commission for services in this category.</p>
                @error('commission_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Image -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Cover Image</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition-all cursor-pointer relative group">
                    <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="space-y-2">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-400">SVG, PNG, JPG or GIF (max. 2MB)</p>
                    </div>
                </div>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Show in Footer -->
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out">
                    <input type="checkbox" name="is_shown_in_footer" id="is_shown_in_footer" value="1" {{ old('is_shown_in_footer') ? 'checked' : '' }} class="peer absolute w-0 h-0 opacity-0">
                    <label for="is_shown_in_footer" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer peer-checked:bg-indigo-600 transition-colors duration-200 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all after:duration-200 peer-checked:after:translate-x-6 peer-checked:after:border-white"></label>
                </div>
                <label for="is_shown_in_footer" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">Show in Footer</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Create Freelancer Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
