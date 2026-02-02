<?php

namespace Database\Seeders;

use App\Models\CommunityCategory;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommunityFullDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Categories
        $categories = [
            [
                'name' => 'General Discussion',
                'slug' => 'general-discussion',
                'description' => 'A place for general chatter, introductions, and off-topic conversations.',
                'icon' => 'fas fa-comments',
                'type' => 'both',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Freelancing Tips',
                'slug' => 'freelancing-tips',
                'description' => 'Share and find tips on how to succeed as a freelancer.',
                'icon' => 'fas fa-lightbulb',
                'type' => 'forum',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Client Relations',
                'slug' => 'client-relations',
                'description' => 'Advice on handling clients, negotiations, and contracts.',
                'icon' => 'fas fa-handshake',
                'type' => 'forum',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Technical Support',
                'slug' => 'technical-support',
                'description' => 'Get help with technical issues, platform bugs, or coding problems.',
                'icon' => 'fas fa-laptop-code',
                'type' => 'forum',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Success Stories',
                'slug' => 'success-stories',
                'description' => 'Share your wins and get inspired by others.',
                'icon' => 'fas fa-trophy',
                'type' => 'both',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Industry News',
                'slug' => 'industry-news',
                'description' => 'Latest news and trends in the gig economy and tech world.',
                'icon' => 'fas fa-newspaper',
                'type' => 'both',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $catData) {
            CommunityCategory::updateOrCreate(['slug' => $catData['slug']], $catData);
        }

        $cats = CommunityCategory::all();
        $users = User::limit(10)->get();

        if ($users->count() < 1) {
            $this->command->info('No users found. Creating a default user for seeding.');
            $users = collect([User::factory()->create()]);
        }

        // 2. Create Forum Posts (20 Professional Posts)
        $postsData = [
            // General Discussion
            ['title' => 'Networking Etiquette: How to build genuine professional relationships', 'cat' => 'general-discussion'],
            ['title' => 'The Future of the Gig Economy: Trends to watch in 2026', 'cat' => 'general-discussion'],
            ['title' => 'Work-Life Balance: Strategies for avoiding burnout', 'cat' => 'general-discussion'],
            ['title' => 'Best productivity tools for remote teams in 2026', 'cat' => 'general-discussion'],

            // Freelancing Tips
            ['title' => 'Mastering the Art of the Proposal: Convert leads into clients', 'cat' => 'freelancing-tips'],
            ['title' => 'Pricing Strategies: Hourly rates vs. Fixed-price projects', 'cat' => 'freelancing-tips'],
            ['title' => 'Building a Personal Brand: Why it matters more than ever', 'cat' => 'freelancing-tips'],
            ['title' => 'Managing irregular income: Financial planning for freelancers', 'cat' => 'freelancing-tips'],
            ['title' => 'Contract Essentials: Protecting yourself legally', 'cat' => 'freelancing-tips'],

            // Client Relations
            ['title' => 'Effective Communication: Managing client expectations from day one', 'cat' => 'client-relations'],
            ['title' => 'Dealing with Scope Creep: How to say no professionally', 'cat' => 'client-relations'],
            ['title' => 'Onboarding Checklist: Setting the stage for project success', 'cat' => 'client-relations'],
            ['title' => 'Turning one-off clients into long-term retainers', 'cat' => 'client-relations'],

            // Technical Support
            ['title' => 'Optimizing your profile for search visibility', 'cat' => 'technical-support'],
            ['title' => 'Troubleshooting common file upload issues', 'cat' => 'technical-support'],
            ['title' => 'Understanding the new payment withdrawal options', 'cat' => 'technical-support'],

            // Success Stories
            ['title' => 'Case Study: How I scaled my design agency to $10k/month', 'cat' => 'success-stories'],
            ['title' => 'From Part-time Side Hustle to Full-time Career: My Journey', 'cat' => 'success-stories'],
            ['title' => 'Landing my first Enterprise Client: Lessons learned', 'cat' => 'success-stories'],
            ['title' => 'Milestone reached: Completing 500 orders with a 5-star rating', 'cat' => 'success-stories'],
        ];

        foreach ($postsData as $index => $pData) {
            $category = $cats->where('slug', $pData['cat'])->first();
            $user = $users->random();
            
            $post = ForumPost::create([
                'user_id' => $user->id,
                'community_category_id' => $category->id,
                'title' => $pData['title'],
                'slug' => Str::slug($pData['title']) . '-' . uniqid(),
                'content' => "<p>Hey everyone,</p><p>I wanted to start a discussion about <strong>{$pData['title']}</strong>.</p><p>This is a topic that I think is really relevant to many of us here. I've been thinking about this a lot lately and would love to hear your thoughts and experiences.</p><p>Feel free to share your insights below!</p><p>Best regards,<br>{$user->name}</p>",
                'is_pinned' => $index < 2, // Pin first 2
                'is_locked' => false,
                'view_count' => rand(50, 5000),
                'like_count' => rand(5, 200),
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
            ]);

            // Add 3-8 replies per post
            $numReplies = rand(3, 8);
            for ($i = 0; $i < $numReplies; $i++) {
                ForumReply::create([
                    'forum_post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'content' => "Great post! I really agree with your points about {$pData['title']}. It's definitely something to consider.",
                    'is_accepted_answer' => ($i === 1 && rand(0, 1)), // Randomly mark as accepted
                    'created_at' => Carbon::parse($post->created_at)->addHours(rand(1, 48)),
                ]);
            }
        }

        // 3. Create Events (20+ Events)
        $eventsData = [
            ['title' => 'Freelancer Networking Mixer', 'type' => 'online'],
            ['title' => 'Web Development Workshop 2024', 'type' => 'online'],
            ['title' => 'SEO Masterclass for Beginners', 'type' => 'online'],
            ['title' => 'Graphic Design Trends 2025', 'type' => 'online'],
            ['title' => 'Copywriting Secrets Webinar', 'type' => 'online'],
            ['title' => 'Local Business Meetup: NYC', 'type' => 'offline'],
            ['title' => 'Startup Pitch Night', 'type' => 'offline'],
            ['title' => 'Digital Nomad Meetup: Bali', 'type' => 'offline'],
            ['title' => 'AI in Creative Industries Panel', 'type' => 'online'],
            ['title' => 'Tax & Legal Q&A for Freelancers', 'type' => 'online'],
            ['title' => 'Portfolio Review Session', 'type' => 'online'],
            ['title' => 'Client Negotiation Roleplay', 'type' => 'online'],
            ['title' => 'Social Media Marketing Bootcamp', 'type' => 'online'],
            ['title' => 'Video Editing 101', 'type' => 'online'],
            ['title' => 'Python for Data Science Workshop', 'type' => 'online'],
            ['title' => 'Mobile App Dev Crash Course', 'type' => 'online'],
            ['title' => 'UX/UI Design Sprint', 'type' => 'online'],
            ['title' => 'Productivity Tools Showcase', 'type' => 'online'],
            ['title' => 'Mental Health for Remote Workers', 'type' => 'online'],
            ['title' => 'Annual Community Summit', 'type' => 'online'],
            ['title' => 'London Tech Week Meetup', 'type' => 'offline'],
            ['title' => 'San Francisco Founder Coffee', 'type' => 'offline'],
        ];

        foreach ($eventsData as $index => $eData) {
            $user = $users->random();
            $category = $cats->random();
            $isOnline = $eData['type'] === 'online';
            $startDate = Carbon::now()->addDays(rand(-30, 90)); // Mix of past and future
            
            $event = Event::create([
                'user_id' => $user->id,
                'community_category_id' => $category->id,
                'title' => $eData['title'],
                'slug' => Str::slug($eData['title']) . '-' . uniqid(),
                'description' => "<p>Join us for the <strong>{$eData['title']}</strong>!</p><p>This event will cover everything you need to know. Whether you are a beginner or a pro, you'll find value in this session.</p><h3>Agenda:</h3><ul><li>Introduction</li><li>Keynote Speech</li><li>Q&A Session</li><li>Networking</li></ul><p>Don't miss out!</p>",
                'start_date' => $startDate,
                'end_date' => (clone $startDate)->addHours(2),
                'location' => $isOnline ? 'Zoom / Google Meet' : 'City Conference Center, Main Hall',
                'is_online' => $isOnline,
                'meeting_link' => $isOnline ? 'https://meet.google.com/abc-defg-hij' : null,
                'image' => null, // Or a placeholder URL
                'max_attendees' => rand(20, 500),
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);

            // Add attendees
            $numAttendees = rand(5, 20);
            for ($i = 0; $i < $numAttendees; $i++) {
                EventAttendee::firstOrCreate([
                    'event_id' => $event->id,
                    'user_id' => $users->random()->id,
                ], [
                    'status' => 'going',
                    'created_at' => Carbon::now()->subDays(rand(0, 10)),
                ]);
            }
        }
    }
}
