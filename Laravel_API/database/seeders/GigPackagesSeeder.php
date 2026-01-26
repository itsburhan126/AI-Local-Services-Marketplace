<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gig;
use App\Models\GigPackage;

class GigPackagesSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Seeding Gig Packages...');

        $gigs = Gig::all();

        if ($gigs->isEmpty()) {
            $this->command->warn('No gigs found. Skipping package seeding.');
            return;
        }

        foreach ($gigs as $gig) {
            // Check if gig already has packages
            if ($gig->packages()->count() > 0) {
                continue;
            }

            // Create Basic Package
            GigPackage::create([
                'gig_id' => $gig->id,
                'tier' => 'basic',
                'name' => 'Basic Package',
                'description' => 'Essential services to get you started.',
                'price' => rand(10, 50),
                'delivery_days' => rand(1, 3),
                'revisions' => 1,
                'features' => json_encode(['Basic Support', 'Standard Quality']),
                'source_code' => false
            ]);

            // Create Standard Package
            GigPackage::create([
                'gig_id' => $gig->id,
                'tier' => 'standard',
                'name' => 'Standard Package',
                'description' => 'Most popular choice for average needs.',
                'price' => rand(60, 150),
                'delivery_days' => rand(3, 5),
                'revisions' => 3,
                'features' => json_encode(['Priority Support', 'High Quality', 'Source Files']),
                'source_code' => true
            ]);

            // Create Premium Package
            GigPackage::create([
                'gig_id' => $gig->id,
                'tier' => 'premium',
                'name' => 'Premium Package',
                'description' => 'Full comprehensive service with all bells and whistles.',
                'price' => rand(200, 500),
                'delivery_days' => rand(5, 10),
                'revisions' => 999, // Unlimited
                'features' => json_encode(['VIP Support', 'Premium Quality', 'Source Files', 'Commercial Use']),
                'source_code' => true
            ]);
        }

        $this->command->info('Gig Packages seeded successfully for ' . $gigs->count() . ' gigs.');
    }
}
