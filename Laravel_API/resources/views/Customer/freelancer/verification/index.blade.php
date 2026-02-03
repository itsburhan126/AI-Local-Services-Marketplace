@extends('layouts.customer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Section -->
    <div class="mb-10 text-center md:text-left flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Identity Verification</h1>
            <p class="mt-3 text-slate-600 text-lg max-w-2xl">Complete your verification to ensure the safety of our marketplace and unlock higher limits.</p>
        </div>
        <div class="hidden md:block">
            <span class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm font-bold">
                <i class="fas fa-shield-alt mr-2"></i> Secure & Encrypted
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg animate-fade-in-up">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        <!-- Main Form -->
        <div class="lg:col-span-8 space-y-8">
            
            @if($user->kyc_status === 'verified')
                <div class="bg-white rounded-2xl border border-emerald-100 shadow-lg shadow-emerald-500/10 p-8 text-center transform transition-all hover:scale-[1.01]">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="fas fa-shield-check text-4xl text-emerald-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Verified Account</h2>
                    <p class="text-slate-600 mb-6">Your identity has been confirmed. Thank you for helping us keep our community safe.</p>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full font-bold text-sm uppercase tracking-wide">
                        <i class="fas fa-check-circle"></i> Verified Status Active
                    </div>
                </div>
            @elseif($user->kyc_status === 'pending')
                <div class="bg-white rounded-2xl border border-amber-100 shadow-lg shadow-amber-500/10 p-8 text-center transform transition-all hover:scale-[1.01]">
                    <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="fas fa-clock text-4xl text-amber-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Verification in Progress</h2>
                    <p class="text-slate-600 mb-6">We have received your documents and are currently reviewing them. This process typically takes 24-48 hours.</p>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-full font-bold text-sm uppercase tracking-wide">
                        <i class="fas fa-spinner fa-spin"></i> Reviewing Documents
                    </div>
                </div>
            @else
                @if($user->kyc_status === 'rejected')
                <div class="bg-white rounded-2xl border border-red-100 shadow-lg shadow-red-500/10 p-6 text-center mb-6">
                    <div class="flex items-center justify-center gap-3 text-red-600 mb-2">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                        <h2 class="text-xl font-bold">Verification Failed</h2>
                    </div>
                    <p class="text-slate-600">Please review your documents and ensure they are clear and valid, then try again.</p>
                    
                    @if(isset($user->kyc_data['rejection_reason']))
                        <div class="mt-4 mx-auto max-w-lg bg-red-50 rounded-xl border border-red-100 p-4 text-left">
                            <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1">Admin Note:</h4>
                            <p class="text-sm text-red-700 font-medium">{{ $user->kyc_data['rejection_reason'] }}</p>
                        </div>
                    @endif
                </div>
                @endif
                
                <!-- Verification Form -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl shadow-slate-200/50 overflow-hidden">
                    <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <i class="fas fa-file-contract text-xl"></i>
                                </span>
                                Submit Documents
                            </h3>
                            <p class="mt-2 text-slate-500 text-sm ml-14">Please upload valid government-issued identification.</p>
                        </div>
                        <div class="hidden sm:block">
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wide">Step 1 of 1</span>
                        </div>
                    </div>
                    <div class="p-8 lg:p-10">
                        <form action="{{ route('customer.verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                            @csrf
                            
                            <!-- Profile Photo -->
                            <div x-data="imagePreview()">
                                <label class="block text-base font-bold text-slate-700 mb-3">Profile Photo <span class="text-red-500">*</span></label>
                                <div class="relative border-3 border-dashed border-slate-200 rounded-2xl p-2 text-center hover:bg-slate-50 hover:border-indigo-400 transition-all group cursor-pointer bg-slate-50/50 min-h-[200px] flex flex-col items-center justify-center overflow-hidden"
                                     :class="{'border-indigo-500 bg-indigo-50/30': isDragging}"
                                     @dragover.prevent="isDragging = true"
                                     @dragleave.prevent="isDragging = false"
                                     @drop.prevent="handleDrop($event)">
                                     
                                    <input type="file" name="profile_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" required @change="handleFile($event)">
                                    
                                    <div x-show="!previewUrl" class="pointer-events-none transition-transform duration-300" :class="{'scale-105': isDragging}">
                                        <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:shadow-md transition-shadow border border-slate-100">
                                            <i class="fas fa-user-circle text-4xl text-indigo-500"></i>
                                        </div>
                                        <p class="text-lg font-bold text-slate-900">Upload Profile Photo</p>
                                        <p class="text-sm text-slate-500 mt-2 font-medium">Click to upload or drag and drop</p>
                                    </div>
                                    
                                    <!-- Image Preview -->
                                    <div x-show="previewUrl" class="absolute inset-0 w-full h-full bg-slate-50 z-20 flex items-center justify-center p-2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <img :src="previewUrl" class="w-32 h-32 object-cover rounded-full shadow-md border-4 border-white">
                                        
                                        <button type="button" @click.prevent="removeFile" class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur text-red-500 rounded-full shadow-lg hover:bg-red-500 hover:text-white focus:outline-none flex items-center justify-center transform hover:scale-110 transition-all z-30" title="Remove image">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('profile_photo')
                                    <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="group">
                                <label class="block text-base font-bold text-slate-700 mb-3 group-hover:text-indigo-600 transition-colors">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-slate-400 group-focus-within:text-indigo-500 transition-colors text-lg"></i>
                                    </div>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all text-lg font-medium text-slate-900 placeholder:text-slate-400" placeholder="+1 (555) 000-0000" required>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">We'll send a verification code to this number.</p>
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Document Type -->
                            <div>
                                <label class="block text-base font-bold text-slate-700 mb-4">Select Document Type</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="document_type" value="passport" class="peer sr-only" checked>
                                        <div class="p-6 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/30 transition-all text-center h-full flex flex-col items-center justify-center gap-3">
                                            <div class="w-14 h-14 rounded-full bg-slate-100 group-hover:bg-indigo-50 peer-checked:bg-indigo-100 flex items-center justify-center transition-colors">
                                                <i class="fas fa-passport text-2xl text-slate-400 group-hover:text-indigo-500 peer-checked:text-indigo-600 transition-colors"></i>
                                            </div>
                                            <span class="block text-base font-bold text-slate-700 peer-checked:text-indigo-900">Passport</span>
                                        </div>
                                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-indigo-600">
                                            <i class="fas fa-check-circle text-xl bg-white rounded-full"></i>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="document_type" value="id_card" class="peer sr-only">
                                        <div class="p-6 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/30 transition-all text-center h-full flex flex-col items-center justify-center gap-3">
                                            <div class="w-14 h-14 rounded-full bg-slate-100 group-hover:bg-indigo-50 peer-checked:bg-indigo-100 flex items-center justify-center transition-colors">
                                                <i class="fas fa-id-card text-2xl text-slate-400 group-hover:text-indigo-500 peer-checked:text-indigo-600 transition-colors"></i>
                                            </div>
                                            <span class="block text-base font-bold text-slate-700 peer-checked:text-indigo-900">National ID</span>
                                        </div>
                                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-indigo-600">
                                            <i class="fas fa-check-circle text-xl bg-white rounded-full"></i>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="document_type" value="license" class="peer sr-only">
                                        <div class="p-6 rounded-2xl border-2 border-slate-200 hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/30 transition-all text-center h-full flex flex-col items-center justify-center gap-3">
                                            <div class="w-14 h-14 rounded-full bg-slate-100 group-hover:bg-indigo-50 peer-checked:bg-indigo-100 flex items-center justify-center transition-colors">
                                                <i class="fas fa-car text-2xl text-slate-400 group-hover:text-indigo-500 peer-checked:text-indigo-600 transition-colors"></i>
                                            </div>
                                            <span class="block text-base font-bold text-slate-700 peer-checked:text-indigo-900">Driver's License</span>
                                        </div>
                                        <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-indigo-600">
                                            <i class="fas fa-check-circle text-xl bg-white rounded-full"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Upload Front -->
                            <div x-data="imagePreview()">
                                <label class="block text-base font-bold text-slate-700 mb-3">Front Side <span class="text-red-500">*</span></label>
                                <div class="relative border-3 border-dashed border-slate-200 rounded-2xl p-2 text-center hover:bg-slate-50 hover:border-indigo-400 transition-all group cursor-pointer bg-slate-50/50 min-h-[250px] flex flex-col items-center justify-center overflow-hidden"
                                     :class="{'border-indigo-500 bg-indigo-50/30': isDragging}"
                                     @dragover.prevent="isDragging = true"
                                     @dragleave.prevent="isDragging = false"
                                     @drop.prevent="handleDrop($event)">
                                     
                                    <input type="file" name="document_front" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" required @change="handleFile($event)">
                                    
                                    <div x-show="!previewUrl" class="pointer-events-none transition-transform duration-300" :class="{'scale-105': isDragging}">
                                        <div class="w-20 h-20 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:shadow-md transition-shadow border border-slate-100">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-indigo-500"></i>
                                        </div>
                                        <p class="text-lg font-bold text-slate-900">Click to upload or drag and drop</p>
                                        <p class="text-sm text-slate-500 mt-2 font-medium">High resolution images (max. 4MB)</p>
                                    </div>
                                    
                                    <!-- Image Preview -->
                                    <div x-show="previewUrl" class="absolute inset-0 w-full h-full bg-slate-50 z-20 flex items-center justify-center p-2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <img :src="previewUrl" class="max-w-full max-h-full object-contain rounded-xl shadow-md">
                                        
                                        <button type="button" @click.prevent="removeFile" class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur text-red-500 rounded-full shadow-lg hover:bg-red-500 hover:text-white focus:outline-none flex items-center justify-center transform hover:scale-110 transition-all z-30" title="Remove image">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        
                                        <div class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-colors flex items-center justify-center pointer-events-none"></div>
                                    </div>
                                </div>
                                @error('document_front')
                                    <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Back -->
                            <div x-data="imagePreview()">
                                <label class="block text-base font-bold text-slate-700 mb-3">Back Side <span class="text-slate-400 font-normal ml-1">(Optional)</span></label>
                                <div class="relative border-3 border-dashed border-slate-200 rounded-2xl p-2 text-center hover:bg-slate-50 hover:border-indigo-400 transition-all group cursor-pointer bg-slate-50/50 min-h-[250px] flex flex-col items-center justify-center overflow-hidden"
                                     :class="{'border-indigo-500 bg-indigo-50/30': isDragging}"
                                     @dragover.prevent="isDragging = true"
                                     @dragleave.prevent="isDragging = false"
                                     @drop.prevent="handleDrop($event)">
                                     
                                    <input type="file" name="document_back" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" @change="handleFile($event)">
                                    
                                    <div x-show="!previewUrl" class="pointer-events-none transition-transform duration-300" :class="{'scale-105': isDragging}">
                                        <div class="w-20 h-20 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:shadow-md transition-shadow border border-slate-100">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                                        </div>
                                        <p class="text-lg font-bold text-slate-900">Click to upload or drag and drop</p>
                                        <p class="text-sm text-slate-500 mt-2 font-medium">High resolution images (max. 4MB)</p>
                                    </div>
                                    
                                    <!-- Image Preview -->
                                    <div x-show="previewUrl" class="absolute inset-0 w-full h-full bg-slate-50 z-20 flex items-center justify-center p-2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                                        <img :src="previewUrl" class="max-w-full max-h-full object-contain rounded-xl shadow-md">
                                        
                                        <button type="button" @click.prevent="removeFile" class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur text-red-500 rounded-full shadow-lg hover:bg-red-500 hover:text-white focus:outline-none flex items-center justify-center transform hover:scale-110 transition-all z-30" title="Remove image">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        
                                        <div class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-colors flex items-center justify-center pointer-events-none"></div>
                                    </div>
                                </div>
                                @error('document_back')
                                    <p class="mt-2 text-sm text-red-600 font-medium flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-5 px-6 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold text-xl rounded-2xl shadow-xl shadow-indigo-500/30 transition-all transform hover:-translate-y-1 hover:shadow-indigo-500/50 focus:ring-4 focus:ring-indigo-500/50 flex items-center justify-center gap-3">
                                <i class="fas fa-shield-check"></i> Verify My Identity
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Why Verify? -->
            <div class="bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-900 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-900/30 relative overflow-hidden border border-indigo-500/20">
                <div class="absolute top-0 right-0 -mr-12 -mt-12 w-48 h-48 bg-indigo-500/30 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-purple-500/30 rounded-full blur-3xl"></div>
                
                <h3 class="text-2xl font-bold mb-8 relative z-10 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <i class="fas fa-shield-alt text-indigo-300"></i>
                    </span>
                    Why verify?
                </h3>
                
                <ul class="space-y-6 relative z-10">
                    <li class="flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center flex-shrink-0 border border-emerald-500/20 group-hover:bg-emerald-500/20 transition-colors">
                            <i class="fas fa-check text-emerald-400"></i>
                        </div>
                        <div>
                            <span class="block font-bold text-white text-lg">Verified Badge</span>
                            <span class="text-slate-300 text-sm leading-relaxed">Get a trusted badge that boosts your credibility by 3x.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center flex-shrink-0 border border-blue-500/20 group-hover:bg-blue-500/20 transition-colors">
                            <i class="fas fa-lock text-blue-400"></i>
                        </div>
                        <div>
                            <span class="block font-bold text-white text-lg">Bank-Grade Security</span>
                            <span class="text-slate-300 text-sm leading-relaxed">Your data is encrypted with 256-bit SSL protection.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center flex-shrink-0 border border-amber-500/20 group-hover:bg-amber-500/20 transition-colors">
                            <i class="fas fa-infinity text-amber-400"></i>
                        </div>
                        <div>
                            <span class="block font-bold text-white text-lg">Unlimited Access</span>
                            <span class="text-slate-300 text-sm leading-relaxed">Remove all withdrawal and transaction limits.</span>
                        </div>
                    </li>
                </ul>
                
                <div class="mt-8 pt-6 border-t border-white/10 text-center">
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Trusted by 10k+ Freelancers</p>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/40 p-8 group hover:border-indigo-200 transition-colors relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Need Assistance?</h3>
                    </div>
                    <p class="text-slate-600 mb-6 leading-relaxed">Having trouble with your documents? Our support team is available 24/7 to help you get verified instantly.</p>
                    <a href="{{ route('support.index') }}" class="block w-full py-4 bg-white border-2 border-slate-100 hover:border-indigo-600 text-slate-700 hover:text-indigo-600 font-bold text-center rounded-xl transition-all duration-300 group-hover:shadow-lg">
                        Contact Support <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imagePreview', () => ({
            previewUrl: null,
            isDragging: false,
            
            handleFile(event) {
                const file = event.target.files[0];
                if (file) {
                    this.previewFile(file);
                }
            },
            
            handleDrop(event) {
                this.isDragging = false;
                const file = event.dataTransfer.files[0];
                if (file) {
                    const input = this.$el.querySelector('input[type="file"]');
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    this.previewFile(file);
                }
            },
            
            previewFile(file) {
                if (!file.type.match('image.*')) return;
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            
            removeFile() {
                this.previewUrl = null;
                const input = this.$el.querySelector('input[type="file"]');
                input.value = '';
            }
        }));
    });
</script>
@endpush
