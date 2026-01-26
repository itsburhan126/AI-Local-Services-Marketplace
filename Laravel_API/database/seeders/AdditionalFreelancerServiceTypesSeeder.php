<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use Illuminate\Support\Str;

class AdditionalFreelancerServiceTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            // Tech & Programming
            'E-commerce Development',
            'Mobile App Design',
            'Game Development',
            '3D Modeling',
            'Database Administration',
            'Server Administration',
            'Cybersecurity Support',
            'Blockchain Development',
            'Smart Contracts',
            'NFT Development',
            'Scripting & Automation',
            'QA & Review',
            'User Testing',
            'Website Maintenance',
            'Chatbot Development',
            
            // Design & Creative
            'Logo Design',
            'Illustration',
            'Vector Tracing',
            'Visual Effects',
            'Intro & Outro Videos',
            'Spokespersons Videos',
            'Unboxing Videos',
            'Music Production',
            'Audio Ads Production',
            'Podcast Editing',
            'Songwriting',
            
            // Writing & Translation
            'Copywriting',
            'Resume Writing',
            'Cover Letters',
            'Technical Writing',
            'Speechwriting',
            'Book Editing',
            'Beta Reading',
            'Translation',
            'Transcription',
            'Proofreading',
            
            // Business & Marketing
            'Virtual Assistant',
            'Data Entry',
            'Market Research',
            'Project Management',
            'Business Consulting',
            'Legal Consulting',
            'Financial Consulting',
            'Life Coaching',
            'Fitness Training',
            'Social Media Management',
            'Influencer Marketing',
            'Community Management',
            'App Marketing',
            'Music Promotion',
            'Email Marketing'
        ];

        $this->command->info('Seeding 40+ Additional Freelancer Service Types...');

        foreach ($types as $type) {
            ServiceType::firstOrCreate(
                ['slug' => Str::slug($type)],
                [
                    'name' => $type,
                    'is_active' => true,
                    'type' => 'freelancer'
                ]
            );
        }

        $this->command->info('Additional Freelancer Service Types seeded successfully.');
    }
}
