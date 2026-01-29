@extends('layouts.freelancer')

@section('title', 'Add Payout Method')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <div class="flex items-center gap-3">
            @if($payoutMethod->logo)
                <img src="{{ asset('storage/'.$payoutMethod->logo) }}" alt="{{ $payoutMethod->name }}" class="w-10 h-10 rounded-md object-cover">
            @else
                <div class="w-10 h-10 rounded-md bg-slate-100 flex items-center justify-center text-slate-500">
                    <i class="fas fa-wallet"></i>
                </div>
            @endif
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Add {{ $payoutMethod->name }}</h1>
                <p class="text-slate-500 mt-1 text-sm">Provide the required details to set up this method.</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-sm border border-slate-200 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)]">
        <form action="{{ route('provider.freelancer.payout.store', $payoutMethod->id) }}" method="POST" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="border border-slate-200 rounded-lg p-6">
                        <h3 class="text-base font-bold text-slate-800 mb-4">Method details</h3>
                        <div class="space-y-4">
                            @if(is_array($payoutMethod->fields) && count($payoutMethod->fields) > 0)
                                @foreach($payoutMethod->fields as $field)
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">{{ $field['label'] ?? ucfirst($field['name']) }}</label>
                                        @php $type = $field['type'] ?? 'text'; @endphp
                                        @if($type === 'textarea')
                                            <textarea name="field_values[{{ $field['name'] }}]" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:border-slate-800 focus:ring-0" required></textarea>
                                        @else
                                            <input type="{{ $type }}" name="field_values[{{ $field['name'] }}]" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm focus:border-slate-800 focus:ring-0" required>
                                        @endif
                                        @error('field_values.'.$field['name'])
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            @else
                                <div class="p-6 bg-slate-50 rounded-lg border border-slate-200">
                                    <p class="text-sm text-slate-600">No additional details required for this method.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="border border-slate-200 rounded-lg p-6">
                        <h3 class="text-base font-bold text-slate-800 mb-4">Summary</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-500">
                            <p>Min: ${{ number_format($payoutMethod->min_amount ?? 0, 2) }}</p>
                            <p>@if($payoutMethod->max_amount) Max: ${{ number_format($payoutMethod->max_amount, 2) }} @endif</p>
                            <p>Processing: {{ $payoutMethod->processing_time_days ? $payoutMethod->processing_time_days.' days' : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('provider.freelancer.payout.index') }}" class="px-4 py-2.5 bg-white border border-slate-300 rounded-[4px] text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="px-4 py-2.5 bg-black text-white rounded-[4px] text-sm font-bold hover:bg-slate-800">Save method</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
