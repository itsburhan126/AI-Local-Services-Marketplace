@extends('layouts.freelancer')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Identity Verification</h1>
        <p class="mt-2 text-slate-600 text-lg">Verify your identity to build trust, unlock premium features, and get the "Verified Pro" badge.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            
            @if($user->kyc_status === 'verified')
                <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-8 text-center">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shield-check text-4xl text-emerald-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">You are verified!</h2>
                    <p class="text-slate-600 mb-6">Your identity has been confirmed. You now have full access to all professional features.</p>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full font-medium">
                        <i class="fas fa-check"></i> Verified Pro
                    </div>
                </div>
            @elseif($user->kyc_status === 'pending')
                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-8 text-center">
                    <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-4xl text-amber-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Verification in Progress</h2>
                    <p class="text-slate-600 mb-6">We received your documents and are reviewing them. This usually takes 24-48 hours.</p>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-full font-medium">
                        <i class="fas fa-spinner fa-spin"></i> Reviewing
                    </div>
                </div>
            @elseif($user->kyc_status === 'rejected')
                <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-8 text-center mb-6">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-times-circle text-4xl text-red-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Verification Failed</h2>
                    <p class="text-slate-600 mb-6">Unfortunately, we couldn't verify your documents. Please try again with clearer photos.</p>
                    
                    @if(isset($user->kyc_data['rejection_reason']))
                        <div class="mt-4 mx-auto max-w-lg bg-red-50 rounded-xl border border-red-100 p-4 text-left">
                            <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1">Reason for Rejection:</h4>
                            <p class="text-sm text-red-700 font-medium">{{ $user->kyc_data['rejection_reason'] }}</p>
                        </div>
                    @endif
                </div>
                
                <!-- Verification Form -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900">Submit Documents Again</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('provider.freelancer.verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all" placeholder="+1 (555) 000-0000" required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Document Type -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Document Type</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="document_type" value="passport" class="peer sr-only" checked>
                                        <div class="p-4 rounded-xl border border-slate-200 hover:bg-slate-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all text-center">
                                            <i class="fas fa-passport text-2xl mb-2 text-slate-400 peer-checked:text-indigo-600"></i>
                                            <span class="block text-sm font-medium text-slate-700 peer-checked:text-indigo-900">Passport</span>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="document_type" value="id_card" class="peer sr-only">
                                        <div class="p-4 rounded-xl border border-slate-200 hover:bg-slate-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all text-center">
                                            <i class="fas fa-id-card text-2xl mb-2 text-slate-400 peer-checked:text-indigo-600"></i>
                                            <span class="block text-sm font-medium text-slate-700 peer-checked:text-indigo-900">ID Card</span>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="document_type" value="license" class="peer sr-only">
                                        <div class="p-4 rounded-xl border border-slate-200 hover:bg-slate-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all text-center">
                                            <i class="fas fa-car text-2xl mb-2 text-slate-400 peer-checked:text-indigo-600"></i>
                                            <span class="block text-sm font-medium text-slate-700 peer-checked:text-indigo-900">Driver's License</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Upload Front -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Front Side</label>
                                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors">
                                    <input type="file" name="document_front" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" required>
                                    <div class="pointer-events-none">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                                        <p class="text-sm font-medium text-slate-900">Click to upload or drag and drop</p>
                                        <p class="text-xs text-slate-500 mt-1">SVG, PNG, JPG or GIF (max. 4MB)</p>
                                    </div>
                                </div>
                                @error('document_front')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Back -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Back Side</label>
                                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors">
                                    <input type="file" name="document_back" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" required>
                                    <div class="pointer-events-none">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                                        <p class="text-sm font-medium text-slate-900">Click to upload or drag and drop</p>
                                        <p class="text-xs text-slate-500 mt-1">SVG, PNG, JPG or GIF (max. 4MB)</p>
                                    </div>
                                </div>
                                @error('document_back')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                                Resubmit Documents
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Verification Form -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900">Submit Documents</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('provider.freelancer.verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all" placeholder="+1 (555) 000-0000" required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Document Type -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Document Type</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="document_type" value="passport" class="peer sr-only" checked>
                                        <div class="p-4 rounded-xl border border-slate-200 hover:bg-slate-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all text-center">
                                            <i class="fas fa-passport text-2xl mb-2 text-slate-400 peer-checked:text-indigo-600"></i>
                                            <span class="block text-sm font-medium text-slate-700 peer-checked:text-indigo-900">Passport</span>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="document_type" value="id_card" class="peer sr-only">
                                        <div class="p-4 rounded-xl border border-slate-200 hover:bg-slate-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all text-center">
                                            <i class="fas fa-id-card text-2xl mb-2 text-slate-400 peer-checked:text-indigo-600"></i>
                                            <span class="block text-sm font-medium text-slate-700 peer-checked:text-indigo-900">ID Card</span>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="document_type" value="license" class="peer sr-only">
                                        <div class="p-4 rounded-xl border border-slate-200 hover:bg-slate-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all text-center">
                                            <i class="fas fa-car text-2xl mb-2 text-slate-400 peer-checked:text-indigo-600"></i>
                                            <span class="block text-sm font-medium text-slate-700 peer-checked:text-indigo-900">Driver's License</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Upload Front -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Front Side</label>
                                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors">
                                    <input type="file" name="document_front" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" required>
                                    <div class="pointer-events-none">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                                        <p class="text-sm font-medium text-slate-900">Click to upload or drag and drop</p>
                                        <p class="text-xs text-slate-500 mt-1">SVG, PNG, JPG or GIF (max. 4MB)</p>
                                    </div>
                                </div>
                                @error('document_front')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Back -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Back Side (Optional)</label>
                                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors">
                                    <input type="file" name="document_back" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                                    <div class="pointer-events-none">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                                        <p class="text-sm font-medium text-slate-900">Click to upload or drag and drop</p>
                                        <p class="text-xs text-slate-500 mt-1">SVG, PNG, JPG or GIF (max. 4MB)</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                                Submit for Verification
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Why Verify? -->
            <div class="bg-indigo-900 rounded-2xl p-6 text-white shadow-xl shadow-indigo-900/20">
                <h3 class="text-lg font-bold mb-4">Why verify your ID?</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-xs text-indigo-200"></i>
                        </div>
                        <span class="text-indigo-100 text-sm">Gain the "Verified Pro" badge to stand out to clients.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-xs text-indigo-200"></i>
                        </div>
                        <span class="text-indigo-100 text-sm">Access higher withdrawal limits and faster payouts.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-xs text-indigo-200"></i>
                        </div>
                        <span class="text-indigo-100 text-sm">Increase client trust and get more job invitations.</span>
                    </li>
                </ul>
            </div>

            <!-- Help & Support -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-2">Need Help?</h3>
                <p class="text-slate-600 text-sm mb-4">If you have trouble uploading documents or have questions, our support team is here to help.</p>
                <a href="{{ route('support.index') }}" class="inline-flex items-center text-indigo-600 font-medium text-sm hover:text-indigo-700">
                    Contact Support <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
