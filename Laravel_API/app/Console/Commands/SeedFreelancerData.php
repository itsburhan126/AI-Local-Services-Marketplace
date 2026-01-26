<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gig;
use App\Models\GigPackage;
use App\Models\GigFaq;
use App\Models\FreelancerPortfolio;
use App\Models\User;

class SeedFreelancerData extends Command
{
    protected $signature = 'seed:freelancer-data';
    protected $description = 'Seed freelancer gigs with packages, FAQs and portfolios';

    public function handle()
    {
        $gigs = Gig::all();

        foreach ($gigs as $gig) {
            $this->info("Processing Gig: {$gig->title}");
            
            // 1. Add Packages if not exist
            if ($gig->packages()->count() == 0) {
                $basePrice = 50; // Default base price since we don't have one on Gig model
                // Try to guess from gig extras or just random
                $basePrice = rand(30, 100);

                // Basic
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'basic',
                    'name' => 'Basic Package',
                    'price' => round($basePrice),
                    'description' => 'Basic starter package for simple tasks.',
                    'delivery_days' => 5,
                    'revisions' => 1,
                    'features' => ['1 Concept', 'JPG & PNG'],
                ]);

                // Standard
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'standard',
                    'name' => 'Standard Package',
                    'price' => round($basePrice * 1.5),
                    'description' => 'Standard service delivery with source files.',
                    'delivery_days' => 3,
                    'revisions' => 2,
                    'features' => ['2 Concepts', 'Source File', 'High Resolution'],
                ]);

                // Premium
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'premium',
                    'name' => 'Premium Package',
                    'price' => round($basePrice * 2.5),
                    'description' => 'Premium VIP support and unlimited revisions.',
                    'delivery_days' => 1,
                    'revisions' => 999,
                    'features' => ['3 Concepts', 'Source File', 'Commercial Use', 'VIP Support'],
                ]);
                
                $this->info("  - Added Packages");
            }

            // 2. Add FAQs if not exist
            if ($gig->faqs()->count() == 0) {
                $gig->faqs()->createMany([
                    [
                        'question' => 'What information do you need to get started?',
                        'answer' => 'I need a clear description of your requirements, any reference images, and your preferred style.'
                    ],
                    [
                        'question' => 'Can you deliver faster than the stated time?',
                        'answer' => 'Yes, I offer an extra fast delivery option which you can select during the order process.'
                    ],
                    [
                        'question' => 'Do you provide source files?',
                        'answer' => 'Source files are included in the Standard and Premium packages.'
                    ]
                ]);
                $this->info("  - Added FAQs");
            }

            // 3. Add Portfolio for Provider if not exist
            $provider = $gig->provider;
            if ($provider && $provider->freelancerPortfolios()->count() == 0) {
                $provider->freelancerPortfolios()->createMany([
                    [
                        'title' => 'Modern App Design',
                        'description' => 'A clean and modern mobile app design for a fintech startup.',
                        'image_path' => 'https://cdn.dribbble.com/users/1615584/screenshots/15710288/media/6c7a996417537b98d2e8b23023253e96.jpg',
                        'link' => 'https://dribbble.com'
                    ],
                    [
                        'title' => 'E-commerce Website',
                        'description' => 'Full e-commerce platform UI/UX design with dark mode.',
                        'image_path' => 'https://cdn.dribbble.com/users/4859/screenshots/14605995/media/311b0e00f913636b0008595603706c3f.jpg',
                        'link' => 'https://behance.net'
                    ],
                    [
                        'title' => 'Brand Identity',
                        'description' => 'Complete brand identity package including logo and stationery.',
                        'image_path' => 'https://cdn.dribbble.com/users/2551472/screenshots/15684787/media/64993139046c820063229873d6d6788a.jpg',
                        'link' => 'https://instagram.com'
                    ]
                ]);
                $this->info("  - Added Portfolios to Provider {$provider->name}");
            }
            
            // 4. Ensure Provider Profile exists and has 'About'
            if ($provider && !$provider->providerProfile) {
                $provider->providerProfile()->create([
                   'company_name' => $provider->name . ' Studio',
                   'about' => "Hi, I'm {$provider->name}. I am a professional freelancer with over 5 years of experience in this field. I am passionate about creating high-quality work that meets my clients' needs. I look forward to working with you!",
                   'mode' => 'freelancer',
                   'is_verified' => true,
                   'rating' => 4.9,
                   'reviews_count' => rand(10, 500),
                ]);
                $this->info("  - Created Provider Profile");
            } elseif ($provider && $provider->providerProfile && empty($provider->providerProfile->about)) {
                $provider->providerProfile->update([
                    'about' => "Hi, I'm {$provider->name}. I am a professional freelancer with over 5 years of experience in this field. I am passionate about creating high-quality work that meets my clients' needs. I look forward to working with you!"
                ]);
                $this->info("  - Updated Provider Profile About");
            }
        }

        $this->info('Freelancer Data Seeding Completed!');
    }
}
