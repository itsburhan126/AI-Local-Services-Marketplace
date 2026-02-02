@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Forum Posts</h1>
            <p class="text-slate-500 text-sm mt-1">Manage forum discussions</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Title</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Author</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stats</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-slate-700">{{ $post->title }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ Str::limit($post->content, 50) }}</div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs">
                                        {{ substr($post->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-slate-600">{{ $post->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $post->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-slate-500">
                                <div class="flex gap-3">
                                    <span><i class="fas fa-eye mr-1"></i> {{ $post->view_count }}</span>
                                    <span><i class="fas fa-comment mr-1"></i> {{ $post->replies_count ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <button class="text-slate-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">
                                No posts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($posts->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
