@extends('layouts.freelancer')

@section('title', 'Create New Gig')

@section('content')
<div class="w-full mx-auto pb-12 px-4 sm:px-6 lg:px-8" 
    x-data="{ 
        step: 1,
        categories: {{ $categories->toJson() }},
        selectedCategory: '{{ old('category_id') }}',
        subCategories: [],
        selectedSubCategory: '{{ old('sub_category_id') }}',
        
        // Tags
        tags: '{{ old('tags') }}' ? '{{ old('tags') }}'.split(',') : [],
        tagInput: '',
        suggestedTags: ['Logo Design', 'Web Development', 'Mobile App', 'SEO', 'Content Writing', 'Digital Marketing', 'Video Editing', 'Graphic Design', 'Data Entry', 'Virtual Assistant', 'WordPress', 'Shopify', 'React', 'Flutter', 'Laravel'],
        filteredTags: [],
        
        // Packages
        activePackageTab: 'Basic',
        
        // Extras
        extras: {{ json_encode(old('extras', [])) }},
        
        // FAQs
        faqs: {{ json_encode(old('faqs', [['question' => '', 'answer' => '']])) }},
        
        // Gallery
        thumbnailPreview: null,
        galleryPreviews: [],

        init() {
            this.filteredTags = this.suggestedTags;
            if (this.selectedCategory) {
                this.updateSubCategories();
            }
        },

        handleThumbnailChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.thumbnailPreview = URL.createObjectURL(file);
            }
        },

        handleGalleryChange(event) {
            const files = event.target.files;
            if (files) {
                for (let i = 0; i < files.length; i++) {
                    this.galleryPreviews.push(URL.createObjectURL(files[i]));
                }
            }
        },
        
        removeGalleryImage(index) {
            this.galleryPreviews.splice(index, 1);
        },

        updateSubCategories() {
            const category = this.categories.find(c => c.id == this.selectedCategory);
            this.subCategories = category ? category.children : [];
            // Reset subcategory if it doesn't belong to new category
            if (this.subCategories.length > 0 && !this.subCategories.find(s => s.id == this.selectedSubCategory)) {
                 // Keep selected if it's valid (e.g. on init), otherwise clear
                 // Actually on init, selectedSubCategory comes from old(), which should be valid for the selectedCategory.
                 // But on change, we should clear it.
            }
        },

        filterTags() {
            if (this.tagInput === '') {
                this.filteredTags = this.suggestedTags;
            } else {
                this.filteredTags = this.suggestedTags.filter(tag => tag.toLowerCase().includes(this.tagInput.toLowerCase()));
            }
        },

        addTag(tag) {
            if (tag && !this.tags.includes(tag) && this.tags.length < 5) {
                this.tags.push(tag);
            }
            this.tagInput = '';
            this.filterTags();
        },

        removeTag(index) {
            this.tags.splice(index, 1);
        },

        addExtra() {
            this.extras.push({ title: '', price: '', additional_days: 0 });
        },

        removeExtra(index) {
            this.extras.splice(index, 1);
        },

        addFaq() {
            this.faqs.push({ question: '', answer: '' });
        },

        removeFaq(index) {
            this.faqs.splice(index, 1);
        }
    }"
