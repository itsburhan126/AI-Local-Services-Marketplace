<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FreelancerCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Web Development', 'Mobile App Development', 'Graphics Design', 'Digital Marketing', 
            'Content Writing', 'SEO Optimization', 'Video Editing', 'Animation', 
            'UI/UX Design', 'Data Entry', 'Virtual Assistant', 'Translation', 
            'Voice Over', 'Music Production', 'Photography', 'Illustration', 
            'Logo Design', 'Branding', 'Social Media Management', 'Email Marketing', 
            'Copywriting', 'Blog Writing', 'Technical Writing', 'E-commerce Development', 
            'Game Development', 'Blockchain Development', 'AI & Machine Learning', 'Cybersecurity', 
            'Cloud Computing', 'DevOps', 'Software Testing', 'Database Management', 
            'Network Administration', 'System Administration', 'IT Support', 'Hardware Repair', 
            'Consulting', 'Business Analysis', 'Project Management', 'Product Management', 
            'Accounting', 'Bookkeeping', 'Financial Analysis', 'Legal Consulting', 
            'HR Consulting', 'Recruitment', 'Training & Development', 'Coaching', 
            'Mentoring', 'Life Coaching'
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'type' => 'freelancer',
                'description' => $category . ' services',
                'is_active' => true,
                'order' => $index + 1,
                'image' => null, // Or a default image path if available
            ]);
        }
    }
}
