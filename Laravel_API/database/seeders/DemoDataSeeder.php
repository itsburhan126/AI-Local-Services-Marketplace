<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use App\Models\Zone;
use App\Models\SubscriptionPlan;
use App\Models\Coupon;
use App\Models\Promotion;
use App\Models\Banner;
use App\Models\ProviderProfile;
use App\Models\Booking;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Zones
        $zones = [
            ['name' => 'New York', 'coordinates' => '{"lat": 40.7128, "lng": -74.0060}', 'is_active' => true],
            ['name' => 'Los Angeles', 'coordinates' => '{"lat": 34.0522, "lng": -118.2437}', 'is_active' => true],
            ['name' => 'London', 'coordinates' => '{"lat": 51.5074, "lng": -0.1278}', 'is_active' => true],
            ['name' => 'Dhaka', 'coordinates' => '{"lat": 23.8103, "lng": 90.4125}', 'is_active' => true],
        ];

        foreach ($zones as $z) {
            Zone::firstOrCreate(['name' => $z['name']], $z);
        }

        // 2. Categories
        $categories = [
            [
                'name' => 'Home Cleaning',
                'image' => 'https://images.unsplash.com/photo-1581578731117-104f2a41272c?q=80&w=2070&auto=format&fit=crop',
                'commission_rate' => 10.00
            ],
            [
                'name' => 'Plumbing',
                'image' => 'https://images.unsplash.com/photo-1607472586893-edb57bdc0e39?q=80&w=2074&auto=format&fit=crop',
                'commission_rate' => 12.50
            ],
            [
                'name' => 'Electrical',
                'image' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?q=80&w=2069&auto=format&fit=crop',
                'commission_rate' => 15.00
            ],
            [
                'name' => 'Beauty & Spa',
                'image' => 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=2070&auto=format&fit=crop',
                'commission_rate' => 20.00
            ],
            [
                'name' => 'Moving Services',
                'image' => 'https://images.unsplash.com/photo-1600585152220-90363fe7e115?q=80&w=2070&auto=format&fit=crop',
                'commission_rate' => 8.00
            ],
        ];

        foreach ($categories as $c) {
            $c['slug'] = Str::slug($c['name']);
            Category::firstOrCreate(['name' => $c['name']], $c);
        }

        // 3. Subscription Plans
        $plans = [
            [
                'name' => 'Basic Starter',
                'price' => 0.00,
                'duration_days' => 30, // days
                'features' => ['Basic Profile', 'Low Commission', '5 Bids/Month'],
                'is_active' => true
            ],
            [
                'name' => 'Professional',
                'price' => 29.99,
                'duration_days' => 30,
                'features' => ['Verified Badge', 'Priority Support', 'Unlimited Bids', 'Analytics'],
                'is_active' => true
            ],
            [
                'name' => 'Enterprise',
                'price' => 99.99,
                'duration_days' => 365,
                'features' => ['All Features', 'Zero Commission', 'Featured Listing', 'Dedicated Manager'],
                'is_active' => true
            ]
        ];

        foreach ($plans as $p) {
            $p['slug'] = Str::slug($p['name']);
            SubscriptionPlan::firstOrCreate(['name' => $p['name']], $p);
        }

        // 4. Users (Customers)
        User::factory()->count(10)->create(['role' => 'user']);

        // 5. Providers
        $providers = User::factory()->count(10)->create(['role' => 'provider']);
        
        foreach ($providers as $provider) {
            // Create profile
            ProviderProfile::create([
                'user_id' => $provider->id,
                'company_name' => $provider->name . ' Services',
                'about' => 'Professional service provider with years of experience.',
                'is_verified' => rand(0, 1),
                'rating' => rand(35, 50) / 10, // 3.5 to 5.0
            ]);
        }

        // 6. Services
        $servicesData = [
            'Home Cleaning' => [
                ['name' => 'Standard House Cleaning', 'price' => 80.00, 'duration_minutes' => 120, 'description' => 'Complete cleaning of living room, kitchen, and bathroom.'],
                ['name' => 'Deep Cleaning', 'price' => 150.00, 'duration_minutes' => 240, 'description' => 'Intensive cleaning including appliances, windows, and carpets.'],
            ],
            'Plumbing' => [
                ['name' => 'Leak Repair', 'price' => 60.00, 'duration_minutes' => 60, 'description' => 'Fixing minor leaks in pipes or faucets.'],
                ['name' => 'Drain Unblocking', 'price' => 90.00, 'duration_minutes' => 90, 'description' => 'Clearing clogged drains and pipes.'],
            ],
            'Electrical' => [
                ['name' => 'Wiring Inspection', 'price' => 75.00, 'duration_minutes' => 60, 'description' => 'Safety check of home electrical wiring.'],
                ['name' => 'Light Installation', 'price' => 45.00, 'duration_minutes' => 45, 'description' => 'Installation of new light fixtures.'],
            ],
            'Beauty & Spa' => [
                ['name' => 'Manicure & Pedicure', 'price' => 50.00, 'duration_minutes' => 90, 'description' => 'Full nail care service.'],
                ['name' => 'Haircut & Styling', 'price' => 65.00, 'duration_minutes' => 60, 'description' => 'Professional haircut and styling.'],
            ],
            'Moving Services' => [
                ['name' => 'Local Moving (Small)', 'price' => 200.00, 'duration_minutes' => 240, 'description' => 'Moving service for studio or 1-bedroom apartments.'],
            ]
        ];

        foreach ($servicesData as $catName => $services) {
            $category = Category::where('name', $catName)->first();
            if ($category) {
                foreach ($services as $s) {
                    // Assign a random provider
                    $provider = $providers->random();
                    
                    Service::create([
                        'provider_id' => $provider->id,
                        'category_id' => $category->id,
                        'name' => $s['name'],
                        'slug' => Str::slug($s['name'] . '-' . Str::random(5)),
                        'price' => $s['price'],
                        'duration_minutes' => $s['duration_minutes'],
                        'description' => $s['description'],
                        'image' => $category->image, // Use category image for demo
                        'is_active' => true,
                        'location_type' => 'customer'
                    ]);
                }
            }
        }

        // 7. Coupons
        $coupons = [
            ['code' => 'WELCOME20', 'type' => 'percentage', 'value' => 20, 'usage_limit' => 100, 'expiry_date' => Carbon::now()->addMonths(1)],
            ['code' => 'SUMMER50', 'type' => 'fixed', 'value' => 50, 'usage_limit' => 50, 'expiry_date' => Carbon::now()->addMonths(2)],
        ];

        foreach ($coupons as $c) {
            Coupon::firstOrCreate(['code' => $c['code']], $c);
        }

        // 8. Promotions
        Promotion::create([
            'title' => 'Winter Sale',
            'description' => 'Get 20% off on all cleaning services this winter!',
            'image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?q=80&w=2070&auto=format&fit=crop',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(1),
            'is_active' => true,
        ]);

        // 9. Banners
        $banners = [
            [
                'title' => 'Best Home Services',
                'image' => 'https://images.unsplash.com/photo-1581578731117-104f2a8d275d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
                'link' => '/services',
                'order' => 1
            ],
            [
                'title' => 'Expert Cleaners',
                'image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
                'link' => '/category/cleaning',
                'order' => 2
            ]
        ];

        foreach ($banners as $b) {
            Banner::create($b);
        }

        // 10. Bookings (Demo Data for Dashboard)
        $services = Service::all();
        $customers = User::where('role', 'user')->get();
        
        if ($services->count() > 0 && $customers->count() > 0) {
            for ($i = 0; $i < 20; $i++) {
                $service = $services->random();
                $total = $service->price;
                $commission = $total * 0.10; // 10%
                $providerAmount = $total - $commission;

                Booking::create([
                    'user_id' => $customers->random()->id,
                    'provider_id' => $service->provider_id, // Use service provider
                    'service_id' => $service->id,
                    'scheduled_at' => Carbon::now()->subDays(rand(0, 30)),
                    'status' => ['pending', 'confirmed', 'completed', 'cancelled'][rand(0, 3)],
                    'total_amount' => $total,
                    'commission_amount' => $commission,
                    'provider_amount' => $providerAmount,
                    'payment_status' => ['pending', 'paid'][rand(0, 1)],
                    'address' => '123 Demo St, City, Country'
                ]);
            }
        }
        // 11. Withdrawals
        $providers = User::where('role', 'provider')->get();
        if ($providers->count() > 0) {
            foreach($providers as $provider) {
                // Create a pending withdrawal
                if (rand(0, 1)) {
                    \App\Models\Withdrawal::create([
                        'provider_id' => $provider->id,
                        'amount' => rand(50, 500),
                        'method' => 'bank_transfer',
                        'account_details' => ['bank' => 'Demo Bank', 'account' => '1234567890'],
                        'status' => 'pending',
                    ]);
                }
            }
        }
    }
}
