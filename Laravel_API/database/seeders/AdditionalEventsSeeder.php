<?php

namespace Database\Seeders;

use App\Models\CommunityCategory;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdditionalEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure we have categories and users
        $categories = CommunityCategory::all();
        if ($categories->isEmpty()) {
            $category = CommunityCategory::create([
                'name' => 'General Events',
                'slug' => 'general-events',
                'description' => 'Events for everyone.',
                'icon' => 'fas fa-calendar',
                'type' => 'both',
                'order' => 1,
                'is_active' => true,
            ]);
            $categories = collect([$category]);
        }

        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $events = [
            [
                'title' => 'Freelancer Networking Night',
                'description' => 'Join us for an evening of networking with fellow freelancers. Share tips, find collaboration opportunities, and enjoy some virtual coffee.',
                'is_online' => true,
                'days_offset' => 2, // 2 days from now
            ],
            [
                'title' => 'Web Development Workshop: Laravel Basics',
                'description' => 'A beginner-friendly workshop covering the fundamentals of Laravel framework. Perfect for those starting their backend journey.',
                'is_online' => true,
                'days_offset' => 5,
            ],
            [
                'title' => 'Graphic Design Trends 2026',
                'description' => 'Explore the latest trends in graphic design. From minimalism to 3D typography, we will cover it all.',
                'is_online' => true,
                'days_offset' => 7,
            ],
            [
                'title' => 'Local Business Meetup: New York',
                'description' => 'Connect with local business owners in New York. Discuss strategies for growth and local market penetration.',
                'is_online' => false,
                'location' => 'Tech Hub, 123 Broadway, NY',
                'days_offset' => 10,
            ],
            [
                'title' => 'SEO Masterclass for Content Writers',
                'description' => 'Learn how to optimize your content for search engines. We will discuss keyword research, on-page SEO, and backlinking strategies.',
                'is_online' => true,
                'days_offset' => 12,
            ],
            [
                'title' => 'Digital Marketing Summit',
                'description' => 'A full-day event featuring industry leaders in digital marketing. Topics include social media marketing, PPC, and email automation.',
                'is_online' => true,
                'days_offset' => 15,
            ],
            [
                'title' => 'Photography Walk: Urban Landscapes',
                'description' => 'Join us for a photography walk through the city. Capture the beauty of urban architecture and street life.',
                'is_online' => false,
                'location' => 'Central Park Entrance, NY',
                'days_offset' => 18,
            ],
            [
                'title' => 'React Native for Mobile Development',
                'description' => 'Deep dive into React Native. Learn how to build cross-platform mobile apps using your React skills.',
                'is_online' => true,
                'days_offset' => 20,
            ],
            [
                'title' => 'Freelance Finance 101',
                'description' => 'Manage your finances like a pro. We will cover taxes, invoicing, and saving for retirement as a freelancer.',
                'is_online' => true,
                'days_offset' => 22,
            ],
            [
                'title' => 'UI/UX Design Sprint',
                'description' => 'Participate in a design sprint. Solve a real-world problem and prototype a solution in just 2 hours.',
                'is_online' => true,
                'days_offset' => 25,
            ],
            [
                'title' => 'Copywriting That Sells',
                'description' => 'Learn the art of persuasive writing. Improve your conversion rates with better copy.',
                'is_online' => true,
                'days_offset' => 28,
            ],
            [
                'title' => 'Video Editing with Premiere Pro',
                'description' => 'A hands-on workshop on video editing. Learn the basics of cutting, transitions, and color grading.',
                'is_online' => true,
                'days_offset' => 30,
            ],
            [
                'title' => 'Startup Pitch Night',
                'description' => 'Watch aspiring entrepreneurs pitch their ideas to investors. Network with the startup community.',
                'is_online' => true,
                'days_offset' => 35,
            ],
            [
                'title' => 'Cybersecurity Awareness Webinar',
                'description' => 'Protect yourself and your clients from cyber threats. Learn about phishing, password security, and data protection.',
                'is_online' => true,
                'days_offset' => 40,
            ],
            [
                'title' => 'E-commerce Strategies for 2026',
                'description' => 'Optimize your online store. Discussing dropshipping, inventory management, and customer retention.',
                'is_online' => true,
                'days_offset' => 42,
            ],
            [
                'title' => 'Remote Work Wellness',
                'description' => 'Stay healthy and productive while working from home. Tips on ergonomics, mental health, and routine.',
                'is_online' => true,
                'days_offset' => 45,
            ],
            [
                'title' => 'Blockchain Fundamentals',
                'description' => 'Understand the basics of blockchain technology. Cryptocurrencies, smart contracts, and NFTs explained.',
                'is_online' => true,
                'days_offset' => 50,
            ],
            [
                'title' => 'Podcast Launch Workshop',
                'description' => 'Everything you need to know to start your own podcast. Equipment, hosting, and promotion.',
                'is_online' => true,
                'days_offset' => 55,
            ],
            [
                'title' => 'AI in Creative Industries',
                'description' => 'How Artificial Intelligence is transforming art, music, and writing. Discussion on tools and ethics.',
                'is_online' => true,
                'days_offset' => 60,
            ],
            [
                'title' => 'End of Year Community Party',
                'description' => 'Celebrate a successful year with the community. Music, games, and awards.',
                'is_online' => false,
                'location' => 'Grand Hotel Ballroom',
                'days_offset' => 90,
            ],
        ];

        foreach ($events as $eventData) {
            $startDate = Carbon::now()->addDays($eventData['days_offset']);
            $isOnline = $eventData['is_online'];

            Event::create([
                'user_id' => $user->id,
                'community_category_id' => $categories->random()->id,
                'title' => $eventData['title'],
                'slug' => Str::slug($eventData['title']) . '-' . Str::random(6),
                'description' => $eventData['description'],
                'start_date' => $startDate,
                'end_date' => (clone $startDate)->addHours(2),
                'location' => $isOnline ? 'Zoom / Google Meet' : ($eventData['location'] ?? 'TBA'),
                'is_online' => $isOnline,
                'meeting_link' => $isOnline ? 'https://meet.google.com/abc-defg-hij' : null,
                'image' => null,
                'max_attendees' => rand(50, 200),
                'is_active' => true,
            ]);
        }
    }
}
