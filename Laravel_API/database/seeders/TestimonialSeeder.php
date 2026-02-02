<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing testimonials to prevent duplicates
        Testimonial::truncate();

        Testimonial::create([
            'name' => 'Sarah Jenkins',
            'role' => 'Small Business Owner',
            'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
            'text' => 'Found an amazing graphic designer in minutes. The quality of work was outstanding! I highly recommend this platform to anyone looking for professional services.',
            'rating' => 5,
            'is_active' => true,
            'order' => 1
        ]);

        Testimonial::create([
            'name' => 'Michael Chen',
            'role' => 'Tech Startup Founder',
            'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
            'text' => 'This platform made it so easy to find local developers for our MVP. The vetting process really shows in the quality of talent available here.',
            'rating' => 5,
            'is_active' => true,
            'order' => 2
        ]);

        Testimonial::create([
            'name' => 'Jessica Williams',
            'role' => 'Marketing Director',
            'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
            'text' => 'The best place to find reliable freelancers. Trust and Safety features gave me peace of mind. Will definitely be using this for all our future projects.',
            'rating' => 4,
            'is_active' => true,
            'order' => 3
        ]);

        Testimonial::create([
            'name' => 'David Thompson',
            'role' => 'E-commerce Manager',
            'image' => 'https://randomuser.me/api/portraits/men/85.jpg',
            'text' => 'Incredible experience! The freelancer I hired went above and beyond. The communication tools made collaboration seamless.',
            'rating' => 5,
            'is_active' => true,
            'order' => 4
        ]);

        Testimonial::create([
            'name' => 'Emily Rodriguez',
            'role' => 'Creative Director',
            'image' => 'https://randomuser.me/api/portraits/women/29.jpg',
            'text' => 'The variety of talent on this platform is unmatched. We found a voiceover artist for our campaign within hours. Truly a game-changer for creative agencies.',
            'rating' => 5,
            'is_active' => true,
            'order' => 5
        ]);

        Testimonial::create([
            'name' => 'James Wilson',
            'role' => 'Real Estate Agent',
            'image' => 'https://randomuser.me/api/portraits/men/46.jpg',
            'text' => 'I needed a photographer for a property listing urgently. Findlancer connected me with a pro who delivered stunning photos the same day. 5 stars!',
            'rating' => 5,
            'is_active' => true,
            'order' => 6
        ]);
    }
}
