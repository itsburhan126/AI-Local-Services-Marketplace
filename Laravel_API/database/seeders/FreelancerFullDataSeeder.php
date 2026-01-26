<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\ProviderProfile;
use App\Models\Gig;
use App\Models\GigPackage;
use App\Models\GigExtra;
use App\Models\GigFaq;
use App\Models\Tag;
use App\Models\ServiceType;
use App\Models\FreelancerBanner;
use App\Models\FreelancerInterest;
use Faker\Factory as Faker;

class FreelancerFullDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Image Pools
        $bannerImages = [
            'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1000',
            'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1000',
            'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1000',
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1000',
            'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=1000',
        ];
        
        $gigImages = [
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800',
            'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?w=800',
            'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=800',
            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800',
            'https://images.unsplash.com/photo-1527689368864-3a821dbccc34?w=800',
            'https://images.unsplash.com/photo-1555421689-491a97ff2040?w=800',
        ];

        $userAvatars = [
            'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200',
            'https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=200',
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200',
            'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200',
        ];

        // 1. Service Types (50+)
        $this->command->info('Creating Service Types...');
        $serviceTypeIds = [];
        $baseServiceTypes = ['Remote', 'Online', 'Digital', 'On-Site', 'Hybrid', 'Consultation', 'Subscription', 'One-Time', 'Contract', 'Retainer'];
        
        // Add more random service types to reach 50+
        for ($i = 0; $i < 50; $i++) {
            $name = $i < count($baseServiceTypes) ? $baseServiceTypes[$i] : $faker->unique()->words(2, true);
            $st = ServiceType::firstOrCreate(
                ['name' => ucfirst($name)],
                ['slug' => Str::slug($name), 'is_active' => true]
            );
            $serviceTypeIds[] = $st->id;
        }

        // 2. Categories (20+)
        $this->command->info('Creating Categories...');
        $categoryIds = [];
        $baseCategories = ['Development', 'Design', 'Marketing', 'Writing', 'Video', 'Music', 'Business', 'Lifestyle', 'Data', 'Photography'];
        
        for ($i = 0; $i < 25; $i++) {
            $name = $i < count($baseCategories) ? $baseCategories[$i] : $faker->unique()->jobTitle;
            $cat = Category::updateOrCreate(
                ['name' => ucfirst($name), 'type' => 'freelancer'],
                [
                    'slug' => Str::slug($name),
                    'image' => $faker->randomElement($gigImages),
                    'description' => $faker->sentence
                ]
            );
            $categoryIds[] = $cat->id;
        }

        // 3. Tags (50+)
        $this->command->info('Creating Tags...');
        $tags = [];
        for ($i = 0; $i < 60; $i++) {
            $word = $faker->unique()->word;
            $tag = Tag::firstOrCreate(['name' => $word], ['slug' => Str::slug($word)]);
            $tags[] = $word;
        }

        // 4. Interests (100+)
        $this->command->info('Creating Freelancer Interests...');
        for ($i = 0; $i < 110; $i++) {
            FreelancerInterest::create([
                'name' => $faker->words(3, true),
                'slug' => $faker->slug,
                'category_id' => $faker->randomElement($categoryIds),
                'is_active' => true,
                'order' => $i,
                'icon' => null // Optional
            ]);
        }

        // 5. Freelancers (50+)
        $this->command->info('Creating Freelancers...');
        $providerIds = [];
        // Create one specific demo user
        $demoUser = User::firstOrCreate(
            ['email' => 'freelancer@demo.com'],
            [
                'name' => 'Alex Coder',
                'password' => Hash::make('password'),
                'role' => 'provider',
                'status' => 'active',
                'avatar' => $userAvatars[0],
            ]
        );
        ProviderProfile::updateOrCreate(
            ['user_id' => $demoUser->id],
            ['mode' => 'freelancer', 'about' => 'Expert Full Stack Developer', 'is_verified' => true]
        );
        $providerIds[] = $demoUser->id;

        for ($i = 0; $i < 55; $i++) {
            try {
                $email = $faker->unique()->safeEmail;
                // Ensure email is really unique in DB
                while (User::where('email', $email)->exists()) {
                    $email = $faker->unique()->safeEmail;
                }
                
                $user = User::create([
                    'name' => $faker->name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'provider',
                    'status' => 'active',
                    'avatar' => $faker->randomElement($userAvatars),
                ]);
                ProviderProfile::create([
                    'user_id' => $user->id,
                    'mode' => 'freelancer',
                    'about' => $faker->paragraph,
                    'is_verified' => $faker->boolean(80),
                ]);
                $providerIds[] = $user->id;
            } catch (\Exception $e) {
                $this->command->error("Error creating freelancer: " . $e->getMessage());
            }
        }

        // 6. Gigs (100+)
        $this->command->info('Creating Gigs...');
        for ($i = 0; $i < 120; $i++) {
            try {
                $providerId = $faker->randomElement($providerIds);
                $categoryId = $faker->randomElement($categoryIds);
                $serviceTypeId = $faker->randomElement($serviceTypeIds);
                
                $gig = Gig::create([
                    'provider_id' => $providerId,
                    'category_id' => $categoryId,
                    'service_type_id' => $serviceTypeId,
                    'title' => $faker->sentence(6),
                    'slug' => $faker->slug . '-' . uniqid(),
                    'description' => $faker->paragraphs(3, true),
                    'images' => $faker->randomElements($gigImages, rand(1, 3)),
                    'tags' => $faker->randomElements($tags, rand(3, 6)),
                    'is_active' => true,
                    'status' => 'approved',
                    'is_featured' => $faker->boolean(20),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                ]);

                // Packages (3 per gig)
                $tiers = ['basic', 'standard', 'premium'];
                $prices = [10, 50, 100, 200, 500, 1000];
                
                foreach ($tiers as $index => $tier) {
                    GigPackage::create([
                        'gig_id' => $gig->id,
                        'tier' => $tier,
                        'name' => ucfirst($tier) . ' Package',
                        'description' => $faker->sentence,
                        'price' => $prices[rand(0, 5)] * ($index + 1),
                        'delivery_days' => ($index + 1) * 2,
                        'revisions' => ($index + 1),
                        'features' => $faker->words(4),
                    ]);
                }

                // Extras
                if ($faker->boolean(50)) {
                    GigExtra::create([
                        'gig_id' => $gig->id,
                        'title' => 'Fast Delivery',
                        'description' => 'Deliver in 24 hours',
                        'price' => 50,
                        'duration_type' => 'days',
                        'duration' => 1,
                    ]);
                }

                // FAQs
                for ($k = 0; $k < rand(1, 3); $k++) {
                    GigFaq::create([
                        'gig_id' => $gig->id,
                        'question' => $faker->sentence . '?',
                        'answer' => $faker->paragraph,
                    ]);
                }
            } catch (\Exception $e) {
                $this->command->error("Error creating gig: " . $e->getMessage());
            }
        }

        // 7. Banners (10+)
        $this->command->info('Creating Banners...');
        
        for ($i = 0; $i < 12; $i++) {
            FreelancerBanner::create([
                'title' => $faker->catchPhrase,
                'image_path' => $bannerImages[$i % count($bannerImages)],
                'order' => $i,
                'is_active' => true,
            ]);
        }
    }
}
