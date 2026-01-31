<?php

namespace Database\Seeders;

use App\Models\HowItWorksStep;
use Illuminate\Database\Seeder;

class HowItWorksSeeder extends Seeder
{
    public function run(): void
    {
        // Client Steps
        $clientSteps = [
            [
                'title' => 'Find a Service',
                'description' => 'Browse through thousands of services or search for exactly what you need. Filter by price, rating, and more.',
                'icon' => 'fas fa-search',
                'step_order' => 1,
            ],
            [
                'title' => 'Hire a Pro',
                'description' => 'Check reviews, compare packages, and chat with freelancers. Hire the best match for your project.',
                'icon' => 'fas fa-handshake',
                'step_order' => 2,
            ],
            [
                'title' => 'Work Together',
                'description' => 'Communicate clearly, share files, and track progress directly through our secure platform.',
                'icon' => 'fas fa-tasks',
                'step_order' => 3,
            ],
            [
                'title' => 'Approve & Pay',
                'description' => 'Review the final work. Payment is only released to the freelancer once you approve the delivery.',
                'icon' => 'fas fa-check-circle',
                'step_order' => 4,
            ],
        ];

        foreach ($clientSteps as $step) {
            HowItWorksStep::create(array_merge($step, ['type' => 'client']));
        }

        // Freelancer Steps
        $freelancerSteps = [
            [
                'title' => 'Create a Gig',
                'description' => 'Sign up for free, set up your Gig, and offer your work to our global audience.',
                'icon' => 'fas fa-box-open',
                'step_order' => 1,
            ],
            [
                'title' => 'Deliver Great Work',
                'description' => 'Get notified when you get an order and use our system to discuss details with customers.',
                'icon' => 'fas fa-paper-plane',
                'step_order' => 2,
            ],
            [
                'title' => 'Get Paid',
                'description' => 'Get paid on time, every time. Payment is transferred to you upon order completion.',
                'icon' => 'fas fa-wallet',
                'step_order' => 3,
            ],
        ];

        foreach ($freelancerSteps as $step) {
            HowItWorksStep::create(array_merge($step, ['type' => 'freelancer']));
        }
    }
}
