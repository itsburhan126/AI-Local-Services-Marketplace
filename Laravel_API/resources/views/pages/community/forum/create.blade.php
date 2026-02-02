@extends('layouts.customer')

@section('title', 'Create New Topic')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Create New Topic</h1>
                <a href="{{ route('community.forum.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Back to Forum</a>
            </div>
            <div class="p-6">
                <form action="{{ route('community.forum.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="community_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="community_category_id" name="community_category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="title" name="title" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="What do you want to discuss?" required>
                    </div>
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea id="content" name="content" rows="8" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Share details, context, and what you're looking for." required></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('community.forum.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Publish Topic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

