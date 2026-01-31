@extends('layouts.customer')

@section('title', $guide->title)

@section('content')
<div class="bg-white min-h-screen pb-20">
    <!-- Hero Section -->
    <div class="relative bg-indigo-900 py-20 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-indigo-800 opacity-50 blur-3xl"></div>
            <div class="absolute top-1/2 right-0 w-64 h-64 rounded-full bg-emerald-600 opacity-20 blur-2xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-indigo-800 text-indigo-200 text-sm font-bold mb-4 border border-indigo-700">
                {{ $guide->category }}
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-6 font-display leading-tight">
                {{ $guide->title }}
            </h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto">
                {{ $guide->excerpt }}
            </p>
            <div class="mt-8 flex items-center justify-center gap-2 text-indigo-300 text-sm">
                <i class="far fa-calendar-alt"></i>
                <span>Updated {{ $guide->updated_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10">
        @if($guide->image_path)
        <div class="rounded-2xl shadow-xl overflow-hidden mb-12 border-4 border-white">
            <img src="{{ asset('storage/' . $guide->image_path) }}" alt="{{ $guide->title }}" class="w-full h-auto object-cover max-h-[500px]">
        </div>
        @endif

        <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-slate-100 prose prose-lg prose-indigo max-w-none">
            {!! nl2br(e($guide->content)) !!}
        </div>

        <!-- Navigation -->
        <div class="mt-12 flex justify-between items-center border-t border-gray-200 pt-8">
            <a href="{{ route('guides') }}" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 font-bold transition-colors">
                <i class="fas fa-arrow-left"></i> Back to Guides
            </a>
        </div>
    </div>
</div>
@endsection
