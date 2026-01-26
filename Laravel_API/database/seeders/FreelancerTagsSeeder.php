<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Gig;
use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\Category;
use App\Models\ServiceType;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class FreelancerTagsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Create 50 Tags suitable for Freelancers
        $tagsList = [
            'Web Development', 'Graphic Design', 'Content Writing', 'SEO', 'Digital Marketing',
            'Logo Design', 'WordPress', 'React', 'Vue.js', 'Laravel',
            'Python', 'Machine Learning', 'Data Analysis', 'Video Editing', 'Animation',
            'Voice Over', 'Translation', 'Proofreading', 'Copywriting', 'Social Media Management',
            'Email Marketing', 'Lead Generation', 'Virtual Assistant', 'Customer Support', 'Data Entry',
            'Project Management', 'Business Consulting', 'Legal Consulting', 'Accounting', 'Bookkeeping',
            'Tax Preparation', 'Financial Analysis', 'Market Research', 'Product Design', 'UX/UI Design',
            'Mobile App Development', 'iOS Development', 'Android Development', 'Flutter', 'React Native',
            'Cybersecurity', 'Network Administration', 'Cloud Computing', 'DevOps', 'Blockchain',
            'NFT Art', 'Game Development', '3D Modeling', 'Interior Design', 'Architecture'
        ];

        $this->command->info('Creating 50 Freelancer Tags...');
        
        $createdTags = [];
        foreach ($tagsList as $tagName) {
            $tag = Tag::firstOrCreate(
                ['name' => $tagName],
                [
                    'slug' => Str::slug($tagName),
                    'is_active' => true,
                    'created_by' => 1,
                    'source' => 'seeder'
                ]
            );
            $createdTags[] = $tag;
        }

        // Ensure we have 50 tags
        $needed = 50 - count($createdTags);
        for ($i = 0; $i < $needed; $i++) {
             $tagName = $faker->unique()->word . ' ' . $faker->word;
             $tag = Tag::firstOrCreate(
                ['name' => ucfirst($tagName)],
                [
                    'slug' => Str::slug($tagName),
                    'is_active' => true,
                    'created_by' => 1,
                    'source' => 'seeder'
                ]
             );
             $createdTags[] = $tag;
        }

        // 2. Ensure we have Freelancer Providers
        $freelancers = User::where('role', 'provider')
            ->whereHas('providerProfile', function($q) {
                $q->where('mode', 'freelancer');
            })->get();

        if ($freelancers->isEmpty()) {
            $this->command->info('No freelancers found. Creating one...');
            $user = User::create([
                'name' => 'Seeded Freelancer',
                'email' => 'freelancer_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'provider',
                'status' => 'active'
            ]);
            
            ProviderProfile::create([
                'user_id' => $user->id,
                'mode' => 'freelancer',
                'company_name' => 'Freelancer Inc',
                'about' => 'A seeded freelancer profile',
                'is_verified' => true
            ]);
            
            $freelancers = collect([$user]);
        }

        // 3. Ensure Freelancers have Gigs
        $this->command->info('Associating Tags with Freelancer Gigs...');
        
        $freelancerGigs = Gig::whereHas('provider.providerProfile', function($q) {
            $q->where('mode', 'freelancer');
        })->get();

        if ($freelancerGigs->isEmpty()) {
            $this->command->info('No freelancer gigs found. Creating some...');
            
            $category = Category::firstOrCreate(['name' => 'General', 'slug' => 'general', 'type' => 'freelancer']);
            $serviceType = ServiceType::firstOrCreate(['name' => 'Hourly', 'slug' => 'hourly']);

            foreach ($freelancers as $freelancer) {
                for ($k=0; $k<5; $k++) {
                    Gig::create([
                        'provider_id' => $freelancer->id,
                        'category_id' => $category->id,
                        'service_type_id' => $serviceType->id,
                        'title' => 'Freelancer Gig ' . uniqid(),
                        'slug' => 'freelancer-gig-' . uniqid(),
                        'description' => 'A seeded gig description',
                        'is_active' => true,
                        'status' => 'approved'
                    ]);
                }
            }
            // Refresh gigs
            $freelancerGigs = Gig::whereHas('provider.providerProfile', function($q) {
                $q->where('mode', 'freelancer');
            })->get();
        }

        // 4. Attach Tags to Gigs
        foreach ($freelancerGigs as $gig) {
            $randomTags = collect($createdTags)->random(rand(3, 5));
            $tagIds = $randomTags->pluck('id')->toArray();
            $gig->relatedTags()->syncWithoutDetaching($tagIds);
        }

        $this->command->info('Done! Created 50 tags and associated them with freelancer gigs.');
    }
}
