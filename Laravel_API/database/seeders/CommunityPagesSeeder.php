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
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