>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
            <a href="{{ route('provider.freelancer.gigs.index') }}" class="hover:text-primary-600 transition-colors"><i class="fas fa-arrow-left mr-1"></i> Back to Gigs</a>
        </div>
        <h2 class="text-3xl font-bold text-slate-800">Create a New Gig</h2>
        <p class="text-slate-500">Showcase your skills and start earning.</p>
    </div>

    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 rounded-full -z-10"></div>
            
            <!-- Step 1 Indicator -->
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2 cursor-pointer" @click="step = 1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300"
                    :class="step >= 1 ? 'bg-primary-600 border-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-white border-slate-300 text-slate-400'">
                    1
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider transition-colors duration-300"
                    :class="step >= 1 ? 'text-primary-700' : 'text-slate-400'">Overview</span>
            </div>

            <!-- Step 2 Indicator -->
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2 cursor-pointer" @click="step = 2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300"
                    :class="step >= 2 ? 'bg-primary-600 border-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-white border-slate-300 text-slate-400'">
                    2
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider transition-colors duration-300"
                    :class="step >= 2 ? 'text-primary-700' : 'text-slate-400'">Pricing</span>
            </div>

            <!-- Step 3 Indicator -->
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2 cursor-pointer" @click="step = 3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300"
                    :class="step >= 3 ? 'bg-primary-600 border-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-white border-slate-300 text-slate-400'">
                    3
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider transition-colors duration-300"
                    :class="step >= 3 ? 'text-primary-700' : 'text-slate-400'">Description</span>
            </div>

            <!-- Step 4 Indicator -->
            <div class="flex flex-col items-center gap-2 bg-slate-50 px-2 cursor-pointer" @click="step = 4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300"
                    :class="step >= 4 ? 'bg-primary-600 border-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-white border-slate-300 text-slate-400'">
                    4
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider transition-colors duration-300"
                    :class="step >= 4 ? 'text-primary-700' : 'text-slate-400'">Gallery</span>
            </div>
        </div>
    </div>

    <form action="{{ route('provider.freelancer.gigs.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @csrf
        
        <!-- Step 1: Overview -->
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            <div class="p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Gig Overview</h3>
                    <p class="text-sm text-slate-500">The foundation of your service.</p>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gig Title <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-400 font-medium text-sm">I will</span>
                        <input type="text" name="title" required value="{{ old('title') }}"
                            class="block w-full pl-14 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all placeholder-slate-300"
                            placeholder="do something I'm really good at">
                    </div>
                    <p class="text-xs text-slate-400 mt-2 text-right">0/80 max</p>
                </div>

                <!-- Category, Sub-Category & Service Type -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" required x-model="selectedCategory" @change="updateSubCategories(); selectedSubCategory = ''" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all bg-white">
                            <option value="">Select Category</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                    </div>

                    <div x-show="subCategories.length > 0" x-transition>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sub Category <span class="text-red-500">*</span></label>
                        <select name="sub_category_id" x-model="selectedSubCategory" :required="subCategories.length > 0" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all bg-white">
                            <option value="">Select Sub Category</option>
                            <template x-for="sub in subCategories" :key="sub.id">
                                <option :value="sub.id" x-text="sub.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Service Type <span class="text-red-500">*</span></label>
                        <select name="service_type_id" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all bg-white">
                            <option value="">Select Service Type</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}" {{ old('service_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Search Tags -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search Tags</label>
                    
                    <div class="relative">
                        <div class="flex flex-wrap items-center gap-2 p-2 border border-slate-200 rounded-xl focus-within:ring-2 focus-within:ring-primary-100 focus-within:border-primary-500 bg-white min-h-[50px]">
                            <template x-for="(tag, index) in tags" :key="index">
                                <span class="inline-flex items-center gap-1 bg-primary-50 text-primary-700 px-2 py-1 rounded-lg text-sm font-medium">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(index)" class="hover:text-primary-900"><i class="fas fa-times"></i></button>
                                </span>
                            </template>
                            <input type="text" x-model="tagInput" @keydown.enter.prevent="addTag(tagInput)" @input="filterTags()"
                                class="flex-1 min-w-[120px] bg-transparent border-none focus:ring-0 p-1 placeholder-slate-300"
                                placeholder="Type and press enter or select below">
                        </div>

                        <!-- Suggestions Dropdown -->
                        <div x-show="tagInput.length > 0 && filteredTags.length > 0" class="absolute z-10 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="suggestion in filteredTags" :key="suggestion">
                                <div @click="addTag(suggestion)" class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-sm text-slate-700" x-text="suggestion"></div>
                            </template>
                        </div>
                    </div>
                    
                    <input type="hidden" name="tags" :value="tags.join(',')">
                    <p class="text-xs text-slate-400 mt-2">Up to 5 tags to help buyers find your gig.</p>
                </div>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="button" @click="step = 2" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-primary-500/30">
                    Save & Continue <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Pricing -->
        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
            <div class="p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Scope & Pricing</h3>
                    <p class="text-sm text-slate-500">Define your packages.</p>
                </div>

                <!-- Package Tabs (Mobile Only) -->
                <div class="flex border-b border-slate-200 mb-6 md:hidden">
                    @foreach(['Basic', 'Standard', 'Premium'] as $tier)
                        <button type="button" 
                            @click="activePackageTab = '{{ $tier }}'"
                            :class="activePackageTab === '{{ $tier }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700'"
                            class="flex-1 pb-4 text-sm font-bold border-b-2 transition-colors">
                            {{ $tier }}
                        </button>
                    @endforeach
                </div>

                <!-- Desktop Package Headers -->
                <div class="hidden md:grid md:grid-cols-3 gap-6 mb-4">
                    @foreach(['Basic', 'Standard', 'Premium'] as $tier)
                        <div class="text-center pb-4 border-b-2 border-primary-600">
                            <h4 class="text-lg font-bold text-primary-700">{{ $tier }}</h4>
                            <p class="text-xs text-slate-500">Package</p>
                        </div>
                    @endforeach
                </div>

                <!-- Package Forms -->
                <div class="md:grid md:grid-cols-3 md:gap-6">
                @foreach(['Basic', 'Standard', 'Premium'] as $index => $tier)
                    <div :class="activePackageTab === '{{ $tier }}' ? 'block' : 'hidden'" class="md:block md:col-span-1 bg-slate-50 md:bg-white rounded-xl md:rounded-none p-4 md:p-0 md:border-r md:last:border-r-0 border-slate-100 md:border-none">
                        <input type="hidden" name="packages[{{ $index }}][tier]" value="{{ $tier }}">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-500 mb-1.5">Package Name</label>
                                <input type="text" name="packages[{{ $index }}][name]" value="{{ old('packages.'.$index.'.name', $tier) }}" placeholder="e.g. {{ $tier }} Plan" class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-100 focus:border-primary-500 text-sm bg-white">
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-500 mb-1.5">Description</label>
                                <textarea name="packages[{{ $index }}][description]" rows="4" placeholder="Describe what you offer..." class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-100 focus:border-primary-500 text-sm bg-white resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs uppercase font-bold text-slate-500 mb-1.5">Delivery</label>
                                    <select name="packages[{{ $index }}][delivery_days]" class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-100 focus:border-primary-500 text-sm bg-white">
                                        @foreach([1,2,3,4,5,6,7,10,14,21,30] as $days)
                                            <option value="{{ $days }}">{{ $days }} Day{{ $days > 1 ? 's' : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs uppercase font-bold text-slate-500 mb-1.5">Revisions</label>
                                    <select name="packages[{{ $index }}][revisions]" class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-100 focus:border-primary-500 text-sm bg-white">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="5">5</option>
                                        <option value="-1">Unlimited</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-500 mb-1.5">Price ($)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                                    <input type="number" name="packages[{{ $index }}][price]" placeholder="0" min="5" class="block w-full pl-8 pr-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-100 focus:border-primary-500 text-sm font-bold text-slate-800 bg-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-500 mb-1.5">Features</label>
                                <textarea name="packages[{{ $index }}][features]" rows="3" placeholder="Source file, High resolution, etc." class="block w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-100 focus:border-primary-500 text-sm bg-white resize-none"></textarea>
                                <p class="text-xs text-slate-400 mt-1">Comma separated</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

                <!-- Extras Section -->
                <div class="pt-8 mt-8 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                         <h4 class="font-bold text-slate-800">My Extras</h4>
                         <button type="button" @click="addExtra()" class="text-sm font-medium text-primary-600 hover:text-primary-700 flex items-center gap-1">
                            <i class="fas fa-plus"></i> Add Extra
                         </button>
                    </div>
                    
                    <div class="space-y-4">
                        <template x-for="(extra, index) in extras" :key="index">
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 relative">
                                <button type="button" @click="removeExtra(index)" class="absolute top-2 right-2 text-slate-400 hover:text-red-500">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Title</label>
                                        <input type="text" :name="'extras['+index+'][title]'" x-model="extra.title" placeholder="I will deliver in..." class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Price ($)</label>
                                        <input type="number" :name="'extras['+index+'][price]'" x-model="extra.price" placeholder="0.00" class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Additional Days</label>
                                        <select :name="'extras['+index+'][additional_days]'" x-model="extra.additional_days" class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white">
                                            <option value="0">None</option>
                                            <option value="1">1 Day</option>
                                            <option value="2">2 Days</option>
                                            <option value="3">3 Days</option>
                                            <option value="5">5 Days</option>
                                            <option value="7">1 Week</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="extras.length === 0" class="text-center py-4 text-slate-400 text-sm">
                            No extras added.
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-between">
                <button type="button" @click="step = 1" class="text-slate-500 font-medium hover:text-slate-700 px-4 py-2">Back</button>
                <button type="button" @click="step = 3" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-primary-500/30">
                    Save & Continue <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: Description & FAQ -->
        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
            <div class="p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Description</h3>
                    <p class="text-sm text-slate-500">Briefly Describe Your Gig.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gig Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="10" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all text-sm leading-relaxed" placeholder="Describe your service in detail..."></textarea>
                    <p class="text-xs text-slate-400 mt-2">1200 characters max.</p>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <h4 class="font-bold text-slate-800 mb-4">Frequently Asked Questions</h4>
                    <div class="space-y-4">
                        <template x-for="(faq, index) in faqs" :key="index">
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 relative group">
                                <button type="button" @click="removeFaq(index)" class="absolute top-2 right-2 text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-times"></i>
                                </button>
                                <input type="text" :name="'faqs['+index+'][question]'" x-model="faq.question" placeholder="Add a Question: e.g. Do you provide source files?" class="block w-full bg-transparent border-none p-0 text-sm font-bold text-slate-800 placeholder-slate-400 focus:ring-0 mb-2">
                                <textarea :name="'faqs['+index+'][answer]'" x-model="faq.answer" rows="2" placeholder="Add an Answer: e.g. Yes, I provide source files for all packages." class="block w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-100 focus:border-primary-500 resize-none"></textarea>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addFaq()" class="mt-4 text-sm font-medium text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fas fa-plus"></i> Add FAQ
                    </button>
                </div>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-between">
                <button type="button" @click="step = 2" class="text-slate-500 font-medium hover:text-slate-700 px-4 py-2">Back</button>
                <button type="button" @click="step = 4" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-primary-500/30">
                    Save & Continue <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Step 4: Gallery -->
        <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
            <div class="p-8 space-y-6">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Showcase Your Work</h3>
                    <p class="text-sm text-slate-500">Get noticed by the right buyers with visual examples.</p>
                </div>

                <!-- Thumbnail -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gig Thumbnail <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors cursor-pointer group relative overflow-hidden h-64 flex flex-col justify-center items-center">
                        <input type="file" name="thumbnail" accept="image/*" required @change="handleThumbnailChange" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <!-- Placeholder -->
                        <div x-show="!thumbnailPreview" class="group-hover:scale-105 transition-transform duration-200">
                            <div class="h-12 w-12 bg-primary-50 text-primary-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-image text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-700">Drag & drop or <span class="text-primary-600">Browse</span></p>
                            <p class="text-xs text-slate-400 mt-1">JPEG, JPG, PNG (Max 5MB)</p>
                        </div>

                        <!-- Preview -->
                        <div x-show="thumbnailPreview" class="absolute inset-0 w-full h-full">
                             <img :src="thumbnailPreview" class="w-full h-full object-cover rounded-lg">
                             <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                 <p class="text-white font-medium"><i class="fas fa-edit mr-2"></i>Change Image</p>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gallery Images (Optional)</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Add Button -->
                        <div class="aspect-square border-2 border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors cursor-pointer relative">
                            <input type="file" name="images[]" accept="image/*" multiple @change="handleGalleryChange" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <i class="fas fa-plus text-2xl mb-2"></i>
                            <span class="text-xs font-medium">Add Images</span>
                        </div>

                        <!-- Previews -->
                        <template x-for="(img, index) in galleryPreviews" :key="index">
                            <div class="aspect-square rounded-xl overflow-hidden relative group border border-slate-200">
                                <img :src="img" class="w-full h-full object-cover">
                                <button type="button" @click="removeGalleryImage(index)" class="absolute top-2 right-2 bg-red-500 text-white h-6 w-6 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Documents -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Documents (PDF)</label>
                    <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl bg-slate-50">
                        <div class="h-10 w-10 bg-red-100 text-red-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-700">Upload PDF Brochure</p>
                            <p class="text-xs text-slate-400">Max 2MB</p>
                        </div>
                        <button type="button" class="text-sm font-medium text-primary-600 hover:text-primary-700">Upload</button>
                    </div>
                </div>

                <!-- Declaration -->
                <div class="pt-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-1 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-slate-600">I declare that this service complies with the community standards and I own the rights to the media uploaded.</span>
                    </label>
                </div>
            </div>
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-between">
                <button type="button" @click="step = 3" class="text-slate-500 font-medium hover:text-slate-700 px-4 py-2">Back</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-green-500/30 flex items-center gap-2">
                    <i class="fas fa-check"></i> Publish Gig
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Alpine.js for Step Handling -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection