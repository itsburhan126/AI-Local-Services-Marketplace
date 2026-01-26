<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use Illuminate\Support\Str;

class FreelancerServiceTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'Hourly Based',
            'Fixed Price Project',
            'Monthly Retainer',
            'Consultation Call',
            'Quick Task',
            'Milestone Based',
            'Subscription Service',
            'Custom Offer',
            'Remote Contract',
            'Dedicated Support',
            'Training & Mentorship',
            'Audit & Review',
            'Bug Fixing',
            'Installation & Setup',
            'Maintenance',
            'Creative Design',
            'Content Strategy',
            'SEO Optimization',
            'Performance Tuning',
            'API Integration'
        ];

        $this->command->info('Seeding Freelancer Service Types...');

        foreach ($types as $type) {
            ServiceType::updateOrCreate(
                ['slug' => Str::slug($type)],
                [
                    'name' => $type,
                    'is_active' => true,
                    'type' => 'freelancer'
                ]
            );
        }
        
        $this->command->info('Seeding Local Service Types (for context)...');
        $localTypes = [
            'Plumbing', 'Electrical', 'Cleaning', 'Moving', 'Painting', 'Gardening'
        ];
        
        foreach ($localTypes as $type) {
             ServiceType::updateOrCreate(
                ['slug' => Str::slug($type)],
                [
                    'name' => $type,
                    'is_active' => true,
                    'type' => 'local'
                ]
            );
        }

        $this->command->info('Service Types seeded successfully.');
    }
}
