@extends('layouts.admin')

@section('title', 'Configure ' . $paymentGateway->title)

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.payment-gateways.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Configure {{ $paymentGateway->title }}</h1>
            <p class="text-gray-500 mt-1">Update credentials and settings</p>
        </div>
    </div>

    <form action="{{ route('admin.payment-gateways.update', $paymentGateway->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Settings -->
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-panel rounded-2xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">General Settings</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Display Title</label>
                            <input type="text" name="title" value="{{ old('title', $paymentGateway->title) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Environment Mode</label>
                                <select name="mode" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                                    <option value="sandbox" {{ $paymentGateway->mode === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                                    <option value="live" {{ $paymentGateway->mode === 'live' ? 'selected' : '' }}>Live (Production)</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-info-circle"></i> Use Sandbox for testing with fake money.
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="is_active" value="1" {{ $paymentGateway->is_active ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                                    <span class="font-medium text-gray-700">Enable this gateway</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">API Credentials</h2>
                    
                    @if($paymentGateway->name === 'paypal')
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Client ID</label>
                                <input type="text" name="credentials[client_id]" value="{{ $paymentGateway->credentials['client_id'] ?? '' }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Secret Key</label>
                                <input type="password" name="credentials[client_secret]" value="{{ $paymentGateway->credentials['client_secret'] ?? $paymentGateway->credentials['secret'] ?? '' }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">App ID (Optional)</label>
                                <input type="text" name="credentials[app_id]" value="{{ $paymentGateway->credentials['app_id'] ?? '' }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono text-sm">
                            </div>
                        </div>
                    @elseif($paymentGateway->name === 'stripe')
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Publishable Key</label>
                                <input type="text" name="credentials[publishable_key]" value="{{ $paymentGateway->credentials['publishable_key'] ?? $paymentGateway->credentials['public_key'] ?? '' }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Secret Key</label>
                                <input type="password" name="credentials[secret_key]" value="{{ $paymentGateway->credentials['secret_key'] ?? '' }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Webhook Secret (Optional)</label>
                                <input type="password" name="credentials[webhook_secret]" value="{{ $paymentGateway->credentials['webhook_secret'] ?? '' }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono text-sm">
                            </div>
                        </div>
                    @else
                        <!-- Fallback for generic gateways -->
                         <div class="space-y-6">
                            @if(isset($paymentGateway->credentials))
                                @foreach($paymentGateway->credentials as $key => $value)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                    <input type="text" name="credentials[{{ $key }}]" value="{{ $value }}"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none font-mono text-sm">
                                </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500 italic">No specific fields defined for this gateway type.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Info -->
            <div class="space-y-6">
                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Integration Guide</h3>
                    <div class="text-sm text-gray-600 space-y-4">
                        <p>
                            <strong>Sandbox Mode:</strong><br>
                            Use this mode for testing. Payments will be simulated and no real money will be charged. Perfect for verifying the flow.
                        </p>
                        <p>
                            <strong>Live Mode:</strong><br>
                            Only enable this when you are ready to accept real payments. Ensure your API credentials are correct.
                        </p>
                        <div class="p-3 bg-blue-50 text-blue-700 rounded-lg text-xs">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Tip: You can get these keys from your {{ $paymentGateway->title }} Developer Dashboard.
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
