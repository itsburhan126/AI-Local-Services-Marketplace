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
                                <div class="aspect-[3/1] w-full rounded-2xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center relative transition-all hover:border-indigo-400 hover:bg-gray-50 group-hover:shadow-md" id="drop-zone">
                    @php
                        $imageUrl = $banner->image ? (\Illuminate\Support\Str::startsWith($banner->image, ['http', 'https']) ? $banner->image : asset('storage/' . $banner->image)) : 'https://placehold.co/1200x400?text=Upload+Promotional+Banner';
                    @endphp
                    <img src="{{ $imageUrl }}" 
                         class="w-full h-full object-cover absolute inset-0 transition-transform duration-500" 
                         id="preview-image"
                         onerror="this.onerror=null;this.src='https://placehold.co/1200x400?text=Image+Not+Found';"
                         alt="Banner Preview">
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center z-10 pointer-events-none backdrop-blur-[2px]">
                                        <div class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-6 py-3 rounded-full font-medium flex items-center gap-2 transform translate-y-4 group-hover:translate-y-0 transition-transform shadow-xl">
                                            <i class="fas fa-camera"></i> <span id="upload-text">Change Image</span>
                                        </div>
                                        <p class="text-white/80 text-xs mt-3 font-medium">Click or Drag & Drop</p>
                                    </div>
                                    
                                    <input type="file" name="image" id="banner-image-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" 
                                           accept="image/*"
                                           onchange="handleFileSelect(this)">
                                </div>
                                
                                <div class="flex items-center justify-between mt-3 px-1">
                                    <div class="flex flex-col">
                                        <p class="text-xs text-gray-500">Recommended: 1200x400px. Max: 4MB</p>
                                        <p id="file-name" class="text-xs text-indigo-600 font-medium mt-1 hidden"></p>
                                    </div>
                                    <button type="button" onclick="document.getElementById('banner-image-input').click()" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-upload mr-1.5"></i> Upload New
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function handleFileSelect(input) {
        var preview = document.getElementById('preview-image');
        var fileNameDisplay = document.getElementById('file-name');
        var file = input.files[0];
        
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select an image file');
                return;
            }

            var reader = new FileReader();

            // Show loading state if needed, or just immediate preview
            preview.style.opacity = '0.5';

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.style.opacity = '1';
                
                // Show file name
                fileNameDisplay.textContent = 'Selected: ' + file.name;
                fileNameDisplay.classList.remove('hidden');
                
                // Add success animation
                preview.classList.add('scale-105');
                setTimeout(() => {
                    preview.classList.remove('scale-105');
                }, 300);
            }

            reader.readAsDataURL(file);
        }
    }
    
    // Drag and drop visual feedback
    const dropZone = document.getElementById('drop-zone');
    const input = document.getElementById('banner-image-input');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'ring-4', 'ring-indigo-500/20');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'ring-4', 'ring-indigo-500/20');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            input.files = files;
            handleFileSelect(input);
        }
    }
</script>
@endsection
