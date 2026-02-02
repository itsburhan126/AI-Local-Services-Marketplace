<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core Data
            CountrySeeder::class,
            LanguageSeeder::class,
            SkillSeeder::class,
            
            // App Data
            InterestSeeder::class,
            ServiceRuleSeeder::class,
            
            // Content
            CommunityPagesSeeder::class,
            ContentPageDataSeeder::class,
            CategoryFullDataSeeder::class,
            DashboardBannerSeeder::class,

            // Demo/Mock Data
            FreshRealDataSeeder::class,
        ]);
    }
}
