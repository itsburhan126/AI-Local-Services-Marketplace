<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryFullDataSeeder extends Seeder
{
    private function createUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            'Graphics & Design' => [
                'Logo Design', 'Brand Style Guides', 'Game Art', 'Graphics for Streamers', 
                'Business Cards & Stationery', 'Website Design', 'App Design', 'UX Design', 
                'Landing Page Design', 'Resume Design', 'Illustration', 'NFT Art', 
                'Pattern Design', 'Fonts & Typography', 'Poster Design'
            ],
            'Programming & Tech' => [
                'WordPress', 'Website Builders & CMS', 'Game Development', 'Development for Streamers',
                'Mobile Apps', 'Web Programming', 'Desktop Applications', 'Chatbots',
                'Support & IT', 'Online Coding Lessons', 'Cybersecurity & Data Protection',
                'User Testing', 'QA & Review', 'E-Commerce Development', 'Blockchain & Cryptocurrency'
            ],
            'Digital Marketing' => [
                'Social Media Marketing', 'SEO', 'Public Relations', 'Content Marketing',
                'Podcast Marketing', 'Video Marketing', 'Email Marketing', 'Crowdfunding',
                'SEM', 'Display Advertising', 'E-Commerce Marketing', 'Influencer Marketing',
                'Community Management', 'Mobile Marketing & Advertising', 'Music Promotion'
            ],
            'Video & Animation' => [
                'Video Editing', 'Short Video Ads', 'Whiteboard & Animated Explainers',
                'Character Animation', '3D Product Animation', 'Lyric & Music Videos',
                'Logo Animation', 'Intros & Outros', 'Visual Effects', 'Subtitles & Captions',
                'Lottie & Web Animation', 'Unboxing Videos', 'Live Action Explainers',
                'eLearning Video Production', 'Article to Video'
            ],
            'Writing & Translation' => [
                'Articles & Blog Posts', 'Translation', 'Proofreading & Editing', 'Resume Writing',
                'Cover Letters', 'LinkedIn Profiles', 'Ad Copy', 'Sales Copy',
                'Social Media Copy', 'Email Copy', 'Case Studies', 'Book & eBook Writing',
                'Scriptwriting', 'Creative Writing', 'Podcast Writing'
            ],
            'Music & Audio' => [
                'Voice Over', 'Mixing & Mastering', 'Producers & Composers', 'Singers & Vocalists',
                'Session Musicians', 'Online Music Lessons', 'Songwriters', 'Beat Making',
                'Audiobook Production', 'Audio Ads Production', 'Sound Design', 'Jingles & Intros'
            ],
            'Business' => [
                'Virtual Assistant', 'Data Entry', 'Market Research', 'Project Management',
                'Business Plans', 'Legal Consulting', 'Financial Consulting', 'Business Consulting',
                'HR Consulting', 'Career Counseling'
            ],
            'Data' => [
                'Data Entry', 'Data Analytics', 'Data Visualization', 'Data Science',
                'Databases', 'Data Engineering', 'Data Processing', 'Data Formatting',
                'Data Cleaning', 'Data Typing'
            ],
            'Photography' => [
                'Product Photography', 'Portrait Photography', 'Lifestyle Photography',
                'Real Estate Photography', 'Event Photography', 'Food Photography',
                'Aerial Photography', 'Editing & Retouching', 'Photography Classes', 'Advice'
            ],
            'AI Services' => [
                'AI Applications', 'AI Integrations', 'AI Agents', 'AI Artists',
                'Custom GPTs', 'Data Science & AI', 'Voice Synthesis & AI', 'Fact Checking',
                'AI Video Creation', 'AI Content Editing'
            ]
        ];

        // Flatten array to pick 10 random for footer, or pick specific ones.
        // Let's pick specific popular ones for footer.
        $footerCategories = [
            'Graphics & Design', 'Digital Marketing', 'Writing & Translation', 
            'Video & Animation', 'Programming & Tech', 'Logo Design', 
            'WordPress', 'SEO', 'Voice Over', 'Data Entry'
        ];

        foreach ($categories as $parentName => $subCategories) {
            $isFooterParent = in_array($parentName, $footerCategories);
            
            $parent = Category::create([
                'name' => $parentName,
                'slug' => $this->createUniqueSlug($parentName),
                'image' => 'https://placehold.co/600x400?text=' . urlencode($parentName),
                'is_active' => true,
                'is_shown_in_footer' => $isFooterParent,
                'type' => 'service', // Assuming 'service' is a valid type
            ]);

            foreach ($subCategories as $subName) {
                $isFooterSub = in_array($subName, $footerCategories);
                
                Category::create([
                    'name' => $subName,
                    'slug' => $this->createUniqueSlug($subName),
                    'parent_id' => $parent->id,
                    'image' => 'https://placehold.co/600x400?text=' . urlencode($subName),
                    'is_active' => true,
                    'is_shown_in_footer' => $isFooterSub,
                    'type' => 'service',
                ]);
            }
        }
    }
}
