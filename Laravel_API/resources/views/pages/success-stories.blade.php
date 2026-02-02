@extends('layouts.customer')

@section('title', 'Success Stories')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <span class="text-indigo-600 font-semibold tracking-wide uppercase text-sm mb-2 block">Community Spotlight</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-6 font-display">
                Success Stories
            </h1>
            <p class="text-xl text-gray-500 max-w-3xl mx-auto">
                Inspiring stories from the people who use Findlancer to build their businesses and bring their ideas to life.
            </p>
        </div>
    </div>

    <!-- Stories Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="infiniteScroll()">
        @if($stories->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="stories-grid">
            @include('pages.partials.success-stories-list', ['stories' => $stories])
        </div>

        <!-- Infinite Scroll Sentinel & Loading -->
        <div x-ref="sentinel" class="mt-12 text-center" x-show="hasMore">
            <div x-show="loading" class="inline-flex items-center gap-2 px-4 py-2 text-indigo-600 bg-indigo-50 rounded-full text-sm font-semibold animate-pulse">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading more stories...
            </div>
        </div>

        <script>
            function infiniteScroll() {
                return {
                    page: 1,
                    hasMore: {{ $stories->hasMorePages() ? 'true' : 'false' }},
                    loading: false,
                    init() {
                        const observer = new IntersectionObserver((entries) => {
                            if (entries[0].isIntersecting && this.hasMore && !this.loading) {
                                this.loadMore();
                            }
                        }, { rootMargin: '200px' });
                        
                        observer.observe(this.$refs.sentinel);
                    },
                    loadMore() {
                        this.loading = true;
                        this.page++;
                        
                        fetch(`{{ route('success-stories') }}?page=${this.page}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(response => response.text())
                        .then(html => {
                            if (html.trim() === '') {
                                this.hasMore = false;
                            } else {
                                const temp = document.createElement('div');
                                temp.innerHTML = html;
                                while (temp.firstElementChild) {
                                    document.getElementById('stories-grid').appendChild(temp.firstElementChild);
                                }
                            }
                        })
                        .catch(() => {
                            this.hasMore = false;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                    }
                }
            }
        </script>
        @else
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 mx-auto mb-4">
                <i class="fas fa-book-open text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No Stories Yet</h3>
            <p class="text-gray-500">Check back soon for inspiring stories from our community.</p>
        </div>
        @endif
    </div>
</div>
@endsection
