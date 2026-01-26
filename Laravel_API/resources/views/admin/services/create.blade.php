@extends('layouts.admin')

@section('title', 'Add Service')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.services.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Add Service</h1>
            <p class="text-gray-500 mt-1">Create a new service listing</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-4xl">
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Service Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                        placeholder="e.g. Deep House Cleaning">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Provider -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Provider</label>
                    <select name="provider_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50" required>
                        <option value="">Select Provider</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                        @endforeach
                    </select>
                    @error('provider_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Category</label>
                    <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Duration -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" min="1" value="{{ old('duration_minutes', 60) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Main Image -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Main Image</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition-all cursor-pointer relative group">
                    <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="space-y-2">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                            <i class="fas fa-image text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Upload Main Image</p>
                    </div>
                </div>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Gallery -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Gallery Images</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition-all cursor-pointer relative group">
                    <input type="file" name="gallery[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="space-y-2">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-500 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                            <i class="fas fa-images text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Upload Gallery Images</p>
                        <p class="text-xs text-gray-400">Select multiple files</p>
                    </div>
                </div>
                @error('gallery') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Create Service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
