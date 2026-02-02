<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Findlancer') | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Macondo&family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Alpine.js Init -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carousel', () => ({
                scrollContainer: null,
                init() {
                    this.scrollContainer = this.$refs.list;
                    if (this.scrollContainer) {
                        this.scrollContainer.classList.add('cursor-grab');
                        this.scrollContainer.style.scrollBehavior = 'smooth';
                    }
                },
                scrollLeft() {
                    if (this.scrollContainer) {
                        this.scrollContainer.scrollBy({ left: -340, behavior: 'smooth' });
                    }
                },
                scrollRight() {
                    if (this.scrollContainer) {
                        this.scrollContainer.scrollBy({ left: 340, behavior: 'smooth' });
                    }
                },
                isDown: false,
                startX: 0,
                scrollPos: 0,
                start(e) {
                    if (!this.scrollContainer) return;
                    this.isDown = true;
                    this.startX = e.pageX - this.scrollContainer.offsetLeft;
                    this.scrollPos = this.scrollContainer.scrollLeft;
                    this.scrollContainer.classList.add('cursor-grabbing');
                    this.scrollContainer.classList.remove('cursor-grab');
                    this.scrollContainer.style.scrollBehavior = 'auto';
                },
                stop() {
                    if (!this.scrollContainer) return;
                    this.isDown = false;
                    this.scrollContainer.classList.remove('cursor-grabbing');
                    this.scrollContainer.classList.add('cursor-grab');
                    this.scrollContainer.style.scrollBehavior = 'smooth';
                },
                move(e) {
                    if (!this.isDown || !this.scrollContainer) return;
                    e.preventDefault();
                    const x = e.pageX - this.scrollContainer.offsetLeft;
                    const walk = (x - this.startX) * 1.5;
                    this.scrollContainer.scrollLeft = this.scrollPos - walk;
                }
            }));
        });
    </script>

    <style>
        :root {
            --font-sans: 'Montserrat', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-display: 'Montserrat', sans-serif;
            
            --color-primary-50: #ecfdf5;
            --color-primary-100: #d1fae5;
            --color-primary-500: #10b981;
            --color-primary-600: #059669;
            --color-primary-700: #047857;
            --color-primary-900: #064e3b;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Toast Styles */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full font-body text-slate-600 antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container space-y-4">
        @if (session('success'))
        <div class="toast bg-white border-l-4 border-green-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] transform translate-x-full animate-slide-in">
            <div class="text-green-500">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Success</h4>
                <p class="text-sm text-gray-600">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif
        
        @if (session('error'))
        <div class="toast bg-white border-l-4 border-red-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] transform translate-x-full animate-slide-in">
            <div class="text-red-500">
                <i class="fas fa-exclamation-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Error</h4>
                <p class="text-sm text-gray-600">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif
    </div>

    @include('layouts.partials.header')

    <!-- Main Content -->
    @yield('content')

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>