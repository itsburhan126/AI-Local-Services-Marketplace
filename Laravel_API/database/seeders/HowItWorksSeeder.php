<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HowItWorksStep;

class HowItWorksSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        HowItWorksStep::truncate();

        // Client Steps
        $clientSteps = [
            [
                'title' => 'Browse Services',
                'description' => 'Explore our marketplace to find the perfect service for your needs. Filter by category, price, and reviews.',
                'icon' => 'fas fa-search',
            ],
            [
                'title' => 'Choose an Expert',
                'description' => 'Review portfolios, read client feedback, and select a freelancer who matches your project requirements.',
                'icon' => 'fas fa-user-check',
            ],
            [
                'title' => 'Secure Payment',
                'description' => 'Pay securely through our platform. Funds are held in escrow until you are 100% satisfied with the work.',
                'icon' => 'fas fa-shield-alt',
            ],
            [
                'title' => 'Get Work Done',
                'description' => 'Collaborate with your freelancer, track progress, and receive high-quality work on time.',
                'icon' => 'fas fa-check-circle',
            ],
        ];

        foreach ($clientSteps as $index => $step) {
            HowItWorksStep::create([
                'type' => 'client',
                'title' => $step['title'],
                'description' => $step['description'],
                'icon' => $step['icon'],
                'step_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Freelancer Steps
        $freelancerSteps = [
            [
                'title' => 'Create Your Profile',
                'description' => 'Showcase your skills, experience, and portfolio. Highlight what makes you unique to attract clients.',
                'icon' => 'fas fa-id-card',
            ],
            [
                'title' => 'Find Opportunities',
                'description' => 'Search for jobs that match your expertise. Submit proposals and communicate with potential clients.',
                'icon' => 'fas fa-briefcase',
            ],
            [
                'title' => 'Deliver Great Work',
                'description' => 'Complete projects to the client\'s satisfaction. Build your reputation with positive ratings and reviews.',
                'icon' => 'fas fa-star',
            ],
            [
                'title' => 'Get Paid Securely',
                'description' => 'Receive payments safely and on time. Withdraw earnings to your preferred payment method.',
                'icon' => 'fas fa-wallet',
            ],
        ];

        foreach ($freelancerSteps as $index => $step) {
            HowItWorksStep::create([
                'type' => 'freelancer',
                'title' => $step['title'],
                'description' => $step['description'],
                'icon' => $step['icon'],
                'step_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
