@extends('layouts.admin')

@section('title', 'AI Configuration')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">AI Configuration</h1>
            <p class="text-gray-500 mt-1">Manage Artificial Intelligence settings and keys</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-4xl">
        <form action="{{ route('admin.ai.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Global Enable -->
            <div class="flex items-center justify-between p-6 bg-indigo-50/50 rounded-xl border border-indigo-100">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Enable AI Features</h3>
                    <p class="text-gray-500 text-sm mt-1">Globally enable or disable AI capabilities across the platform</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="ai_enabled" value="1" {{ ($settings['ai_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- API Configuration -->
                <div class="space-y-6">
                    <h3 class="font-bold text-gray-800 border-b pb-2">API Configuration</h3>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">OpenAI API Key</label>
                        <div class="relative">
                            <input type="password" name="openai_api_key" value="{{ $settings['openai_api_key'] ?? '' }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                                placeholder="sk-...">
                            <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <p class="text-xs text-gray-400">Your secret API key from OpenAI Platform</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">AI Model</label>
                        <select name="openai_model" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                            <option value="gpt-3.5-turbo" {{ ($settings['openai_model'] ?? '') == 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo (Fast & Cheap)</option>
                            <option value="gpt-4" {{ ($settings['openai_model'] ?? '') == 'gpt-4' ? 'selected' : '' }}>GPT-4 (Smart & Powerful)</option>
                            <option value="gpt-4-turbo" {{ ($settings['openai_model'] ?? '') == 'gpt-4-turbo' ? 'selected' : '' }}>GPT-4 Turbo (Latest)</option>
                        </select>
                    </div>
                </div>

                <!-- Feature Toggles -->
                <div class="space-y-6">
                    <h3 class="font-bold text-gray-800 border-b pb-2">Active Features</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl cursor-pointer hover:border-indigo-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                                <div>
                                    <span class="block font-semibold text-gray-700">Chat Completion</span>
                                    <span class="text-xs text-gray-500">Enable AI chat for support/descriptions</span>
                                </div>
                            </div>
                            <input type="checkbox" name="chat_completion_enabled" value="1" {{ ($settings['chat_completion_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        </label>

                        <label class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl cursor-pointer hover:border-indigo-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div>
                                    <span class="block font-semibold text-gray-700">Image Generation</span>
                                    <span class="text-xs text-gray-500">Allow generating service images via DALL-E</span>
                                </div>
                            </div>
                            <input type="checkbox" name="image_generation_enabled" value="1" {{ ($settings['image_generation_enabled'] ?? '0') == '1' ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Save AI Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
