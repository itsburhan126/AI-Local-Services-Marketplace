<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guide;
use Illuminate\Support\Str;

class GuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            [
                'title' => 'Getting Started with Findlancer',
                'excerpt' => 'A complete guide to setting up your account and finding your first gig.',
                'content' => '<h2>Welcome to Findlancer!</h2><p>This guide will help you navigate the platform effectively. First, complete your profile with accurate information. A complete profile increases your chances of getting hired.</p><h3>Step 1: Profile Setup</h3><p>Upload a professional photo and write a compelling bio.</p><h3>Step 2: Searching for Gigs</h3><p>Use our advanced search filters to find gigs that match your skills.</p>',
                'category' => 'Getting Started',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'title' => 'How to Write a Winning Proposal',
                'excerpt' => 'Learn the secrets of writing proposals that get you hired.',
                'content' => '<h2>Crafting the Perfect Proposal</h2><p>Your proposal is your first impression. Make it count.</p><ul><li><strong>Be specific:</strong> Address the client\'s needs directly.</li><li><strong>Keep it concise:</strong> Clients are busy. Get to the point.</li><li><strong>Proofread:</strong> Spelling errors can look unprofessional.</li></ul><p>Follow these tips and watch your acceptance rate soar!</p>',
                'category' => 'Freelancing Tips',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Safety Tips for Buyers and Sellers',
                'excerpt' => 'Stay safe while trading on our platform with these essential tips.',
                'content' => '<h2>Safety First</h2><p>We prioritize your safety. Here are some guidelines to keep you protected.</p><h3>Communication</h3><p>Always communicate within the platform.</p><h3>Payments</h3><p>Never accept payments outside of Findlancer. Our escrow system protects both parties.</p>',
                'category' => 'Safety',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Understanding Service Fees',
                'excerpt' => 'Everything you need to know about our fee structure.',
                'content' => '<h2>Our Fee Structure</h2><p>We believe in transparency. Here is a breakdown of our fees.</p><p>We charge a small percentage on each completed transaction to maintain the platform and provide support.</p>',
                'category' => 'Billing & Payments',
                'image_path' => null,
                'is_active' => true,
            ],
            [
                'title' => 'Top 10 Skills in Demand for 2026',
                'excerpt' => 'Discover which skills are trending and how to upskill.',
                'content' => '<h2>Trending Skills</h2><p>The market is always changing. Here are the top skills employers are looking for this year:</p><ol><li>AI & Machine Learning</li><li>Cybersecurity</li><li>Data Analysis</li><li>Blockchain Development</li></ol><p>Start learning these today!</p>',
                'category' => 'Career Advice',
                'image_path' => null,
                'is_active' => true,
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create(array_merge($guide, [
                'slug' => Str::slug($guide['title']),
            ]));
        }
    }
}
