<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class FooterPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'title' => 'About Findlancer',
                'slug' => 'about-findlancer',
                'content' => '
<div class="about-section">
    <h2 class="text-3xl font-bold mb-6 text-slate-800">Empowering Local Services with AI</h2>
    <p class="text-lg text-slate-600 mb-8">Findlancer is more than just a marketplace; it\'s a movement to revolutionize how local services are discovered, booked, and delivered. By bridging the gap between skilled professionals and clients through advanced AI technology, we make high-quality services accessible to everyone, everywhere.</p>

    <div class="grid md:grid-cols-2 gap-8 mb-12">
        <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
            <h3 class="text-xl font-bold text-indigo-700 mb-3">Our Mission</h3>
            <p class="text-slate-700">To democratize access to professional services by building the world\'s most trusted, efficient, and intelligent local services marketplace.</p>
        </div>
        <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100">
            <h3 class="text-xl font-bold text-emerald-700 mb-3">Our Vision</h3>
            <p class="text-slate-700">A world where talent meets opportunity instantly, creating economic empowerment for millions of service providers globally.</p>
        </div>
    </div>

    <h3 class="text-2xl font-bold mb-4 text-slate-800">Our Story</h3>
    <p class="text-slate-600 mb-6">Founded in 2024, Findlancer began with a simple observation: finding a reliable plumber, graphic designer, or tutor was still too hard. Traditional directories were outdated, and general marketplaces lacked the specialized tools needed for local service delivery. We set out to change that.</p>
    <p class="text-slate-600 mb-12">Today, we serve thousands of communities, helping professionals build thriving businesses and enabling clients to get more done, faster.</p>

    <h3 class="text-2xl font-bold mb-6 text-slate-800">Why Choose Findlancer?</h3>
    <ul class="space-y-4 mb-8">
        <li class="flex items-start gap-3">
            <span class="text-indigo-600 mt-1"><i class="fas fa-check-circle"></i></span>
            <div>
                <strong class="block text-slate-800">AI-Powered Matching</strong>
                <span class="text-slate-600">Our smart algorithms connect you with the perfect professional based on your specific needs, location, and budget.</span>
            </div>
        </li>
        <li class="flex items-start gap-3">
            <span class="text-indigo-600 mt-1"><i class="fas fa-check-circle"></i></span>
            <div>
                <strong class="block text-slate-800">Verified Professionals</strong>
                <span class="text-slate-600">Every provider undergoes a rigorous vetting process, including identity verification and skill assessments.</span>
            </div>
        </li>
        <li class="flex items-start gap-3">
            <span class="text-indigo-600 mt-1"><i class="fas fa-check-circle"></i></span>
            <div>
                <strong class="block text-slate-800">Secure Transactions</strong>
                <span class="text-slate-600">Your payments are held safely in escrow until the job is completed to your satisfaction.</span>
            </div>
        </li>
    </ul>
