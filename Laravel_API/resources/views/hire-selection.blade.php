<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hire a Pro - {{ config('app.name', 'AI Local Services Marketplace') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        
        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'sans-serif'],
                        },
                        colors: {
                            primary: '#4F46E5', // Indigo 600
                            secondary: '#10B981', // Emerald 500
                        }
                    }
                }
            }
        </script>
        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen">
        <div class="max-w-5xl w-full px-6 py-12">
            
            <!-- Back Link -->
            <a href="{{ url('/') }}" class="inline-flex items-center text-gray-500 hover:text-emerald-600 mb-12 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>

            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900 tracking-tight">
                    What kind of service do you need?
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Select the type of professional you want to hire.
                </p>
            </div>

            <!-- Selection Cards -->
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                
                <!-- Option 1: Freelancer -->
                <a href="{{ route('customer.register') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-emerald-200 p-8 flex flex-col items-center text-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 w-full">
                        <div class="w-20 h-20 bg-emerald-100 rounded-2xl mx-auto flex items-center justify-center mb-6 text-emerald-600 group-hover:scale-110 transition-transform duration-300 transform rotate-3 group-hover:rotate-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-3 text-gray-900">Hire a Freelancer</h2>
                        <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                            Find experts for digital projects like Design, Development, Writing, Marketing, etc.
                        </p>
                        <ul class="text-left text-gray-500 text-sm space-y-2 mb-8 mx-auto max-w-xs">
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Remote talent</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Secure payments</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Quality work</li>
                        </ul>
                        <span class="w-full inline-block px-6 py-3 border border-emerald-600 text-emerald-600 font-medium rounded-xl hover:bg-emerald-600 hover:text-white transition-colors">
                            Find Freelancers
                        </span>
                    </div>
                </a>

                <!-- Option 2: Local Service Provider -->
                <a href="{{ route('customer.register') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 p-8 flex flex-col items-center text-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 w-full">
                        <div class="w-20 h-20 bg-blue-100 rounded-2xl mx-auto flex items-center justify-center mb-6 text-blue-600 group-hover:scale-110 transition-transform duration-300 transform -rotate-3 group-hover:-rotate-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-3 text-gray-900">Hire Local Pro</h2>
                        <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                            Find local professionals for services like Plumbing, Cleaning, Electrician, Moving, etc.
                        </p>
                        <ul class="text-left text-gray-500 text-sm space-y-2 mb-8 mx-auto max-w-xs">
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Verified Pros</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Local experts</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Trusted reviews</li>
                        </ul>
                        <span class="w-full inline-block px-6 py-3 border border-blue-600 text-blue-600 font-medium rounded-xl hover:bg-blue-600 hover:text-white transition-colors">
                            Find Local Pros
                        </span>
                    </div>
                </a>

            </div>
            
            <div class="mt-16 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} AI Local Services Marketplace. All rights reserved.
            </div>
        </div>
    </body>
</html>