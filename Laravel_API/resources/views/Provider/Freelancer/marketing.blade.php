@extends('layouts.freelancer')

@section('title', 'Growth & Marketing')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Growth & Marketing</h1>
        <p class="text-slate-600 mt-2">Tools to boost your business.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-primary-500 transition-colors cursor-pointer group">
            <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-bullhorn text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Promote Gigs</h3>
            <p class="text-slate-500">Get more visibility by promoting your best gigs.</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-primary-500 transition-colors cursor-pointer group">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Contacts</h3>
            <p class="text-slate-500">Manage your customer relationships and contacts.</p>
        </div>
    </div>
</div>
@endsection
