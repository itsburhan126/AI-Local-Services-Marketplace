<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\Gig;
use App\Models\Category;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class FreelancerWithGigsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Ensure we have categories and service types
        $categoryIds = Category::where('type', 'freelancer')->pluck('id')->toArray();
        if (empty($categoryIds)) {
            $cat = Category::create([
                'name' => 'General Freelancer',
                'slug' => 'general-freelancer',
                'type' => 'freelancer',
                'image' => 'https://via.placeholder.com/150',
                'is_active' => true,
            ]);
            $categoryIds = [$cat->id];
        }

        $serviceTypeIds = ServiceType::pluck('id')->toArray();
        if (empty($serviceTypeIds)) {
            $st = ServiceType::create(['name' => 'Remote', 'slug' => 'remote', 'is_active' => true]);
            $serviceTypeIds = [$st->id];
        }

        // Create 10 Freelancers, each with 5 Gigs
        $this->command->info('Creating 10 Freelancers each with 5 Gigs...');

        for ($i = 0; $i < 10; $i++) {
            // 1. Create User (Freelancer)
            $email = $faker->unique()->safeEmail;
            $user = User::create([
                'name' => $faker->name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'provider',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150',
            ]);

            // 2. Create Profile
            ProviderProfile::create([
                'user_id' => $user->id,
                'mode' => 'freelancer',
                'about' => $faker->paragraph,
                'is_verified' => true,
            ]);

            // 3. Create 5 Gigs for this User
            for ($j = 0; $j < 5; $j++) {
                Gig::create([
                    'provider_id' => $user->id,
                    'category_id' => $faker->randomElement($categoryIds),
                    'service_type_id' => $faker->randomElement($serviceTypeIds),
                    'title' => $faker->catchPhrase,
                    'slug' => $faker->slug . '-' . uniqid(),
                    'description' => $faker->paragraph,
                    'images' => ['https://via.placeholder.com/300'],
                    'is_active' => true,
                    'status' => 'approved',
                    'is_featured' => $faker->boolean(20),
                ]);
            }
        }

        $this->command->info('Done! Created 10 Freelancers and 50 Gigs (5 per freelancer).');
    }
}
