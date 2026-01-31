@extends('layouts.customer')

@section('title', $page->title)

@section('content')
    <!-- Main Content -->
    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <article class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Title Header -->
            <div class="bg-gradient-to-r from-indigo-50 to-white px-8 py-10 border-b border-gray-100">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    {{ $page->title }}
                </h1>
                <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <i class="far fa-calendar-alt"></i>
                        Last updated: {{ $page->updated_at ? $page->updated_at->format('M d, Y') : now()->format('M d, Y') }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 py-10">
                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    {!! $page->content !!}
                </div>
            </div>
        </article>
    </main>

    <style>
        .prose h1 { font-size: 2.25em; margin-top: 0; margin-bottom: 0.8em; line-height: 1.1111111; font-weight: 800; color: #111827; }
        .prose h2 { font-size: 1.5em; margin-top: 2em; margin-bottom: 1em; line-height: 1.3333333; font-weight: 700; color: #111827; }
        .prose h3 { font-size: 1.25em; margin-top: 1.6em; margin-bottom: 0.6em; line-height: 1.6; font-weight: 600; color: #111827; }
        .prose p { margin-top: 1.25em; margin-bottom: 1.25em; line-height: 1.75; color: #374151; }
        .prose ul { margin-top: 1.25em; margin-bottom: 1.25em; list-style-type: disc; padding-left: 1.625em; }
        .prose li { margin-top: 0.5em; margin-bottom: 0.5em; }
    </style>
@endsection
