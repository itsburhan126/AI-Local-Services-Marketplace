@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.services.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Edit Service</h1>
            <p class="text-gray-500 mt-1">Update service details</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-4xl">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Service Name</label>
                    <input type="text" name="name" value="{{ old('name', $service->name) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Provider -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Provider</label>
                    <select name="provider_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50" required>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" {{ $service->provider_id == $provider->id ? 'selected' : '' }}>
                                {{ $provider->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('provider_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Category</label>
                    <select name="category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $service->price) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Duration -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" min="1" value="{{ old('duration_minutes', $service->duration_minutes) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Main Image -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Main Image</label>
                @if($service->image)
                    <div class="mb-3 w-32 h-24 rounded-xl overflow-hidden shadow-sm border border-gray-200">
                        <img src="{{ $service->image }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition-all cursor-pointer relative group">
                    <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="space-y-2">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                            <i class="fas fa-image text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Change Main Image</p>
                    </div>
                </div>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Gallery -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Gallery Images</label>
                @if($service->gallery)
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        @foreach($service->gallery as $img)
                            <div class="aspect-square rounded-xl overflow-hidden shadow-sm border border-gray-200">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition-all cursor-pointer relative group">
                    <input type="file" name="gallery[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="space-y-2">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-500 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                            <i class="fas fa-images text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Add to Gallery</p>
                    </div>
                </div>
                @error('gallery') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Update Service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
