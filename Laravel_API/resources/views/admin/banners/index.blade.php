@extends('layouts.admin')

@section('header', 'App Banners')

@section('content')
<div class="content-transition">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Banner Form -->
        <div class="lg:col-span-1">
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Add New Banner</h3>
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Banner Image</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:bg-gray-50 transition-colors">
                                <i class="fas fa-image text-3xl text-gray-300 mb-3"></i>
                                <p class="text-xs text-gray-500 mb-2">Upload high quality image</p>
                                <input type="file" name="image" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Title (Optional)</label>
                            <input type="text" name="title" class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" placeholder="e.g. Summer Sale">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Link (Optional)</label>
                            <input type="text" name="link" class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" placeholder="e.g. /services/cleaning">
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="status" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" checked>
                            <label class="text-sm text-gray-600">Active</label>
                        </div>

                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all">
                            Add Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Banners List -->
        <div class="lg:col-span-2">
            <div class="glass-panel rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Active Banners</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($banners as $banner)
                    <div class="group relative rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                        <img src="{{ $banner->image }}" class="w-full h-40 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex flex-col justify-end p-4">
                            <h4 class="text-white font-bold text-lg">{{ $banner->title ?? 'No Title' }}</h4>
                            <p class="text-white/80 text-xs truncate">{{ $banner->link ?? 'No Link' }}</p>
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-2xl">
                            <i class="fas fa-images"></i>
                        </div>
                        <p>No banners added yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
