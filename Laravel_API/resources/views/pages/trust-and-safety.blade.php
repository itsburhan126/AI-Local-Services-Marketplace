@extends('layouts.customer')

@section('title', 'Trust & Safety')

@section('content')
<div class="bg-white">
    <!-- Hero -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <div class="w-20 h-20 bg-emerald-100 rounded-3xl flex items-center justify-center mx-auto mb-8 transform rotate-3">
                <i class="fas fa-shield-alt text-4xl text-emerald-600 transform -rotate-3"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-6 font-display">
                Your Trust is Our Priority
            </h1>
            <p class="text-xl text-gray-500 max-w-3xl mx-auto">
                We've built a secure platform so you can focus on getting work done. Here's how we keep you safe.
            </p>
        </div>
    </div>

    <!-- Features -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid md:grid-cols-3 gap-12">
            
            <!-- Secure Payments -->
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-lock text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Secure Payments</h3>
                <p class="text-gray-600 leading-relaxed">
                    We hold payments in escrow until the work is approved. This ensures freelancers get paid for their work and clients get what they paid for.
                </p>
            </div>

            <!-- Verified Profiles -->
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-user-check text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Verified Profiles</h3>
                <p class="text-gray-600 leading-relaxed">
                    We verify identities and skills to ensure you're working with real professionals. Look for the "Verified" badge on profiles.
                </p>
            </div>

            <!-- 24/7 Support -->
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-headset text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">24/7 Support</h3>
                <p class="text-gray-600 leading-relaxed">
                    Our support team is always here to help. Whether you have a question or an issue, we're just a message away.
                </p>
            </div>

        </div>
    </div>

    <!-- Detailed Sections -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
            
            <!-- Buying Safely -->
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-12 items-center">
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 font-display">Buying Safely</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                            <span class="text-gray-600"><strong>Check Reviews:</strong> Always read reviews from previous clients to gauge the freelancer's quality.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                            <span class="text-gray-600"><strong>Communicate Clearly:</strong> Use our chat system to discuss project details before hiring.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                            <span class="text-gray-600"><strong>Keep it on Platform:</strong> Never pay outside of Findlancer. Paying outside voids our protection.</span>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3 flex justify-center">
                    <img src="https://illustrations.popsy.co/amber/payment-processed.svg" alt="Safe Payment" class="w-64">
                </div>
            </div>

            <!-- Selling Safely -->
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 flex flex-col md:flex-row-reverse gap-12 items-center">
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 font-display">Selling Safely</h2>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-500 mt-1"></i>
                            <span class="text-gray-600"><strong>Set Clear Terms:</strong> Define your scope of work clearly in your Gig description.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-500 mt-1"></i>
                            <span class="text-gray-600"><strong>Don't Start Without an Order:</strong> Wait for an official order notification before starting work.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-indigo-500 mt-1"></i>
                            <span class="text-gray-600"><strong>Report Suspicious Activity:</strong> If a user asks for personal info or outside payment, report them.</span>
                        </li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3 flex justify-center">
                    <img src="https://illustrations.popsy.co/amber/success.svg" alt="Selling Safe" class="w-64">
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
