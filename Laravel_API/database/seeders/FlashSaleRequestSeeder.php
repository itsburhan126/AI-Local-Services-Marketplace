<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FlashSaleRequest;
use App\Models\Service;
use App\Models\User;

class FlashSaleRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some providers
        $providers = User::where('role', 'provider')->take(5)->get();

        if ($providers->isEmpty()) {
            $this->command->info('No providers found. Skipping FlashSaleRequest seeding.');
            return;
        }

        foreach ($providers as $provider) {
            // Get services for this provider
            $services = Service::where('provider_id', $provider->id)->take(3)->get();

            foreach ($services as $service) {
                // Randomly decide if this service has a request
                if (rand(0, 1)) {
                    FlashSaleRequest::create([
                        'service_id' => $service->id,
                        'provider_id' => $provider->id,
                        'proposed_discount' => rand(15, 60), // Random discount between 15% and 60%
                        'status' => 'pending', // Pending status
                    ]);
                }
            }
        }

        $this->command->info('Flash Sale Requests seeded successfully.');
    }
}
