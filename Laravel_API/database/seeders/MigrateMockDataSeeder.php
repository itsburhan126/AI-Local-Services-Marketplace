<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gig;
use App\Models\GigPackage;
use App\Models\GigFaq;
use App\Models\FreelancerPortfolio;
use App\Models\Review;
use App\Models\User;
use Faker\Factory as Faker;

class MigrateMockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $gigs = Gig::all();

        foreach ($gigs as $gig) {
            // 1. Populate Packages
            // Check if packages already exist to avoid duplication
            if ($gig->packages()->count() == 0) {
                $basePrice = 100.0; // Default base price

                // Basic Package
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'basic',
                    'name' => 'Basic Package',
                    'price' => $basePrice,
                    'description' => 'Basic starter package for simple tasks.',
                    'delivery_days' => 5,
                    'revisions' => 1,
                    'features' => json_encode(['1 Concept', 'JPG & PNG']),
                ]);

                // Standard Package
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'standard',
                    'name' => 'Standard Package',
                    'price' => round($basePrice * 1.5),
                    'description' => 'Standard service delivery with source files.',
                    'delivery_days' => 3,
                    'revisions' => 2,
                    'features' => json_encode(['2 Concepts', 'Source File', 'High Resolution']),
                ]);

                // Premium Package
                GigPackage::create([
                    'gig_id' => $gig->id,
                    'tier' => 'premium',
                    'name' => 'Premium Package',
                    'price' => round($basePrice * 2.5),
                    'description' => 'Premium VIP support and unlimited revisions.',
                    'delivery_days' => 1,
                    'revisions' => 999, // 999 acts as unlimited
                    'features' => json_encode(['3 Concepts', 'Source File', 'Commercial Use', 'VIP Support']),
                ]);
            }

            // 2. Populate FAQs
            if ($gig->faqs()->count() == 0) {
                $faqs = [
                    [
                        'question' => 'Do you provide source files?',
                        'answer' => 'Yes, I provide source files in the Standard and Premium packages.',
                    ],
                    [
                        'question' => 'What is your turnaround time?',
                        'answer' => 'It depends on the package. Basic is 3 days, Standard is 2 days, and Premium is 1 day.',
                    ],
                    [
                        'question' => 'Can I request a custom order?',
                        'answer' => 'Absolutely! Please contact me before placing an order to discuss your requirements.',
                    ],
                ];

                foreach ($faqs as $faq) {
                    GigFaq::create([
                        'gig_id' => $gig->id,
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                    ]);
                }
            }

            // 3. Populate Reviews (Rating)
            if ($gig->reviews()->count() == 0) {
                // Add some dummy reviews
                $reviewers = User::where('id', '!=', $gig->provider_id)->inRandomOrder()->take(3)->get();
                if ($reviewers->isEmpty()) {
                    // Create dummy user if none exist
                    $reviewers = User::factory(3)->create();
                }

                foreach ($reviewers as $reviewer) {
                    Review::create([
                        'gig_id' => $gig->id,
                        'provider_id' => $gig->provider_id,
                        'customer_id' => $reviewer->id,
                        'rating' => $faker->numberBetween(4, 5),
                        'review' => $faker->sentence(),
                        'is_active' => true,
                    ]);
                }
            }
            
            // 4. Update Description (About Us)
            if (empty($gig->description)) {
                $gig->description = "I am a professional freelancer with over 5 years of experience in this field. I have completed hundreds of projects for clients all over the world. My goal is to provide high-quality work that meets your needs and exceeds your expectations. \n\nI specialize in delivering top-notch results with a focus on attention to detail and customer satisfaction. Whether you need a simple task completed or a complex project managed, I am here to help. \n\nFeel free to contact me if you have any questions or need a custom offer.";
                $gig->save();
            }

            // 5. Populate Provider Portfolio
            $provider = $gig->provider;
            if ($provider && $provider->freelancerPortfolios()->count() == 0) {
                $portfolioImages = [
                    'https://images.unsplash.com/photo-1558655146-d09347e92766?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                ];

                foreach ($portfolioImages as $index => $image) {
                    FreelancerPortfolio::create([
                        'user_id' => $provider->id,
                        'title' => 'Project ' . ($index + 1),
                        'description' => 'A successful project completed for a client.',
                        'image_path' => $image,
                        'link' => '#',
                    ]);
                }
            }
        }
    }
}
