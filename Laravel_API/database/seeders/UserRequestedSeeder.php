<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\Service;
use App\Models\Gig;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserRequestedSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Create 20 Categories
        $this->command->info('Creating 20 Categories...');
        $categoryIds = [];
        for ($i = 0; $i < 20; $i++) {
            $name = $faker->unique()->jobTitle . ' ' . $faker->unique()->word;
            $category = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'freelancer', // Defaulting to freelancer
                'image' => 'https://via.placeholder.com/150',
                'description' => $faker->sentence,
                'is_active' => true,
            ]);
            $categoryIds[] = $category->id;
        }

        // 2. Create Service Types (needed for Gigs)
        $this->command->info('Creating Service Types...');
        $serviceTypeIds = [];
        for ($i = 0; $i < 5; $i++) {
             $name = $faker->unique()->word . ' Type';
             $st = ServiceType::firstOrCreate(
                 ['name' => ucfirst($name)],
                 ['slug' => Str::slug($name), 'is_active' => true]
             );
             $serviceTypeIds[] = $st->id;
        }

        // 3. Create 20 Freelancers
        $this->command->info('Creating 20 Freelancers...');
        $providerIds = [];
        for ($i = 0; $i < 20; $i++) {
            $email = $faker->unique()->safeEmail;
            $user = User::create([
                'name' => $faker->name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'provider',
                'status' => 'active',
                'avatar' => 'https://via.placeholder.com/150',
            ]);

            ProviderProfile::create([
                'user_id' => $user->id,
                'mode' => 'freelancer',
                'about' => $faker->paragraph,
                'is_verified' => true,
            ]);
            
            $providerIds[] = $user->id;
        }

        // 4. Create 20 Services (Service Model)
        $this->command->info('Creating 20 Services...');
        for ($i = 0; $i < 20; $i++) {
            Service::create([
                'provider_id' => $faker->randomElement($providerIds),
                'category_id' => $faker->randomElement($categoryIds),
                'name' => $faker->bs,
                'slug' => $faker->slug,
                'description' => $faker->paragraph,
                'price' => $faker->randomFloat(2, 10, 500),
                'duration_minutes' => 60,
                'image' => 'https://via.placeholder.com/300',
                'is_active' => true,
                'type' => 'local_service',
            ]);
        }

        // 5. Create 20 Gigs (Gig Model)
        $this->command->info('Creating 20 Gigs...');
        for ($i = 0; $i < 20; $i++) {
            Gig::create([
                'provider_id' => $faker->randomElement($providerIds),
                'category_id' => $faker->randomElement($categoryIds),
                'service_type_id' => $faker->randomElement($serviceTypeIds),
                'title' => $faker->catchPhrase,
                'slug' => $faker->slug . '-' . uniqid(),
                'description' => $faker->paragraph,
                'images' => ['https://via.placeholder.com/300'],
                'is_active' => true,
                'status' => 'approved',
            ]);
        }
        
        $this->command->info('Done! Created 20 Categories, 20 Freelancers, 20 Services, and 20 Gigs.');
    }
}
