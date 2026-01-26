<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $services = Service::all();

        if ($users->isEmpty() || $services->isEmpty()) {
            return;
        }

        foreach ($services as $service) {
            // Create 3-8 reviews for each service
            $reviewCount = rand(3, 8);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                $customer = $users->random();
                
                // Ensure customer is not the provider (optional check, but good for realism)
                if ($customer->id === $service->provider_id) {
                    continue;
                }

                Review::create([
                    'service_id' => $service->id,
                    'provider_id' => $service->provider_id,
                    'customer_id' => $customer->id,
                    'rating' => rand(3, 5), // Mostly good ratings
                    'review' => $this->getRandomReviewText(),
                    'is_active' => true,
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }

    private function getRandomReviewText()
    {
        $reviews = [
            "Amazing service! Highly recommended.",
            "Very professional and punctual.",
            "Did a great job, will book again.",
            "Good experience overall, but a bit pricey.",
            "The provider was very polite and efficient.",
            "Excellent work! My home looks brand new.",
            "Quick and easy service. Thank you!",
            "Five stars! exceeded my expectations.",
            "Professional, clean, and fast.",
            "Great communication and fantastic results."
        ];

        return $reviews[array_rand($reviews)];
    }
}
