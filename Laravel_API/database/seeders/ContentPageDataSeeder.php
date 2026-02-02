<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guide;
use App\Models\SuccessStory;
use App\Models\TrustSafetyItem;
use App\Models\QualityGuideline;
use App\Models\HowItWorksStep;
use Faker\Factory as Faker;

class ContentPageDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Guides (25 items)
        $categories = ['Freelancer', 'Client', 'Platform', 'Safety', 'Billing'];
        for ($i = 0; $i < 25; $i++) {
            $title = $faker->sentence(4);
            Guide::create([
                'title' => $title,
                'slug' => \Illuminate\Support\Str::slug($title) . '-' . $i,
                'excerpt' => $faker->paragraph(2),
                'content' => '<h2>' . $faker->sentence(5) . '</h2><p>' . $faker->paragraph(5) . '</p><h3>Key Takeaways</h3><ul><li>' . $faker->sentence() . '</li><li>' . $faker->sentence() . '</li></ul>',
                'image_path' => 'https://picsum.photos/seed/guide' . $i . '/800/600',
                'category' => $faker->randomElement($categories),
                'is_active' => true,
            ]);
        }
        $this->command->info('Seeded 25 Guides.');

        // 2. Success Stories (25 items)
        $roles = ['Founder', 'CEO', 'Marketing Manager', 'Freelancer', 'Developer', 'Designer'];
        $types = ['Business Owner', 'Freelancer', 'Startup', 'Enterprise'];
        $serviceCategories = ['Web Development', 'Graphic Design', 'Digital Marketing', 'Writing', 'Video Editing'];

        for ($i = 0; $i < 25; $i++) {
            SuccessStory::create([
                'name' => $faker->name,
                'role' => $faker->randomElement($roles),
                'type' => $faker->randomElement($types),
                'quote' => $faker->paragraph(1),
                'story_content' => '<p>' . $faker->paragraph(10) . '</p>',
                'image_path' => 'https://picsum.photos/seed/story' . $i . '/800/600',
                'avatar_path' => 'https://i.pravatar.cc/150?u=' . $i,
                'service_category' => $faker->randomElement($serviceCategories),
                'is_active' => true,
            ]);
        }
        $this->command->info('Seeded 25 Success Stories.');

        // 3. Trust & Safety Items (25 items)
        $bgColors = ['emerald-100', 'blue-100', 'purple-100', 'orange-100', 'red-100'];
        $textColors = ['emerald-600', 'blue-600', 'purple-600', 'orange-600', 'red-600'];
        $icons = ['fas fa-shield-alt', 'fas fa-lock', 'fas fa-user-check', 'fas fa-file-contract', 'fas fa-headset'];

        for ($i = 0; $i < 25; $i++) {
            $idx = $i % 5;
            TrustSafetyItem::create([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph(2),
                'icon' => $icons[$idx],
                'bg_color' => $bgColors[$idx],
                'text_color' => $textColors[$idx],
                'is_active' => true,
                'order' => $i,
            ]);
        }
        $this->command->info('Seeded 25 Trust & Safety Items.');

        // 4. Quality Guidelines (25 items)
        $qIcons = ['fas fa-star', 'fas fa-check-circle', 'fas fa-clock', 'fas fa-comment', 'fas fa-palette'];
        $qColors = ['indigo', 'green', 'blue', 'pink', 'yellow'];

        for ($i = 0; $i < 25; $i++) {
            $idx = $i % 5;
            QualityGuideline::create([
                'title' => $faker->words(3, true),
                'description' => '<p>' . $faker->paragraph(3) . '</p>',
                'icon_class' => $qIcons[$idx],
                'color_class' => $qColors[$idx],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }
        $this->command->info('Seeded 25 Quality Guidelines.');

        // 5. How It Works Steps (25 items: 12 Client, 13 Freelancer)
        // Client Steps
        for ($i = 0; $i < 12; $i++) {
            HowItWorksStep::create([
                'type' => 'client',
                'title' => 'Client Step: ' . $faker->sentence(3),
                'description' => $faker->paragraph(2),
                'icon' => 'fas fa-search', // Simplified for now
                'step_order' => $i + 1,
                'is_active' => true,
            ]);
        }
        // Freelancer Steps
        for ($i = 0; $i < 13; $i++) {
            HowItWorksStep::create([
                'type' => 'freelancer',
                'title' => 'Freelancer Step: ' . $faker->sentence(3),
                'description' => $faker->paragraph(2),
                'icon' => 'fas fa-briefcase',
                'step_order' => $i + 1,
                'is_active' => true,
            ]);
        }
        $this->command->info('Seeded 25 How It Works Steps.');
    }
}
