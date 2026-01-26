<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class FreelancerSkillsSeeder extends Seeder
{
    public function run()
    {
        $freelancerSkills = [
            'Web Development',
            'Mobile App Development',
            'UI/UX Design',
            'Graphic Design',
            'SEO Optimization',
            'Content Writing',
            'Digital Marketing',
            'Data Analysis',
            'Python Programming',
            'React.js',
            'Laravel',
            'Node.js',
            'Video Editing',
            'Animation',
            'Translation',
            'Voice Over',
            'Blockchain Development',
            'Cybersecurity',
            'Cloud Computing',
            'DevOps',
        ];

        $this->command->info('Adding Freelancer Skills...');
        foreach ($freelancerSkills as $skill) {
            Skill::firstOrCreate(
                ['name' => $skill],
                ['type' => 'freelancer', 'is_active' => true]
            );
        }
        
        // Also add some Local Service skills just for context/example if needed, or stick to freelancer as requested
        // User asked for Freelancer skills specifically "IT sector"
        
        $this->command->info('Done! Added IT/Freelancer skills.');
    }
}
