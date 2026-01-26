<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FreelancerBanner;
use App\Models\FreelancerInterest;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Skill;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class AdditionalUserRequestedSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Add 5 Freelancer Banners
        $this->command->info('Creating 5 Freelancer Banners...');
        for ($i = 0; $i < 5; $i++) {
            FreelancerBanner::create([
                'image_path' => 'https://via.placeholder.com/1200x400',
                'title' => $faker->catchPhrase,
                'is_active' => true,
                'order' => $i,
            ]);
        }

        // 2. Add 20 Freelancer Interests
        $this->command->info('Creating 20 Freelancer Interests...');
        // We need some categories for interests, let's ensure we have some or create a fallback
        $categoryIds = Category::where('type', 'freelancer')->pluck('id')->toArray();
        if (empty($categoryIds)) {
             $cat = Category::create([
                'name' => 'Default Freelancer Cat',
                'slug' => 'default-freelancer-cat',
                'type' => 'freelancer'
             ]);
             $categoryIds = [$cat->id];
        }

        for ($i = 0; $i < 20; $i++) {
            $name = $faker->unique()->words(3, true);
            FreelancerInterest::create([
                'name' => ucfirst($name),
                'slug' => Str::slug($name),
                'icon' => null,
                'category_id' => $faker->randomElement($categoryIds),
                'is_active' => true,
                'order' => $i,
            ]);
        }

        // 3. Add 20 Tags
        $this->command->info('Creating 20 Tags...');
        for ($i = 0; $i < 20; $i++) {
            $name = $faker->unique()->word . ' ' . $faker->numberBetween(1, 1000); // Ensure uniqueness
            Tag::create([
                'name' => ucfirst($name),
                'slug' => Str::slug($name),
                'is_active' => true,
                'source' => 'admin', // Assuming admin added these
            ]);
        }

        // 4. Add 20 Categories
        $this->command->info('Creating 20 Freelancer Categories...');
        for ($i = 0; $i < 20; $i++) {
            $name = $faker->unique()->jobTitle . ' ' . $faker->numberBetween(100, 999);
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'freelancer',
                'image' => 'https://via.placeholder.com/150',
                'description' => $faker->sentence,
                'is_active' => true,
                'order' => $i,
            ]);
        }

        // 5. Add 20 Skills
        $this->command->info('Creating 20 Skills...');
        for ($i = 0; $i < 20; $i++) {
            $name = $faker->unique()->word . ' ' . $faker->jobTitle;
            Skill::create([
                'name' => substr($name, 0, 50), // Truncate to ensure it fits if column is short
                'is_active' => true,
            ]);
        }

        $this->command->info('Done! Added requested Banners, Interests, Tags, Categories, and Skills.');
    }
}
