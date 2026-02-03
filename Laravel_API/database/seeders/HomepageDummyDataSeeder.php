<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Gig;
use App\Models\GigPackage;
use App\Models\Banner;
use App\Models\RecentlyViewedGig;
use App\Models\Favorite;
use App\Models\Category;
use App\Models\ProviderProfile;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HomepageDummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure we have a user (Customer)
        $user = User::where('role', 'customer')->first();
        if (!$user) {
            $user = User::first(); // Fallback to any user
        }
        
        if (!$user) {
            $this->command->info('No user found. Creating a dummy customer user.');
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'status' => 'active',
            ]);
        }

        // 2. Ensure we have Categories and Gigs
        $gigs = Gig::all();
        if ($gigs->isEmpty()) {
            $this->command->info('No gigs found. Creating dummy gigs...');
            
            // Create dummy category if needed
            $category = Category::firstOrCreate(
                ['slug' => 'digital-marketing'],
                [
                    'name' => 'Digital Marketing',
                    'image' => 'digital-marketing.jpg',
                    'is_active' => true,
                    'order' => 1
                ]
            );

            // Create dummy provider if needed
            $providerUser = User::where('role', 'provider')->first();
            if (!$providerUser) {
                $providerUser = User::create([
                    'name' => 'Jane Smith',
                    'email' => 'provider@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'provider',
                    'status' => 'active',
                ]);
            }

            // Ensure provider profile exists
            $providerProfile = ProviderProfile::where('user_id', $providerUser->id)->first();
            if (!$providerProfile) {
                $providerProfile = ProviderProfile::create([
                    'user_id' => $providerUser->id,
                    'bio' => 'Expert Digital Marketer',
                    'experience_years' => 5,
                    'is_verified' => true,
                    'mode' => 'freelancer'
                ]);
            }

            // Create Service Type if needed
            $serviceType = ServiceType::firstOrCreate(
                ['slug' => 'seo'],
                [
                    'name' => 'SEO',
                    'description' => 'Search Engine Optimization',
                    'is_active' => true
                ]
            );

            // Create 10 dummy gigs
            for ($i = 1; $i <= 10; $i++) {
                $gig = Gig::create([
                    'provider_id' => $providerUser->id,
                    'category_id' => $category->id,
                    'service_type_id' => $serviceType->id,
                    'title' => "Professional Service $i",
                    'slug' => "professional-service-$i",
                    'description' => "This is a description for service $i",
                    'status' => 'published',
                    'is_active' => true,
                    'thumbnail_image' => 'https://via.placeholder.com/600x400',
                    'view_count' => rand(10, 1000)
                ]);

                // Create Packages for the Gig
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'Basic',
                    'name' => 'Basic Package',
                    'description' => 'Basic features included',
                    'price' => rand(50, 150),
                    'delivery_days' => rand(1, 3),
                    'revisions' => 1,
                    'features' => ['Feature 1', 'Feature 2']
                ]);
            }
            $gigs = Gig::all();
        }

        // 3. Populate Flash Sale Gigs
        $this->command->info('Setting up Flash Sale Gigs...');
        $flashSaleGigs = $gigs->random(min(3, $gigs->count()));
        foreach ($flashSaleGigs as $gig) {
            $gig->update([
                'is_flash_sale' => true,
                'discount_percentage' => rand(10, 50),
                'flash_sale_end_time' => Carbon::now()->addDays(2),
            ]);
        }

        // 4. Populate Recently Viewed and Favorites for ALL users to ensure visibility
        $this->command->info('Populating Recently Viewed and Favorites for all users...');
        
        $users = User::all();
        
        foreach ($users as $currentUser) {
            // Recently Viewed
            RecentlyViewedGig::where('user_id', $currentUser->id)->delete(); // Clear existing
            
            $viewedGigs = $gigs->random(min(5, $gigs->count()));
            foreach ($viewedGigs as $gig) {
                RecentlyViewedGig::create([
                    'user_id' => $currentUser->id,
                    'gig_id' => $gig->id,
                    'updated_at' => Carbon::now()->subMinutes(rand(1, 1000)),
                ]);
            }

            // Favorites
            Favorite::where('user_id', $currentUser->id)->where('favorable_type', Gig::class)->delete();
            
            $savedGigs = $gigs->random(min(3, $gigs->count()));
            foreach ($savedGigs as $gig) {
                Favorite::create([
                    'user_id' => $currentUser->id,
                    'favorable_id' => $gig->id,
                    'favorable_type' => Gig::class,
                ]);
            }
        }

        // 6. Create Banners
        $this->command->info('Creating Banners...');
        
        // Single Large Promo Banner
        Banner::updateOrCreate(
            ['type' => 'promo_large'],
            [
                'title' => 'Unlock Your Potential',
                'subtitle' => 'Get 50% off on your first order',
                'image' => 'https://via.placeholder.com/1200x400?text=Promo+Large',
                'link' => '/gigs',
                'button_text' => 'Shop Now',
                'status' => true,
                'position' => null
            ]
        );

        // Split Left Banner
        Banner::updateOrCreate(
            ['type' => 'promo_split', 'position' => 'left'],
            [
                'title' => 'For Designers',
                'subtitle' => 'Best tools for 2026',
                'image' => 'https://via.placeholder.com/600x400?text=Left+Split',
                'link' => '/category/design',
                'button_text' => 'Explore',
                'status' => true
            ]
        );

        // Split Right Banner
        Banner::updateOrCreate(
            ['type' => 'promo_split', 'position' => 'right'],
            [
                'title' => 'For Developers',
                'subtitle' => 'Top rated services',
                'image' => 'https://via.placeholder.com/600x400?text=Right+Split',
                'link' => '/category/development',
                'button_text' => 'View More',
                'status' => true
            ]
        );

        $this->command->info('Homepage Dummy Data Seeded Successfully!');
    }
}
