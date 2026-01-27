@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Freelancer Delivery Settings</h1>
            <p class="text-slate-500 text-sm mt-1">Configure payment timelines and pending balance rules</p>
        </div>
        <a href="{{ route('admin.freelancers.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 text-sm font-bold transition-all">
            <i class="fas fa-arrow-left"></i> Back to Freelancers
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('admin.freelancers.settings.update') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Payment Delay -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Payment Release Delay (Days)
                        </label>
                        <p class="text-xs text-slate-500 mb-3">
                            Number of days earned funds remain in "Pending Balance" before moving to "Wallet Balance".
                        </p>
                        <div class="relative">
                            <input type="number" name="freelancer_payment_delay_days" 
                                   value="{{ $settings['freelancer_payment_delay_days'] }}"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all pl-12"
                                   min="0" required>
                            <div class="absolute left-4 top-3.5 text-slate-400">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Balance Popup Text -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Pending Balance Explanation
                        </label>
                        <p class="text-xs text-slate-500 mb-3">
                            This text will appear in a popup when freelancers click on their pending balance to explain why funds are waiting.
                        </p>
                        <textarea name="freelancer_pending_balance_popup_text" rows="4"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                                  required>{{ $settings['freelancer_pending_balance_popup_text'] }}</textarea>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-3 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 font-bold transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
