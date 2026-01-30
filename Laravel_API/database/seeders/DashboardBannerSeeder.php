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

        // Hero Banners
        Banner::create([
            'title' => 'Find the perfect freelance services for your business',
            'image' => 'https://placehold.co/1200x400/10b981/ffffff?text=Find+Perfect+Services',
            'type' => 'hero',
            'status' => true,
            'order' => 1,
        ]);

        Banner::create([
            'title' => 'Hire the best freelancers for any job, online.',
            'image' => 'https://placehold.co/1200x400/3b82f6/ffffff?text=Hire+Best+Freelancers',
            'type' => 'hero',
            'status' => true,
            'order' => 2,
        ]);

        // Single Promotional Banner
        Banner::create([
            'title' => 'Get 20% Off Your First Order',
            'subtitle' => 'Limited Time Offer',
            'image' => 'https://placehold.co/1200x300/8b5cf6/ffffff?text=Special+Offer',
            'type' => 'promo_large',
            'button_text' => 'Claim Now',
            'link' => '/search?sort=price_asc',
            'status' => true,
        ]);

        // Split Banners
        Banner::create([
            'title' => 'For Designers',
            'image' => 'https://placehold.co/600x300/ec4899/ffffff?text=Designers',
            'type' => 'promo_split',
            'position' => 'left',
            'link' => '/categories/graphics-design',
            'status' => true,
        ]);

        Banner::create([
            'title' => 'For Developers',
            'image' => 'https://placehold.co/600x300/f59e0b/ffffff?text=Developers',
            'type' => 'promo_split',
            'position' => 'right',
            'link' => '/categories/programming-tech',
            'status' => true,
        ]);
    }
}
