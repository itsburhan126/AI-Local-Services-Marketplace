<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use App\Models\Gig;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class FreelancerSpecificDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Add Freelancer Service Types
        $serviceTypesList = [
            'Hourly Based',
            'Fixed Price Project',
            'Monthly Retainer',
            'Consultation Call',
            'Quick Task',
            'Milestone Based',
            'Subscription Service',
            'Custom Offer',
            'Remote Contract',
            'Dedicated Support'
        ];

        $this->command->info('Adding Freelancer Service Types...');
        $serviceTypeIds = [];
        foreach ($serviceTypesList as $type) {
            $st = ServiceType::firstOrCreate(
                ['name' => $type],
                ['slug' => Str::slug($type), 'is_active' => true]
            );
            $serviceTypeIds[] = $st->id;
        }

        // 2. Add Gigs for these types
        $this->command->info('Adding Gigs for Freelancer Service Types...');
        
        // Get some providers
        $providers = User::where('role', 'provider')->get();
        if ($providers->isEmpty()) {
            $this->command->info('No providers found, creating one...');
            $provider = User::create([
                'name' => 'Demo Provider',
                'email' => 'demo_provider_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'provider',
                'status' => 'active'
            ]);
            $providers = collect([$provider]);
        }

        // Get some categories
        $categories = Category::where('type', 'freelancer')->get();
        if ($categories->isEmpty()) {
             $cat = Category::create([
                'name' => 'General Freelancing',
                'slug' => 'general-freelancing',
                'type' => 'freelancer'
             ]);
             $categories = collect([$cat]);
        }

        for ($i = 0; $i < 30; $i++) {
            Gig::create([
                'provider_id' => $providers->random()->id,
                'category_id' => $categories->random()->id,
                'service_type_id' => $faker->randomElement($serviceTypeIds),
                'title' => $faker->jobTitle . ' - ' . $faker->catchPhrase,
                'slug' => $faker->slug . '-' . uniqid(),
                'description' => $faker->paragraphs(2, true),
                'images' => ['https://via.placeholder.com/600x400'],
                'is_active' => true,
                'status' => 'approved',
                'is_featured' => $faker->boolean(15),
            ]);
        }

        $this->command->info('Done! Added Freelancer Service Types and Gigs.');
    }
}
