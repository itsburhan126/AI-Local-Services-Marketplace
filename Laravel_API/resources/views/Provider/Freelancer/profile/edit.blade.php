@extends('layouts.freelancer')
@section('title', 'Edit Profile')
@section('content')
@php
    $user = Auth::user();
    $profile = $user->providerProfile;
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Profile</h1>
        <a href="{{ route('provider.freelancer.profile') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">Back to Profile</a>
    </div>
    <div class="space-y-8">
        <!-- Basic Information -->
        <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Basic Information</h2>
            <form action="{{ route('provider.freelancer.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Professional Headline</label>
                        <input type="text" name="professional_headline" value="{{ old('professional_headline', $profile->company_name) }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">About</label>
                        <textarea name="description" rows="6" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">{{ old('description', $profile->about) }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors">Save Basic Info</button>
                </div>
            </form>
        </div>

        <!-- Professional Details -->
        <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Professional Details</h2>
            <form action="{{ route('provider.freelancer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Logo</label>
                        <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Cover Image</label>
                        <input type="file" name="cover_image" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors">Save Professional</button>
                </div>
            </form>
        </div>

        <!-- Languages & Skills (App-style selectors) -->
        <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm" 
             x-data="{
                langInput: '',
                skillInput: '',
                langs: {{ json_encode($profile->languages ?? []) }},
                skills: {{ json_encode($profile->skills ?? []) }},
                allLangs: {{ json_encode(($languages ?? collect())->map(fn($l) => $l->name)) }},
                allSkills: {{ json_encode(($skills ?? collect())->map(fn($s) => $s->name)) }},
                showLangDropdown: false,
                showSkillDropdown: false,
                
                get langFiltered() {
                    if (this.langInput === '') return [];
                    const q = this.langInput.toLowerCase();
                    return this.allLangs.filter(n => n.toLowerCase().includes(q) && !this.langs.includes(n)).slice(0, 8);
                },
                get skillFiltered() {
                    if (this.skillInput === '') return [];
                    const q = this.skillInput.toLowerCase();
                    return this.allSkills.filter(n => n.toLowerCase().includes(q) && !this.skills.includes(n)).slice(0, 8);
                },
                addLang(lang) {
                    if (lang && !this.langs.includes(lang)) {
                        this.langs.push(lang);
                    }
                    this.langInput = '';
                    this.showLangDropdown = false;
                },
                addSkill(skill) {
                    if (skill && !this.skills.includes(skill)) {
                        this.skills.push(skill);
                    }
                    this.skillInput = '';
                    this.showSkillDropdown = false;
                }
             }">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Languages & Skills</h2>
            <form action="{{ route('provider.freelancer.profile.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Languages -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Languages</label>
                        <div class="border border-slate-200 rounded-xl p-3 bg-white relative">
                            <div class="flex flex-wrap gap-2 mb-2">
                                <template x-for="(l, i) in langs" :key="i">
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-medium animate-fadeIn">
                                        <span x-text="l"></span>
                                        <button type="button" @click="langs.splice(i,1)" class="hover:text-indigo-900"><i class="fas fa-times text-xs"></i></button>
                                    </span>
                                </template>
                            </div>
                            <div class="relative">
                                <input x-model="langInput" 
                                       @focus="showLangDropdown = true" 
                                       @click.outside="showLangDropdown = false"
                                       @keydown.enter.prevent="addLang(langInput)"
                                       placeholder="Type to add language..." 
                                       class="w-full px-3 py-2 border-0 focus:ring-0 text-sm placeholder:text-slate-400 outline-none">
                                
                                <!-- Dropdown -->
                                <div x-show="showLangDropdown && langFiltered.length > 0" 
                                     class="absolute z-10 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                                     style="display: none;">
                                    <template x-for="n in langFiltered" :key="n">
                                        <button type="button" @click="addLang(n)" class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-sm text-slate-700 flex items-center justify-between">
                                            <span x-text="n"></span>
                                            <i class="fas fa-plus text-indigo-400 text-xs"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <input type="hidden" name="languages" :value="langs.join(',')">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Press Enter to add custom language</p>
                    </div>

                    <!-- Skills -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Skills</label>
                        <div class="border border-slate-200 rounded-xl p-3 bg-white relative">
                            <div class="flex flex-wrap gap-2 mb-2">
                                <template x-for="(s, i) in skills" :key="i">
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-primary-50 text-primary-700 text-sm font-medium animate-fadeIn">
                                        <span x-text="s"></span>
                                        <button type="button" @click="skills.splice(i,1)" class="hover:text-primary-900"><i class="fas fa-times text-xs"></i></button>
                                    </span>
                                </template>
                            </div>
                            <div class="relative">
                                <input x-model="skillInput" 
                                       @focus="showSkillDropdown = true" 
                                       @click.outside="showSkillDropdown = false"
                                       @keydown.enter.prevent="addSkill(skillInput)"
                                       placeholder="Type to add skill..." 
                                       class="w-full px-3 py-2 border-0 focus:ring-0 text-sm placeholder:text-slate-400 outline-none">
                                
                                <!-- Dropdown -->
                                <div x-show="showSkillDropdown && skillFiltered.length > 0" 
                                     class="absolute z-10 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                                     style="display: none;">
                                    <template x-for="n in skillFiltered" :key="n">
                                        <button type="button" @click="addSkill(n)" class="w-full text-left px-4 py-2 hover:bg-primary-50 text-sm text-slate-700 flex items-center justify-between">
                                            <span x-text="n"></span>
                                            <i class="fas fa-plus text-primary-400 text-xs"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <input type="hidden" name="skills" :value="skills.join(',')">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Press Enter to add custom skill</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-bold hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">Save Languages & Skills</button>
                </div>
            </form>
        </div>

        <!-- Personal Details -->
        <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Personal Details</h2>
            <form action="{{ route('provider.freelancer.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div x-data="{ p: '{{ old('phone', $user->phone) }}', showOtp: false }" class="space-y-3">
                        <label class="block text-sm font-bold text-slate-700">Phone</label>
                        <div class="flex gap-2">
                            <input type="tel" x-model="p" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            <button type="button" @click="window.showToast('Coming soon...', 'info')" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors whitespace-nowrap">Send OTP</button>
                        </div>
                        <div x-show="showOtp" class="flex gap-2">
                            <form action="{{ route('provider.freelancer.profile.phone.verify_otp') }}" method="POST" class="flex w-full gap-2">
                                @csrf
                                <input type="text" name="otp" placeholder="Enter OTP" class="flex-1 px-4 py-2 rounded-lg border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition-colors">Verify</button>
                            </form>
                        </div>
                    </div>
                    <div x-data="{
                        open: false,
                        query: '',
                        selected: {{ json_encode(old('country', $profile->country)) }},
                        list: {{ json_encode(($countries ?? collect())->map(fn($c) => ['name' => $c->name, 'code' => $c->code, 'flag' => $c->flag_emoji])) }},
                        get filtered() {
                            if (!this.query) return this.list;
                            const q = this.query.toLowerCase();
                            return this.list.filter(c => c.name.toLowerCase().includes(q) || (c.code || '').toLowerCase().includes(q));
                        },
                        select(c) { this.selected = c.name; this.open = false; }
                    }" class="relative">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Country</label>
                        <div class="relative">
                            <button type="button" @click="open = !open" class="w-full text-left px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-indigo-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all duration-200 flex items-center justify-between group shadow-sm">
                                <span class="flex items-center gap-3">
                                    <template x-if="selected">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xl" x-text="list.find(c => c.name === selected)?.flag || '🏳️'"></span>
                                            <span class="font-semibold text-slate-700" x-text="selected"></span>
                                        </div>
                                    </template>
                                    <span x-show="!selected" class="text-slate-400 font-medium">Select Country</span>
                                </span>
                                <i class="fas fa-chevron-down text-slate-400 group-hover:text-indigo-500 transition-colors" :class="{'rotate-180': open}"></i>
                            </button>
                        </div>
                        <input type="hidden" name="country" :value="selected">
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.outside="open=false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute z-50 mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl ring-1 ring-black/5 overflow-hidden">
                            
                            <!-- Search Header -->
                            <div class="p-3 bg-slate-50 border-b border-slate-100">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input style="padding-left: 49px;" type="text" 
                                           x-model="query" 
                                           placeholder="Search country..." 
                                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all placeholder:text-slate-400">
                                </div>
                            </div>
                            
                            <!-- List -->
                            <ul class="max-h-[300px] overflow-y-auto py-2 custom-scrollbar">
                                <template x-for="c in filtered" :key="c.code">
                                    <li>
                                        <button type="button" @click="select(c)" class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 flex items-center gap-3 transition-all duration-150 group border-l-2 border-transparent hover:border-indigo-500">
                                            <span class="text-2xl shadow-sm rounded-sm" x-text="c.flag || '🏳️'"></span>
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-slate-700 group-hover:text-indigo-700" x-text="c.name"></span>
                                                <span class="text-[10px] text-slate-400 font-mono uppercase tracking-wider" x-text="c.code"></span>
                                            </div>
                                            <i x-show="selected === c.name" class="fas fa-check text-indigo-600 ml-auto text-lg animate-pulse"></i>
                                        </button>
                                    </li>
                                </template>
                                <div x-show="filtered.length === 0" class="p-8 text-center">
                                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <i class="fas fa-globe-americas text-xl"></i>
                                    </div>
                                    <p class="text-slate-500 text-sm font-medium">No country found</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Address</label>
                        <input type="text" name="location" value="{{ old('location', $profile->address) }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all" placeholder="Street, City">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-100">Save Details</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
