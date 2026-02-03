<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard - {{ config('app.name') }}</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --font-sans: 'Instrument Sans', sans-serif;
            
            --color-primary-50: #f0f9ff;
            --color-primary-100: #e0f2fe;
            --color-primary-200: #bae6fd;
            --color-primary-300: #7dd3fc;
            --color-primary-400: #38bdf8;
            --color-primary-500: #0ea5e9;
            --color-primary-600: #0284c7;
            --color-primary-700: #0369a1;
            --color-primary-800: #075985;
            --color-primary-900: #0c4a6e;
        }
    </style>
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
        
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideOut {
            from { transform: translateX(0); }
            to { transform: translateX(100%); }
        }
        .animate-slide-in {
            animation: slideIn 0.5s forwards;
        }
        .animate-slide-out {
            animation: slideOut 0.5s forwards;
        }
    </style>
</head>
<body class="antialiased text-slate-800">

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container space-y-4">
        @if (session('success'))
        <div class="toast bg-white border-l-4 border-green-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] animate-slide-in">
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
        <div class="toast bg-white border-l-4 border-red-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] animate-slide-in">
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

        // Global Toast Function
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Define colors and icons based on type
            let borderColor, iconColor, icon, title;
            if (type === 'success') {
                borderColor = 'border-green-500';
                iconColor = 'text-green-500';
                icon = 'fa-check-circle';
                title = 'Success';
            } else if (type === 'error') {
                borderColor = 'border-red-500';
                iconColor = 'text-red-500';
                icon = 'fa-exclamation-circle';
                title = 'Error';
            } else if (type === 'info') {
                borderColor = 'border-blue-500';
                iconColor = 'text-blue-500';
                icon = 'fa-info-circle';
                title = 'Info';
            }
            
            toast.className = `toast bg-white border-l-4 ${borderColor} shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] animate-slide-in`;
            toast.innerHTML = `
                <div class="${iconColor}">
                    <i class="fas ${icon} text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">${title}</h4>
                    <p class="text-sm text-gray-600">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Auto remove
            setTimeout(() => {
                toast.classList.replace('animate-slide-in', 'animate-slide-out');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 5000);
        };
    </script>
</body>
</html>