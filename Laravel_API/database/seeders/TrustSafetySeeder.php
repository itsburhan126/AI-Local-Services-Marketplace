<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrustSafetyItem;

class TrustSafetySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        TrustSafetyItem::truncate();

        // 1. Payment Protection
        TrustSafetyItem::create([
            'title' => 'Secure Payments',
            'description' => 'Your funds are held securely in escrow until the work is completed and approved by you.',
            'icon' => 'fas fa-shield-alt',
            'bg_color' => 'indigo-50',
            'text_color' => 'indigo-600',
            'is_active' => true,
            'order' => 1
        ]);

        // 2. Verified Professionals
        TrustSafetyItem::create([
            'title' => 'Verified Professionals',
            'description' => 'We rigorously vet all freelancers to ensure high-quality service and authentic identities.',
            'icon' => 'fas fa-user-check',
            'bg_color' => 'emerald-50',
            'text_color' => 'emerald-600',
            'is_active' => true,
            'order' => 2
        ]);

        // 3. 24/7 Support
        TrustSafetyItem::create([
            'title' => '24/7 Global Support',
            'description' => 'Our dedicated support team is available around the clock to resolve any issues you encounter.',
            'icon' => 'fas fa-headset',
            'bg_color' => 'blue-50',
            'text_color' => 'blue-600',
            'is_active' => true,
            'order' => 3
        ]);

        // 4. Data Privacy
        TrustSafetyItem::create([
            'title' => 'Data Privacy First',
            'description' => 'Your personal information and transaction details are encrypted and never shared without consent.',
            'icon' => 'fas fa-lock',
            'bg_color' => 'purple-50',
            'text_color' => 'purple-600',
            'is_active' => true,
            'order' => 4
        ]);
    }
}
