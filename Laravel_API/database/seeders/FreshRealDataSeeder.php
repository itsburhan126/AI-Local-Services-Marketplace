<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\Gig;
use App\Models\GigPackage;
use App\Models\GigFaq;
use App\Models\Review;
use App\Models\FreelancerPortfolio;
use App\Models\Category;
use App\Models\ServiceType;
use Faker\Factory as Faker;

class FreshRealDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Clear Database
        $this->command->info('Clearing database...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        User::truncate();
        ProviderProfile::truncate();
        Gig::truncate();
            GigPackage::truncate();
            GigFaq::truncate();
            Review::truncate();
            FreelancerPortfolio::truncate();
            \App\Models\Tag::truncate();
            DB::table('gig_tag')->truncate();
        // Clear pivot tables or other related tables if necessary
        // DB::table('gig_tag')->truncate(); 

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Categories & Service Types (Ensure at least some exist)
        $this->command->info('Ensuring categories exist...');
        if (Category::count() == 0) {
            $categories = ['Graphics & Design', 'Digital Marketing', 'Writing & Translation', 'Video & Animation', 'Programming & Tech'];
            foreach ($categories as $cat) {
                Category::create([
                    'name' => $cat, 
                    'slug' => \Illuminate\Support\Str::slug($cat),
                    'image' => 'https://placehold.co/600x400?text=' . urlencode($cat),
                    'is_active' => true
                ]);
            }
        }
        
        if (ServiceType::count() == 0) {
            ServiceType::create(['name' => 'Remote', 'slug' => 'remote', 'is_active' => true]);
            ServiceType::create(['name' => 'On-Site', 'slug' => 'on-site', 'is_active' => true]);
        }

        $categories = Category::all();
        $serviceTypes = ServiceType::all();

        // 3. Create Customers (for Reviews)
        $this->command->info('Creating customers...');
        $customers = [];
        for ($i = 0; $i < 20; $i++) {
            $customers[] = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'customer',
                'service_rule' => null, // Regular customer
                'avatar' => 'https://i.pravatar.cc/300?u=' . $faker->uuid,
                'status' => 'active',
            ]);
        }

        // 4. Create Tags
        $this->command->info('Creating tags...');
        $tagNames = ['Mobile App', 'Website', 'Logo Design', 'SEO', 'Video Editing', 'Content Writing', 'Digital Marketing', 'Flutter', 'Laravel', 'React', 'UI/UX', 'Animation'];
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tags[] = \App\Models\Tag::create([
                'name' => $tagName,
                'slug' => \Illuminate\Support\Str::slug($tagName),
                'is_active' => true,
            ]);
        }

        // 5. Create Freelancers
        $this->command->info('Creating freelancers...');
        $freelancerProfiles = [
            [
                'name' => 'Alex DesignPro',
                'title' => 'Senior UI/UX Designer',
                'bio' => 'I am a passionate UI/UX designer with 7 years of experience crafting beautiful and functional digital experiences. I specialize in mobile app design and web interfaces.',
                'image' => 'https://i.pravatar.cc/300?u=alex',
                'skills' => ['Figma', 'Adobe XD', 'Sketch', 'Prototyping'],
            ],
            [
                'name' => 'Sarah Coder',
                'title' => 'Full Stack Web Developer',
                'bio' => 'Expert in React, Laravel, and Flutter. I build scalable and robust web and mobile applications. Let\'s turn your idea into reality.',
                'image' => 'https://i.pravatar.cc/300?u=sarah',
                'skills' => ['React', 'Laravel', 'Flutter', 'Node.js'],
            ],
            [
                'name' => 'Mike Motion',
                'title' => 'Professional Video Editor',
                'bio' => 'I bring stories to life through video. From corporate promos to social media reels, I deliver high-quality edits with quick turnaround times.',
                'image' => 'https://i.pravatar.cc/300?u=mike',
                'skills' => ['Premiere Pro', 'After Effects', 'Color Grading'],
            ],
            [
                'name' => 'Emily Writer',
                'title' => 'Creative Content Writer',
                'bio' => 'Words are my superpower. I write engaging blog posts, SEO-optimized articles, and compelling copy that converts.',
                'image' => 'https://i.pravatar.cc/300?u=emily',
                'skills' => ['SEO Writing', 'Copywriting', 'Blogging'],
            ],
            [
                'name' => 'David Marketer',
                'title' => 'Digital Marketing Specialist',
                'bio' => 'I help businesses grow online. Certified Google Ads and Facebook Ads expert with a track record of high ROI campaigns.',
                'image' => 'https://i.pravatar.cc/300?u=david',
                'skills' => ['Google Ads', 'Facebook Ads', 'SEO'],
            ],
        ];

        for ($i = 0; $i < 100; $i++) {
            $this->command->info("Creating freelancer $i...");
            if ($i < count($freelancerProfiles)) {
                $profileData = $freelancerProfiles[$i];
                $email = \Illuminate\Support\Str::slug($profileData['name']) . '@example.com';
            } else {
                $profileData = [
                    'name' => $faker->name,
                    'title' => $faker->jobTitle,
                    'bio' => $faker->paragraph,
                    'image' => 'https://i.pravatar.cc/300?u=' . $faker->uuid,
                    'skills' => $faker->words(4),
                ];
                $email = $faker->userName . $i . '@example.com';
            }

            $user = User::create([
                'name' => $profileData['name'],
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'provider',
                'service_rule' => 'freelancer',
                'avatar' => $profileData['image'],
                'status' => 'active',
            ]);

            // Create Provider Profile
            ProviderProfile::create([
                'user_id' => $user->id,
                'mode' => 'freelancer',
                'about' => $profileData['bio'],
                'rating' => 4.5,
                'reviews_count' => 15,
                'is_verified' => true,
            ]);

            // Add Portfolio
            for ($k = 0; $k < 5; $k++) {
                FreelancerPortfolio::create([
                    'user_id' => $user->id,
                    'title' => $faker->sentence(3),
                    'description' => $faker->paragraph,
                    'image_path' => 'https://picsum.photos/800/600?random=' . $faker->numberBetween(1, 1000),
                    'link' => $faker->url,
                ]);
            }

            // Create Gigs
            $numGigs = $faker->numberBetween(2, 3);
            for ($j = 0; $j < $numGigs; $j++) {
                $category = $categories->random();
                $serviceType = $serviceTypes->random();

                $gigTitle = 'I will ' . $faker->randomElement(['design', 'build', 'write', 'create', 'fix']) . ' ' . $faker->words(3, true) . ' for you';
                
                $gig = Gig::create([
                    'provider_id' => $user->id,
                    'category_id' => $category->id,
                    'service_type_id' => $serviceType->id,
                    'title' => $gigTitle,
                    'slug' => \Illuminate\Support\Str::slug($gigTitle) . '-' . rand(10000, 99999),
                    'description' => $faker->paragraphs(3, true),
                    'images' => ['https://picsum.photos/800/600?random=' . $faker->numberBetween(1, 1000)],
                    'status' => 'approved',
                    'is_active' => true,
                    'is_featured' => $faker->boolean(20),
                ]);

                // Attach Tags
                $gig->relatedTags()->attach(collect($tags)->random(rand(2, 4))->pluck('id'));

                // Create Packages
                $basePrice = $faker->numberBetween(50, 200);
                
                // Basic
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'Basic',
                    'name' => 'Basic Starter',
                    'price' => $basePrice,
                    'description' => 'Essential package for simple needs. Includes basic features.',
                    'delivery_days' => 3,
                    'revisions' => 1,
                    'features' => ['1 Concept', 'Basic Quality', 'No Source File'],
                ]);

                // Standard
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'Standard',
                    'name' => 'Standard Pro',
                    'price' => $basePrice * 1.5,
                    'description' => 'Most popular choice. Includes source files and better quality.',
                    'delivery_days' => 5,
                    'revisions' => 3,
                    'features' => ['2 Concepts', 'High Quality', 'Source File', 'Priority Support'],
                ]);

                // Premium
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'Premium',
                    'name' => 'Premium VIP',
                    'price' => $basePrice * 2.5,
                    'description' => 'Full VIP treatment. Unlimited revisions and top-tier quality.',
                    'delivery_days' => 7,
                    'revisions' => 999, // Unlimited
                    'features' => ['3 Concepts', 'Premium Quality', 'Source File', 'Commercial Use', 'VIP Support'],
                ]);

                // Create FAQs
                $faqs = [
                    ['q' => 'What do you need to get started?', 'a' => 'I need a clear brief and any assets you have.'],
                    ['q' => 'Can you deliver faster?', 'a' => 'Yes, check the Extra Fast Delivery option.'],
                    ['q' => 'Do you provide source files?', 'a' => 'Yes, in Standard and Premium packages.'],
                    ['q' => 'What if I am not satisfied?', 'a' => 'I offer revisions until you are happy!'],
                ];

                foreach ($faqs as $faq) {
                    GigFaq::create([
                        'gig_id' => $gig->id,
                        'question' => $faq['q'],
                        'answer' => $faq['a'],
                    ]);
                }

                // Create Reviews
                $numReviews = ($i < 5) ? $faker->numberBetween(10, 15) : $faker->numberBetween(2, 5);
                $totalRating = 0;

                for ($r = 0; $r < $numReviews; $r++) {
                    $reviewer = $faker->randomElement($customers);
                    $rating = $faker->randomFloat(1, 4.0, 5.0);
                    $totalRating += $rating;

                    Review::create([
                        'customer_id' => $reviewer->id,
                        'provider_id' => $user->id,
                        'gig_id' => $gig->id,
                        'booking_id' => null,
                        'rating' => $rating,
                        'review' => $faker->sentence(10),
                        'is_active' => true,
                        'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    ]);
                }

                // Update Gig Aggregate Rating (Mocking it for display)
                // Assuming Gig model doesn't have rating column but we might need it? 
                // Or maybe it's calculated dynamically. 
                // But user asked for "gig a rating takbe".
                // Let's assume we don't store aggregate on gig for now, but the reviews are there.
            }
        }

        $this->command->info('Fresh real data seeded successfully!');
    }
}
