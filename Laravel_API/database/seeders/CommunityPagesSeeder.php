<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommunityPagesSeeder extends Seeder
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
                'title' => 'Community Hub',
                'slug' => 'community-hub',
                'content' => '<h2>Welcome to the Findlancer Community Hub</h2>
<p>Connect with other freelancers and clients, share your experiences, and grow together.</p>
<h3>Latest Updates</h3>
<ul>
    <li>New feature released: Freelancer Dashboard 2.0</li>
    <li>Community meetups scheduled for next month</li>
    <li>Success stories from our top rated freelancers</li>
</ul>
<p>Stay tuned for more updates!</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Findlancer Pro',
                'slug' => 'findlancer-pro',
                'content' => '<h2>Findlancer Pro</h2>
<p>Powerful tools for advanced teams and organizations.</p>
<div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:16px">
  <div style="border:1px solid #e5e7eb;border-radius:12px;padding:16px;background:#ffffff">
    <h3 style="margin:0 0 8px 0;">Project Management</h3>
    <p style="margin:0 0 12px 0;color:#6b7280">Plan, track, and deliver projects with boards, tasks, and timelines.</p>
    <span style="display:inline-block;padding:6px 10px;border-radius:9999px;background:#fef3c7;color:#92400e;font-weight:600;">Coming Soon</span>
  </div>
  <div style="border:1px solid #e5e7eb;border-radius:12px;padding:16px;background:#ffffff">
    <h3 style="margin:0 0 8px 0;">Talent Sourcing</h3>
    <p style="margin:0 0 12px 0;color:#6b7280">Advanced sourcing, shortlisting, and outreach workflows.</p>
    <span style="display:inline-block;padding:6px 10px;border-radius:9999px;background:#fef3c7;color:#92400e;font-weight:600;">Coming Soon</span>
  </div>
</div>
<p style="margin-top:16px">Looking for early access? Contact support to join the beta list.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Forum',
                'slug' => 'forum',
                'content' => '<h2>Findlancer Discussion Forum</h2>
<p>Join the conversation! This is a place to ask questions, share knowledge, and help others.</p>
<h3>Popular Topics</h3>
<ul>
    <li><strong>Getting Started:</strong> Tips for new freelancers</li>
    <li><strong>Client Relations:</strong> How to handle difficult situations</li>
    <li><strong>Skill Development:</strong> Resources for learning new skills</li>
    <li><strong>Industry News:</strong> Discuss the latest trends in the gig economy</li>
</ul>
<p><em>Note: This is a static placeholder for the forum. Full forum functionality coming soon.</em></p>',
                'is_active' => true,
            ],
            [
                'title' => 'Events',
                'slug' => 'events',
                'content' => '<h2>Upcoming Events</h2>
<p>Don\'t miss out on our upcoming webinars, workshops, and meetups.</p>
<h3>This Month</h3>
<ul>
    <li>
        <strong>Freelancing 101 Webinar</strong><br>
        Date: Oct 15, 2023<br>
        Time: 2:00 PM EST
    </li>
    <li>
        <strong>Networking Night</strong><br>
        Date: Oct 28, 2023<br>
        Location: Virtual
    </li>
</ul>
<p>Check back regularly for new events!</p>',
                'is_active' => true,
            ],
            [
                'title' => 'About Findlancer',
                'slug' => 'about-findlancer',
                'content' => '<h2>About Findlancer</h2>
<p>Findlancer is the leading marketplace for local services powered by AI. We connect skilled professionals with clients who need their expertise.</p>
<h3>Our Mission</h3>
<p>To empower freelancers and simplify service discovery for everyone.</p>
<h3>Our Vision</h3>
<p>A world where talent knows no boundaries and services are accessible to all.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Help Center',
                'slug' => 'help-center',
                'content' => '<h2>Help Center</h2>
<p>Welcome to the Findlancer Help Center. How can we assist you today?</p>
<h3>Frequently Asked Questions</h3>
<ul>
    <li><a href="#">How do I create an account?</a></li>
    <li><a href="#">How do I post a gig?</a></li>
    <li><a href="#">How do payments work?</a></li>
</ul>
<p>Can\'t find what you\'re looking for? <a href="/contact">Contact Support</a>.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Careers',
                'slug' => 'careers',
                'content' => '<h2>Careers at Findlancer</h2>
<p>Join our team and help shape the future of work.</p>
<h3>Open Positions</h3>
<ul>
    <li>Senior Laravel Developer</li>
    <li>Product Designer</li>
    <li>Customer Success Manager</li>
</ul>
<p>Send your resume to <a href="mailto:careers@findlancer.com">careers@findlancer.com</a>.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h2>Terms of Service</h2>
<p>Please read these Terms of Service carefully before using our platform.</p>
<h3>1. Acceptance of Terms</h3>
<p>By accessing or using Findlancer, you agree to be bound by these terms.</p>
<h3>2. User Accounts</h3>
<p>You are responsible for maintaining the confidentiality of your account credentials.</p>
<p><em>(This is a placeholder for the full Terms of Service)</em></p>',
                'is_active' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2>
<p>Your privacy is important to us. This policy explains how we collect, use, and protect your data.</p>
<h3>Information We Collect</h3>
<p>We collect information you provide directly to us, such as when you create an account or post a gig.</p>
<h3>How We Use Your Information</h3>
<p>We use your information to provide, maintain, and improve our services.</p>
<p><em>(This is a placeholder for the full Privacy Policy)</em></p>',
                'is_active' => true,
            ],
            [
                'title' => 'Partnerships',
                'slug' => 'partnerships',
                'content' => '<h2>Partnerships</h2>
<p>Interested in partnering with Findlancer? We\'d love to hear from you.</p>
<h3>Why Partner with Us?</h3>
<ul>
    <li>Access to a global network of professionals</li>
    <li>Co-marketing opportunities</li>
    <li>Integration capabilities</li>
</ul>
<p>Contact our partnerships team at <a href="mailto:partners@findlancer.com">partners@findlancer.com</a>.</p>',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
