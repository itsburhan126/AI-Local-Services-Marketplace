<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceRule;

class ServiceRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $freelancerRules = [
            'Maintain a high response rate (above 90%)',
            'Deliver work on time to avoid penalties',
            'Keep your profile updated with latest skills',
            'Communicate professionally with clients',
            'Submit original work only',
        ];

        $localServiceRules = [
            'Arrive 10 minutes before appointment time',
            'Wear professional attire / uniform',
            'Verify client identity before starting service',
            'Clean up the workspace after service',
            'Report any safety incidents immediately',
        ];

        foreach ($freelancerRules as $index => $rule) {
            ServiceRule::create([
                'type' => 'freelancer',
                'rule_content' => $rule,
                'order' => $index + 1,
            ]);
        }

        foreach ($localServiceRules as $index => $rule) {
            ServiceRule::create([
                'type' => 'local_service',
                'rule_content' => $rule,
                'order' => $index + 1,
            ]);
        }
    }
}
