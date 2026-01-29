@extends('layouts.admin')

@section('title', 'Edit Payout Method')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.payout-methods.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Edit Payout Method</h1>
            <p class="text-gray-500 mt-1">Update withdrawal option</p>
        </div>
    </div>

    <form action="{{ route('admin.payout-methods.update', $payoutMethod->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Method Name</label>
                            <input type="text" name="name" value="{{ old('name', $payoutMethod->name) }}" required 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" 
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">{{ old('description', $payoutMethod->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount ($)</label>
                                <input type="number" name="min_amount" step="0.01" value="{{ old('min_amount', $payoutMethod->min_amount) }}" required 
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount ($)</label>
                                <input type="number" name="max_amount" step="0.01" value="{{ old('max_amount', $payoutMethod->max_amount) }}" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Processing Time (Days)</label>
                            <input type="number" name="processing_time_days" value="{{ old('processing_time_days', $payoutMethod->processing_time_days) }}" required 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields Builder -->
                <div class="glass-panel rounded-2xl p-6" x-data="fieldBuilder({{ json_encode($payoutMethod->fields ?? []) }})">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Required Fields</h3>
                        <button type="button" @click="addField()" class="text-sm text-indigo-600 font-semibold hover:underline">
                            + Add Field
                        </button>
                    </div>
                    
                    <p class="text-sm text-gray-500 mb-4">Define the fields freelancers need to fill out (e.g., Account Number, IBAN, Email).</p>

                    <div class="space-y-4">
                        <template x-for="(field, index) in fields" :key="index">
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 relative group">
                                <button type="button" @click="removeField(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Label</label>
                                        <input type="text" :name="`fields[${index}][label]`" x-model="field.label" required placeholder="e.g. PayPal Email"
                                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Field Name (Key)</label>
                                        <input type="text" :name="`fields[${index}][name]`" x-model="field.name" required placeholder="e.g. email"
                                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                                        <select :name="`fields[${index}][type]`" x-model="field.type" 
                                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200">
                                            <option value="text">Text</option>
                                            <option value="email">Email</option>
                                            <option value="number">Number</option>
                                            <option value="textarea">Textarea</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="fields.length === 0" class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                        No fields defined yet.
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Image -->
            <div class="space-y-6">
                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Settings</h3>
                    
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $payoutMethod->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Logo</h3>
                    <div class="relative w-full aspect-video rounded-xl bg-gray-100 overflow-hidden border-2 border-dashed border-gray-200 hover:border-indigo-500 transition-colors group">
                        <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                        
                        @if($payoutMethod->logo)
                            <img id="image-preview" src="{{ asset('storage/' . $payoutMethod->logo) }}" class="w-full h-full object-contain p-4">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 hidden" id="image-placeholder">
                                <i class="fas fa-cloud-upload-alt text-3xl mb-2 group-hover:text-indigo-500 transition-colors"></i>
                                <span class="text-xs">Click to change logo</span>
                            </div>
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400" id="image-placeholder">
                                <i class="fas fa-cloud-upload-alt text-3xl mb-2 group-hover:text-indigo-500 transition-colors"></i>
                                <span class="text-xs">Click to upload logo</span>
                            </div>
                            <img id="image-preview" class="hidden w-full h-full object-contain p-4">
                        @endif
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all">
                    Update Method
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function fieldBuilder(initialFields) {
        return {
            fields: initialFields || [],
            addField() {
                this.fields.push({
                    label: '',
                    name: '',
                    type: 'text'
                });
            },
            removeField(index) {
                this.fields.splice(index, 1);
            }
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                var placeholder = document.getElementById('image-placeholder');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection