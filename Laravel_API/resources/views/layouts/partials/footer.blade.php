    <!-- Footer (Professional) -->
    <footer class="bg-white border-t border-gray-200 pt-16 pb-8 mt-auto">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Section: Links Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-16">
                
                <!-- Column 1: Categories -->
                <div class="col-span-1">
                    <h4 class="font-bold text-gray-900 mb-5 text-base">Categories</h4>
                    <ul class="space-y-3">
                        @foreach($footerCategories as $category)
                            <li>
                                <a href="{{ route('customer.gigs.index', ['category' => $category->slug]) }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 2: For Clients -->
                <div class="col-span-1">
                    <h4 class="font-bold text-gray-900 mb-5 text-base">For Clients</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('how-it-works') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">How Findlancer Works</a></li>
                        <li><a href="{{ route('success-stories') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Customer Success Stories</a></li>
                        <li><a href="{{ route('trust-and-safety') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Trust & Safety</a></li>
                        <li><a href="{{ route('quality-guide') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Quality Guide</a></li>
                        <li><a href="{{ route('guides') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Findlancer Guides</a></li>
                    </ul>
                </div>

                <!-- Column 3: Earning with Us -->
                <div class="col-span-1">
                    <h4 class="font-bold text-gray-900 mb-5 text-base">Earning with Us</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('provider.freelancer.dashboard') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Become a Freelancer</a></li>
                        <li class="flex items-center gap-2">
                            <span class="text-gray-400 text-sm cursor-not-allowed">Become an Agency</span>
                            <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full border border-indigo-100">Coming Soon</span>
                        </li>
                        <li><a href="{{ route('page.show', 'community-hub') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Community Hub</a></li>
                        <li><a href="{{ route('page.show', 'forum') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Forum</a></li>
                        <li><a href="{{ route('page.show', 'events') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Events</a></li>
                    </ul>
                </div>

                <!-- Column 4: Business Solutions -->
                <div class="col-span-1">
                    <h4 class="font-bold text-gray-900 mb-5 text-base">Business Solutions</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2">
                            <span class="text-gray-400 text-sm cursor-not-allowed">Findlancer Pro</span>
                            <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full border border-indigo-100">Coming Soon</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-gray-400 text-sm cursor-not-allowed">Project Management</span>
                            <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full border border-indigo-100">Coming Soon</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-gray-400 text-sm cursor-not-allowed">Talent Sourcing</span>
                            <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full border border-indigo-100">Coming Soon</span>
                        </li>
                    </ul>
                </div>

                <!-- Column 5: Company -->
                <div class="col-span-1">
                    <h4 class="font-bold text-gray-900 mb-5 text-base">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('page.show', 'about-findlancer') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">About Findlancer</a></li>
                        <li><a href="{{ route('page.show', 'help-center') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Help Center</a></li>
                        <li><a href="{{ route('page.show', 'careers') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Careers</a></li>
                        <li><a href="{{ route('page.show', 'terms-of-service') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Terms of Service</a></li>
                        <li><a href="{{ route('page.show', 'privacy-policy') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Privacy Policy</a></li>
                        <li><a href="{{ route('page.show', 'partnerships') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-all duration-300 text-sm block transform hover:translate-x-1">Partnerships</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Section: Brand & Social -->
            <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- Left: Logo & Copyright -->
                <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center text-white font-bold text-xl">{{ substr(\App\Models\Setting::get('app_name', 'Findlancer'), 0, 1) }}</div>
                        <span class="font-bold text-2xl tracking-tight font-display text-gray-900">{{ \App\Models\Setting::get('app_name', 'findlancer') }}</span>
                    </div>
                    <p class="text-gray-400 text-sm">{{ \App\Models\Setting::get('copyright_text', '© ' . date('Y') . ' ' . \App\Models\Setting::get('app_name', 'Findlancer') . ' International Ltd.') }}</p>
                </div>

                <!-- Right: Social Icons & Settings -->
                <div class="flex items-center gap-6">
                    <!-- Social Icons -->
                    <div class="flex items-center gap-4">
                        @if(\App\Models\Setting::get('instagram_url'))
                        <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-transparent hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-all duration-300">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('linkedin_url'))
                        <a href="{{ \App\Models\Setting::get('linkedin_url') }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-transparent hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-all duration-300">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('facebook_url'))
                        <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-transparent hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-all duration-300">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('twitter_url'))
                        <a href="{{ \App\Models\Setting::get('twitter_url') }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-transparent hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-all duration-300">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('youtube_url'))
                        <a href="{{ \App\Models\Setting::get('youtube_url') }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-transparent hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-all duration-300">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                        @endif
                    </div>

                    <!-- Settings (Language/Currency) -->
                    <div class="hidden md:flex items-center gap-4 ml-4 pl-4 border-l border-gray-200">
                        <button class="flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm font-medium transition-colors">
                            <i class="fas fa-globe"></i>
                            <span>English</span>
                        </button>
                        <button class="flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm font-medium transition-colors">
                            <span>$ USD</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>