</div>',
            ],
            [
                'title' => 'Help Center',
                'slug' => 'help-center',
                'content' => '
<div class="help-center-section">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-slate-800 mb-4">How can we help you today?</h2>
        <p class="text-slate-600">Browse our guides, find answers to common questions, or contact our support team.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-8 mb-16">
        <div class="border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-4">
                <i class="fas fa-user"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">For Clients</h3>
            <ul class="space-y-2 text-slate-600">
                <li><a href="#" class="hover:text-blue-600 hover:underline">How to post a job</a></li>
                <li><a href="#" class="hover:text-blue-600 hover:underline">Hiring a freelancer</a></li>
                <li><a href="#" class="hover:text-blue-600 hover:underline">Payment methods & security</a></li>
                <li><a href="#" class="hover:text-blue-600 hover:underline">Dispute resolution</a></li>
            </ul>
        </div>
        <div class="border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-2xl mb-4">
                <i class="fas fa-briefcase"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">For Freelancers</h3>
            <ul class="space-y-2 text-slate-600">
                <li><a href="#" class="hover:text-emerald-600 hover:underline">Creating a winning profile</a></li>
                <li><a href="#" class="hover:text-emerald-600 hover:underline">Getting paid</a></li>
                <li><a href="#" class="hover:text-emerald-600 hover:underline">Service fees & charges</a></li>
                <li><a href="#" class="hover:text-emerald-600 hover:underline">Community guidelines</a></li>
            </ul>
        </div>
    </div>

    <h3 class="text-2xl font-bold mb-6 text-slate-800">Frequently Asked Questions</h3>
    <div class="space-y-4">
        <details class="group bg-slate-50 p-4 rounded-lg cursor-pointer">
            <summary class="flex justify-between items-center font-medium text-slate-800 list-none">
                <span>Is Findlancer free to use?</span>
                <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
            </summary>
            <p class="text-slate-600 mt-4 group-open:animate-fadeIn">
                Joining Findlancer is free for both clients and freelancers. We charge a small service fee on completed projects to maintain the platform and provide support services.
            </p>
        </details>
        <details class="group bg-slate-50 p-4 rounded-lg cursor-pointer">
            <summary class="flex justify-between items-center font-medium text-slate-800 list-none">
                <span>How do I get paid?</span>
                <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
            </summary>
            <p class="text-slate-600 mt-4 group-open:animate-fadeIn">
                Freelancers can withdraw their earnings via PayPal, Stripe, or direct Bank Transfer. Payments are processed within 3-5 business days after withdrawal request.
            </p>
        </details>
        <details class="group bg-slate-50 p-4 rounded-lg cursor-pointer">
            <summary class="flex justify-between items-center font-medium text-slate-800 list-none">
                <span>What if I\'m not satisfied with the work?</span>
                <span class="transition group-open:rotate-180"><i class="fas fa-chevron-down"></i></span>
            </summary>
            <p class="text-slate-600 mt-4 group-open:animate-fadeIn">
                We offer a satisfaction guarantee. If the work delivered does not meet the agreed-upon requirements, you can request a revision or open a dispute for mediation.
            </p>
        </details>
    </div>

    <div class="mt-12 bg-indigo-600 rounded-xl p-8 text-center text-white">
        <h3 class="text-2xl font-bold mb-2">Still need help?</h3>
        <p class="text-indigo-100 mb-6">Our support team is available 24/7 to assist you.</p>
        <a href="/support" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-bold hover:bg-indigo-50 transition-colors">Contact Support</a>
    </div>
</div>',
            ],
            [
                'title' => 'Careers',
                'slug' => 'careers',
                'content' => '
<div class="careers-section">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h2 class="text-4xl font-bold text-slate-900 mb-4">Join Our Mission</h2>
        <p class="text-xl text-slate-600">We\'re building the future of work. Come help us create economic opportunity for millions of people around the world.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mb-16">
        <div class="text-center p-6">
            <div class="w-16 h-16 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-2xl mb-4">
                <i class="fas fa-rocket"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Impact</h3>
            <p class="text-slate-600">Work on challenges that matter. Your code, design, or strategy will directly impact lives.</p>
        </div>
        <div class="text-center p-6">
            <div class="w-16 h-16 mx-auto bg-pink-100 text-pink-600 rounded-full flex items-center justify-center text-2xl mb-4">
                <i class="fas fa-heart"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Culture</h3>
            <p class="text-slate-600">We value curiosity, empathy, and ownership. We\'re a remote-first diverse team.</p>
        </div>
        <div class="text-center p-6">
            <div class="w-16 h-16 mx-auto bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-2xl mb-4">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Growth</h3>
            <p class="text-slate-600">We invest in your learning. Annual stipends for courses, books, and conferences.</p>
        </div>
    </div>

    <h3 class="text-2xl font-bold mb-8 text-slate-900 border-b border-slate-200 pb-4">Open Positions</h3>
    
    <div class="space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition-colors">
            <div>
                <h4 class="text-lg font-bold text-slate-800">Senior Full Stack Engineer</h4>
                <p class="text-slate-500 text-sm">Remote (Global) • Engineering • Full Time</p>
            </div>
            <a href="#" class="mt-4 md:mt-0 inline-flex items-center text-indigo-600 font-medium hover:text-indigo-700">
                Apply Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition-colors">
            <div>
                <h4 class="text-lg font-bold text-slate-800">Product Designer</h4>
                <p class="text-slate-500 text-sm">Remote (Europe/US) • Design • Full Time</p>
            </div>
            <a href="#" class="mt-4 md:mt-0 inline-flex items-center text-indigo-600 font-medium hover:text-indigo-700">
                Apply Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition-colors">
            <div>
                <h4 class="text-lg font-bold text-slate-800">Customer Success Manager</h4>
                <p class="text-slate-500 text-sm">Remote (Asia/Pacific) • Support • Full Time</p>
            </div>
            <a href="#" class="mt-4 md:mt-0 inline-flex items-center text-indigo-600 font-medium hover:text-indigo-700">
                Apply Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <div class="mt-12 text-center text-slate-600">
        <p>Don\'t see a role that fits? Email us at <a href="mailto:careers@findlancer.com" class="text-indigo-600 hover:underline">careers@findlancer.com</a></p>
    </div>
</div>',
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '
<div class="legal-content prose prose-slate max-w-none">
    <p class="text-sm text-slate-500 mb-8">Last Updated: February 3, 2026</p>

    <h3>1. Acceptance of Terms</h3>
    <p>By accessing or using the Findlancer platform ("Site"), you agree to comply with and be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, you may not access or use the Site.</p>

    <h3>2. User Accounts</h3>
    <p>To use certain features of the Site, you must register for an account. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.</p>
    <ul>
        <li>You are responsible for safeguarding your password.</li>
        <li>You agree not to disclose your password to any third party.</li>
        <li>You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</li>
    </ul>

    <h3>3. Services and Marketplace</h3>
    <p>Findlancer provides a marketplace for Clients to find and engage Freelancers for various services. Findlancer is not a party to any contractual relationship between Client and Freelancer.</p>

    <h3>4. Payments and Fees</h3>
    <p>Clients pay Findlancer for services rendered by Freelancers. Findlancer releases funds to Freelancers subject to these Terms.</p>
    <ul>
        <li><strong>Service Fees:</strong> Findlancer charges fees for the use of the Site.</li>
        <li><strong>Taxes:</strong> Users are responsible for paying any applicable taxes.</li>
    </ul>

    <h3>5. Content and Conduct</h3>
    <p>You are solely responsible for your conduct and any data, text, information, usernames, graphics, images, photos, profiles, audio and video clips, links and other content ("Content") that you submit, post, and display on the Site.</p>

    <h3>6. Termination</h3>
    <p>We may terminate or suspend your account and bar access to the Service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not limited to a breach of the Terms.</p>

    <h3>7. Limitation of Liability</h3>
    <p>In no event shall Findlancer, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.</p>

    <h3>8. Governing Law</h3>
    <p>These Terms shall be governed and construed in accordance with the laws of Delaware, United States, without regard to its conflict of law provisions.</p>
</div>',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '
<div class="legal-content prose prose-slate max-w-none">
    <p class="text-sm text-slate-500 mb-8">Last Updated: February 3, 2026</p>

    <p>At Findlancer, accessible from www.findlancer.com, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by Findlancer and how we use it.</p>

    <h3>1. Information We Collect</h3>
    <p>The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.</p>
    <ul>
        <li><strong>Account Information:</strong> Name, email address, phone number, and password.</li>
        <li><strong>Profile Information:</strong> Skills, education, portfolio, and profile photo.</li>
        <li><strong>Payment Information:</strong> Credit card details, bank account information (processed securely by our payment processors).</li>
        <li><strong>Usage Data:</strong> Information on how the Service is accessed and used.</li>
    </ul>

    <h3>2. How We Use Your Information</h3>
    <p>We use the information we collect in various ways, including to:</p>
    <ul>
        <li>Provide, operate, and maintain our website</li>
        <li>Improve, personalize, and expand our website</li>
        <li>Understand and analyze how you use our website</li>
        <li>Develop new products, services, features, and functionality</li>
        <li>Communicate with you, either directly or through one of our partners</li>
        <li>Send you emails</li>
        <li>Find and prevent fraud</li>
    </ul>

    <h3>3. Data Protection Rights (GDPR)</h3>
    <p>We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:</p>
    <ul>
        <li>The right to access – You have the right to request copies of your personal data.</li>
        <li>The right to rectification – You have the right to request that we correct any information you believe is inaccurate.</li>
        <li>The right to erasure – You have the right to request that we erase your personal data, under certain conditions.</li>
        <li>The right to restrict processing – You have the right to request that we restrict the processing of your personal data, under certain conditions.</li>
        <li>The right to object to processing – You have the right to object to our processing of your personal data, under certain conditions.</li>
    </ul>

    <h3>4. Log Files</h3>
    <p>Findlancer follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services\' analytics.</p>

    <h3>5. Children\'s Information</h3>
    <p>Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity. Findlancer does not knowingly collect any Personal Identifiable Information from children under the age of 13.</p>
</div>',
            ],
            [
                'title' => 'Partnerships',
                'slug' => 'partnerships',
                'content' => '
<div class="partnerships-section">
    <!-- Coming Soon Banner -->
    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-8 rounded-r-lg shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-amber-500 text-xl mt-0.5"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-amber-800">Coming Soon</h3>
                <div class="mt-2 text-sm text-amber-700">
                    <p>Our partnership programs are currently being finalized. We are excited to launch these opportunities very soon! Please check back later for application details.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-indigo-900 to-slate-900 rounded-2xl p-12 text-center mb-12">
        <h2 class="text-4xl font-bold mb-4" style="color: #ffffff !important;">Grow with Findlancer</h2>
        <p class="text-xl max-w-2xl mx-auto" style="color: #e0e7ff !important;">Join our ecosystem of partners and help us shape the future of local services while growing your own business.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-8 mb-16">
        <div class="p-8 border border-slate-200 rounded-xl hover:border-indigo-500 transition-colors">
            <div class="text-indigo-600 text-3xl mb-4"><i class="fas fa-handshake"></i></div>
            <h3 class="text-2xl font-bold text-slate-900 mb-4">Agency Partners</h3>
            <p class="text-slate-600 mb-6">For digital agencies, consulting firms, and service aggregators. Scale your delivery capabilities by tapping into our vetted talent pool.</p>
            <ul class="space-y-2 mb-8 text-slate-700">
                <li><i class="fas fa-check text-emerald-500 mr-2"></i> Dedicated account manager</li>
                <li><i class="fas fa-check text-emerald-500 mr-2"></i> Priority support</li>
                <li><i class="fas fa-check text-emerald-500 mr-2"></i> Bulk hiring tools</li>
            </ul>
            <a href="#" class="block w-full text-center bg-slate-300 text-slate-500 py-3 rounded-lg font-bold cursor-not-allowed">Coming Soon</a>
        </div>

        <div class="p-8 border border-slate-200 rounded-xl hover:border-indigo-500 transition-colors">
            <div class="text-purple-600 text-3xl mb-4"><i class="fas fa-bullhorn"></i></div>
            <h3 class="text-2xl font-bold text-slate-900 mb-4">Affiliate Program</h3>
            <p class="text-slate-600 mb-6">For content creators, influencers, and community leaders. Earn competitive commissions by referring clients and freelancers.</p>
            <ul class="space-y-2 mb-8 text-slate-700">
                <li><i class="fas fa-check text-emerald-500 mr-2"></i> High commission rates</li>
                <li><i class="fas fa-check text-emerald-500 mr-2"></i> 90-day cookie duration</li>
                <li><i class="fas fa-check text-emerald-500 mr-2"></i> Marketing assets provided</li>
            </ul>
            <a href="#" class="block w-full text-center bg-slate-300 text-slate-500 py-3 rounded-lg font-bold cursor-not-allowed">Coming Soon</a>
        </div>
    </div>

    <div class="bg-slate-50 rounded-xl p-8">
        <h3 class="text-2xl font-bold text-slate-900 mb-6 text-center">Strategic Integrations</h3>
        <p class="text-slate-600 text-center max-w-3xl mx-auto mb-8">We partner with leading software platforms to bring seamless service booking to your users. API access available for qualified partners.</p>
        
        <div class="flex flex-wrap justify-center gap-8 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
            <!-- Logos would go here, using text for now -->
            <span class="text-2xl font-bold text-slate-400">Stripe</span>
            <span class="text-2xl font-bold text-slate-400">PayPal</span>
            <span class="text-2xl font-bold text-slate-400">Slack</span>
            <span class="text-2xl font-bold text-slate-400">Zoom</span>
            <span class="text-2xl font-bold text-slate-400">Google Cloud</span>
        </div>
    </div>
</div>',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'is_active' => true,
                ]
            );
        }
    }
}
