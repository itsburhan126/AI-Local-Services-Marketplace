<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuccessStory;

class SuccessStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stories = [
            [
                'name' => 'Sarah Jenkins',
                'role' => 'Founder, Bloom Marketing',
                'type' => 'Business Owner',
                'quote' => 'Findlancer transformed how we scale. I found an incredible graphic designer within hours who completely rebranded our agency. The quality of talent here is unmatched.',
                'image_path' => null, // Placeholder or seeded image
                'avatar_path' => null,
                'service_category' => 'Branding',
                'is_active' => true,
            ],
            [
                'name' => 'David Miller',
                'role' => 'Full Stack Developer',
                'type' => 'Freelancer',
                'quote' => 'I quit my 9-5 thanks to Findlancer. The steady stream of high-quality clients allowed me to build my own development studio. I\'ve never looked back.',
                'image_path' => null,
                'avatar_path' => null,
                'service_category' => 'Web Dev',
                'is_active' => true,
            ],
            [
                'name' => 'Emily Ross',
                'role' => 'CTO, TechStart',
                'type' => 'Startup',
                'quote' => 'We built our MVP entirely with talent from Findlancer. It was cost-effective, fast, and the code quality was exceptional. We just closed our Series A funding!',
                'image_path' => null,
                'avatar_path' => null,
                'service_category' => 'App Development',
                'is_active' => true,
            ],
        ];

        foreach ($stories as $story) {
            SuccessStory::create($story);
        }
    }
}
