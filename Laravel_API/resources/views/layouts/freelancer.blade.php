<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Instrument Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                        'premium': '0 10px 40px -10px rgba(0,0,0,0.08)',
                    },
                    animation: {
                        'slide-in': 'slideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'slide-out': 'slideOut 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': { transform: 'translateX(100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        slideOut: {
                            '0%': { transform: 'translateX(0)', opacity: '1' },
                            '100%': { transform: 'translateX(100%)', opacity: '0' },
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Instrument Sans', sans-serif; background-color: #f8fafc; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .sidebar-gradient {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
        }

        .nav-item {
            position: relative;
            transition: all 0.2s ease;
            border-radius: 8px;
            margin-bottom: 4px;
            color: #64748b;
        }

        .nav-item-active {
            background: #f0f9ff;
            color: #0ea5e9;
            font-weight: 600;
        }
        
        .nav-item:hover:not(.nav-item-active) {
            background: #f8fafc;
            color: #0f172a;
        }

        /* Toast Styles */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
        }
    </style>
</head>
<body class="antialiased text-slate-800">

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

    <div class="min-h-screen bg-slate-50 flex flex-col">
        <!-- Header (Full Width) -->
        @include('partials.freelancer.header')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col relative w-full {{ request()->routeIs('provider.freelancer.chat.*') ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8' }}">
            <!-- Content Body -->
            <main class="flex-1 flex flex-col">
                @yield('content')
            </main>
        </div>
        
        <!-- Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            @include('partials.freelancer.footer')
        </div>
    </div>

    <script>
        // Auto-dismiss toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.replace('animate-slide-in', 'animate-slide-out');
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>