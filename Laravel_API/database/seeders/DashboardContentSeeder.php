<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\TrustSafetyItem;

class DashboardContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Testimonials
        Testimonial::create([
            'name' => 'Sarah Jenkins',
            'role' => 'Small Business Owner',
            'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
            'text' => 'Found an amazing graphic designer in minutes. The quality of work was outstanding!',
            'order' => 1
        ]);

        Testimonial::create([
            'name' => 'Michael Chen',
            'role' => 'Tech Startup Founder',
            'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
            'text' => 'This platform made it so easy to find local developers for our MVP. Highly recommended.',
            'order' => 2
        ]);

        Testimonial::create([
            'name' => 'Jessica Williams',
            'role' => 'Marketing Director',
            'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
            'text' => 'The best place to find reliable freelancers. Trust and Safety features gave me peace of mind.',
            'order' => 3
        ]);

        // Seed Trust & Safety Items
        TrustSafetyItem::create([
            'title' => 'Secure Payments',
            'description' => 'Your money is held safely until you approve the work.',
            'icon' => '🛡️',
            'bg_color' => 'emerald-100',
            'text_color' => 'emerald-600',
            'order' => 1
        ]);

        TrustSafetyItem::create([
            'title' => 'Quality Work',
            'description' => 'Check ratings and reviews to hire the best talent.',
            'icon' => '⭐',
            'bg_color' => 'blue-100',
            'text_color' => 'blue-600',
            'order' => 2
        ]);

        TrustSafetyItem::create([
            'title' => '24/7 Support',
            'description' => 'Our support team is always here to help you.',
            'icon' => '🎧',
            'bg_color' => 'purple-100',
            'text_color' => 'purple-600',
            'order' => 3
        ]);
    }
}
