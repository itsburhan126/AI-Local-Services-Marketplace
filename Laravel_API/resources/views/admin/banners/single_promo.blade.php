@extends('layouts.admin')

@section('header', 'Single Promotional Banner')

@section('content')
<div class="content-transition">
    <div class="max-w-4xl mx-auto">
        <div class="glass-panel rounded-2xl p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Edit Promotional Banner</h3>
                    <p class="text-gray-500 mt-1">Manage the large single promotional banner on the customer dashboard.</p>
                </div>
                <div class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg font-medium text-sm">
                    <i class="fas fa-eye mr-2"></i>Visible on Dashboard
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.banners.single-promo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Content -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Banner Title</label>
                            <input type="text" name="title" value="{{ old('title', $banner->title) }}" 
                                   class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/50" 
                                   placeholder="e.g. Special Summer Offer">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Subtitle / Description</label>
                            <textarea name="subtitle" rows="3"
                                      class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/50" 
                                      placeholder="e.g. Get 50% off on all cleaning services this weekend.">{{ old('subtitle', $banner->subtitle) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" 
                                       class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/50" 
                                       placeholder="e.g. Book Now">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Link URL</label>
                                <input type="text" name="link" value="{{ old('link', $banner->link) }}" 
                                       class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/50" 
                                       placeholder="e.g. /services/cleaning">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <input type="checkbox" name="status" value="1" id="status" 
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                   {{ old('status', $banner->status) ? 'checked' : '' }}>
                            <label for="status" class="text-sm font-bold text-gray-700 cursor-pointer">
                                Enable Banner
                                <span class="block text-xs font-normal text-gray-500">Show this banner on the customer dashboard</span>
                            </label>
                        </div>
                    </div>

                    <!-- Right Column: Image -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Banner Image</label>
                            <div class="relative group">
                                <div class="aspect-video w-full rounded-2xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center relative">
                                    @if($banner->image)
                                        <img src="{{ Str::startsWith($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image) }}" 
                                             class="w-full h-full object-cover absolute inset-0" 
                                             id="preview-image">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <p class="text-white font-medium">Click to Change</p>
                                        </div>
                                    @else
                                        <div class="text-center p-6 text-gray-400" id="placeholder-content">
                                            <i class="fas fa-image text-4xl mb-2"></i>
                                            <p class="text-sm">No image uploaded</p>
                                        </div>
                                        <img src="" class="w-full h-full object-cover absolute inset-0 hidden" id="preview-image">
                                    @endif
                                    
                                    <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                           onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview-image').classList.remove('hidden'); document.getElementById('placeholder-content')?.classList.add('hidden');">
                                </div>
                                <p class="text-xs text-gray-500 mt-2 text-center">Recommended size: 1200x400px. Max: 4MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
