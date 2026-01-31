@extends('layouts.customer')

@section('title', 'Quality Guide')

@section('content')
<div class="bg-white">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4 font-display">Quality Standards</h1>
        <p class="text-xl text-gray-500 max-w-2xl mx-auto">
            Excellence is our benchmark. Follow these guidelines to ensure high-quality outcomes on every project.
        </p>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        @if($guidelines->count() > 0)
            @foreach($guidelines as $index => $guideline)
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-{{ $guideline->color_class }}-100 rounded-xl flex items-center justify-center text-{{ $guideline->color_class }}-600">
                        <i class="{{ $guideline->icon_class }} text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $index + 1 }}. {{ $guideline->title }}</h2>
                </div>
                <div class="prose prose-indigo max-w-none text-gray-600 bg-gray-50 p-8 rounded-2xl border border-gray-100">
                    {!! $guideline->description !!}
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-12">
                <p class="text-gray-500">Quality guidelines are currently being updated.</p>
            </div>
        @endif

    </div>
</div>
@endsection
