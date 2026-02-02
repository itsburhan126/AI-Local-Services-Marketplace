@extends($layout)

@section('content')
<div class="min-h-screen bg-gray-50 pb-12">
    <!-- Coming Soon Banner -->
    <div class="bg-indigo-900 text-white overflow-hidden relative border-b border-indigo-800">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 relative z-10 text-center flex items-center justify-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-800/80 border border-indigo-500/30 text-indigo-100 text-xs font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-[0_0_10px_rgba(251,191,36,0.5)]"></span>
                Coming Soon
            </span>
            <p class="font-medium text-indigo-100 text-sm">
                We are building a comprehensive <span class="text-white font-bold">24/7 Live Support Center</span> with AI chat. Stay tuned!
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">How can we help you?</h1>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">Search our help center or contact our support team directly. We are here to assist you with any questions or issues.</p>
        </div>

        @if(session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm animate-fade-in-up">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- Contact Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                        <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                                <i class="fas fa-envelope-open-text text-xl"></i>
                            </div>
                            Send us a message
                        </h2>
                    </div>
                    <div class="p-8 lg:p-10">
                        <form action="{{ route('support.store') }}" method="POST" class="space-y-8">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="group">
                                    <label class="block text-base font-bold text-slate-700 mb-3 group-hover:text-indigo-600 transition-colors">Your Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                        </div>
                                        <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all font-medium" placeholder="John Doe">
                                    </div>
                                </div>
                                <div class="group">
                                    <label class="block text-base font-bold text-slate-700 mb-3 group-hover:text-indigo-600 transition-colors">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                        </div>
                                        <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all font-medium" placeholder="john@example.com" required>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-base font-bold text-slate-700 mb-3 group-hover:text-indigo-600 transition-colors">Subject</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                    </div>
                                    <select name="subject" class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all bg-white font-medium appearance-none">
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Technical Support">Technical Support</option>
                                        <option value="Billing Issue">Billing Issue</option>
                                        <option value="Verification Help">Verification Help</option>
                                        <option value="Report a User">Report a User</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-base font-bold text-slate-700 mb-3 group-hover:text-indigo-600 transition-colors">Message</label>
                                <div class="relative">
                                    <div class="absolute top-4 left-4 pointer-events-none">
                                        <i class="fas fa-comment-alt text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                    </div>
                                    <textarea name="message" rows="6" class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all resize-none font-medium" placeholder="Describe your issue in detail..." required></textarea>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-5 px-6 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold text-xl rounded-xl shadow-xl shadow-indigo-500/30 transition-all transform hover:-translate-y-1 hover:shadow-indigo-500/50 flex items-center justify-center gap-3">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Direct Contact -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                        <i class="fas fa-headset text-indigo-600"></i> Other ways to help
                    </h3>
                    
                    <div class="space-y-4">
                        <a href="#" class="flex items-center gap-4 p-5 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all group shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                <i class="fas fa-book-open text-xl"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-slate-900 text-lg group-hover:text-blue-700 transition-colors">Knowledge Base</span>
                                <span class="text-sm text-slate-500">Read guides & FAQs</span>
                            </div>
                            <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="#" class="flex items-center gap-4 p-5 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all group shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp text-2xl"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-slate-900 text-lg group-hover:text-emerald-700 transition-colors">WhatsApp Support</span>
                                <span class="text-sm text-slate-500">Chat with us instantly</span>
                            </div>
                            <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-emerald-500 transform group-hover:translate-x-1 transition-all"></i>
                        </a>
                    </div>
                </div>

                <!-- FAQ Snippet -->
                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-900/30 relative overflow-hidden border border-indigo-500/20">
                    <div class="absolute top-0 right-0 -mr-12 -mt-12 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-40 h-40 bg-indigo-500/30 rounded-full blur-3xl"></div>
                    
                    <h3 class="text-xl font-bold mb-6 relative z-10 flex items-center gap-3">
                        <i class="fas fa-question-circle text-indigo-300"></i> Common Questions
                    </h3>
                    <div class="space-y-6 relative z-10">
                        <div class="group">
                            <p class="font-bold text-indigo-100 text-base mb-2 group-hover:text-white transition-colors">How long does verification take?</p>
                            <p class="text-sm text-indigo-300 leading-relaxed">Typically 24-48 hours after submission. You'll receive an email notification.</p>
                        </div>
                        <div class="w-full h-px bg-white/10"></div>
                        <div class="group">
                            <p class="font-bold text-indigo-100 text-base mb-2 group-hover:text-white transition-colors">How do I withdraw funds?</p>
                            <p class="text-sm text-indigo-300 leading-relaxed">Go to Earnings > Withdraw and choose your preferred payment method.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
