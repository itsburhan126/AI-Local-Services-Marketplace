<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class DashboardBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::truncate();

        $banners = [
            // 1. Hero Banners (Slideshow)
            [
                'title' => 'Find the perfect freelance services for your business',
                'subtitle' => 'Millions of people use Findlancer to turn their ideas into reality.',
                'image' => 'https://placehold.co/1200x400/10b981/ffffff?text=Find+Perfect+Services',
                'type' => 'hero',
                'order' => 1,
                'link' => '/search',
                'button_text' => 'Get Started',
            ],
            [
                'title' => 'Hire the best freelancers for any job, online.',
                'subtitle' => 'Work with the world\'s best talent on Upwork.',
                'image' => 'https://placehold.co/1200x400/3b82f6/ffffff?text=Hire+Best+Freelancers',
                'type' => 'hero',
                'order' => 2,
                'link' => '/categories',
                'button_text' => 'Browse Categories',
            ],
            [
                'title' => 'Scale your business with expert talent',
                'subtitle' => 'Connect with professionals who can help you grow.',
                'image' => 'https://placehold.co/1200x400/6366f1/ffffff?text=Scale+Your+Business',
                'type' => 'hero',
                'order' => 3,
                'link' => '/register',
                'button_text' => 'Join Now',
            ],
            
            // 4. Large Promotional Banner (Middle of page)
            [
                'title' => 'Get 20% Off Your First Order',
                'subtitle' => 'Limited Time Offer for New Users',
                'image' => 'https://placehold.co/1200x300/8b5cf6/ffffff?text=Special+Offer+20%25+OFF',
                'type' => 'promo_large',
                'button_text' => 'Claim Now',
                'link' => '/search?sort=price_asc',
                'order' => 4,
            ],

            // 5. Split Banner Left (Design)
            [
                'title' => 'For Designers',
                'subtitle' => 'Showcase your creative work',
                'image' => 'https://placehold.co/600x300/ec4899/ffffff?text=Designers',
                'type' => 'promo_split',
                'position' => 'left',
                'link' => '/categories/graphics-design',
                'button_text' => 'Explore Design',
                'order' => 5,
            ],

            // 6. Split Banner Right (Development)
            [
                'title' => 'For Developers',
                'subtitle' => 'Build the future',
                'image' => 'https://placehold.co/600x300/f59e0b/ffffff?text=Developers',
                'type' => 'promo_split',
                'position' => 'right',
                'link' => '/categories/programming-tech',
                'button_text' => 'Find Jobs',
                'order' => 6,
            ],

            // 7. Category Spotlight Banner (AI)
            [
                'title' => 'Unleash the Power of AI',
                'subtitle' => 'Discover the latest AI services',
                'image' => 'https://placehold.co/1200x300/14b8a6/ffffff?text=AI+Services+Hub',
                'type' => 'promo_large',
                'link' => '/categories/ai-services',
                'button_text' => 'Explore AI',
                'order' => 7,
            ],

            // 8. Bottom CTA Banner
            [
                'title' => 'Become a Seller Today',
                'subtitle' => 'Start earning from your passion',
                'image' => 'https://placehold.co/1200x300/f43f5e/ffffff?text=Start+Selling',
                'type' => 'cta_bottom',
                'link' => '/register?type=provider',
                'button_text' => 'Register Now',
                'order' => 8,
            ],

            // 9. Seasonal Banner
            [
                'title' => 'Summer Sale',
                'subtitle' => 'Hot deals on cool services',
                'image' => 'https://placehold.co/1200x300/f97316/ffffff?text=Summer+Sale',
                'type' => 'promo_seasonal',
                'link' => '/search?tag=sale',
                'button_text' => 'Shop Deals',
                'order' => 9,
            ],

            // 10. App Download Banner
            [
                'title' => 'Download Our App',
                'subtitle' => 'Get work done on the go',
                'image' => 'https://placehold.co/1200x300/0ea5e9/ffffff?text=Download+App',
                'type' => 'app_promo',
                'link' => '/app',
                'button_text' => 'Get App',
                'order' => 10,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create(array_merge($banner, ['status' => true]));
        }
    }
}
