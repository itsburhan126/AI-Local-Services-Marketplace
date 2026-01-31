@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Add Success Story</h1>
            <p class="text-slate-500 text-sm mt-1">Create a new success story</p>
        </div>
        <a href="{{ route('admin.success-stories.index') }}" class="text-slate-500 hover:text-indigo-600 font-bold text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-4xl">
        <form action="{{ route('admin.success-stories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Name</label>
                    <input type="text" name="name" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                    <input type="text" name="role" placeholder="e.g. Founder, Bloom Marketing" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Type</label>
                    <select name="type" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="Business Owner">Business Owner</option>
                        <option value="Freelancer">Freelancer</option>
                        <option value="Startup">Startup</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Service Category</label>
                    <input type="text" name="service_category" placeholder="e.g. Branding" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Quote</label>
                <textarea name="quote" rows="3" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Full Story (Optional)</label>
                <textarea name="story_content" rows="6" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Avatar Image</label>
                    <input type="file" name="avatar" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hero Image</label>
                    <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm font-bold text-slate-700">Active</span>
                </label>
            </div>

            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-2 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold transition-all transform hover:-translate-y-0.5">
                Create Story
            </button>
        </form>
    </div>
</div>
@endsection
