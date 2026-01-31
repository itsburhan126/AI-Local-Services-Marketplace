<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QualityGuideline;

class QualityGuidelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guidelines = [
            [
                'title' => 'Communication',
                'description' => '<p class="mb-4">Clear communication is the foundation of success.</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Be Responsive:</strong> Reply to messages within 24 hours.</li>
                    <li><strong>Clarify Requirements:</strong> Ask questions if instructions are vague.</li>
                    <li><strong>Update Regularly:</strong> Keep the client informed about progress.</li>
                    <li><strong>Be Professional:</strong> Maintain a polite and respectful tone at all times.</li>
                </ul>',
                'icon_class' => 'fas fa-comments',
                'color_class' => 'blue',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Deliverables',
                'description' => '<p class="mb-4">Ensure your work meets or exceeds expectations.</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Follow Instructions:</strong> Adhere strictly to the project brief.</li>
                    <li><strong>Proofread/Test:</strong> Double-check your work for errors before delivery.</li>
                    <li><strong>Format Correctly:</strong> Deliver files in the requested formats.</li>
                    <li><strong>On Time:</strong> Always deliver by the agreed deadline.</li>
                </ul>',
                'icon_class' => 'fas fa-cube',
                'color_class' => 'purple',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Revisions & Feedback',
                'description' => '<p class="mb-4">Constructive feedback leads to better results.</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Be Open:</strong> Accept feedback gracefully and use it to improve.</li>
                    <li><strong>Provide Specifics:</strong> Clients should provide clear, actionable feedback.</li>
                    <li><strong>Fair Revisions:</strong> Freelancers should offer reasonable revisions as per their package.</li>
                </ul>',
                'icon_class' => 'fas fa-star',
                'color_class' => 'orange',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($guidelines as $guideline) {
            QualityGuideline::create($guideline);
        }
    }
}
