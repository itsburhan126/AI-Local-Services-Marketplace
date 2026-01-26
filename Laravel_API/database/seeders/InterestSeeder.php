<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Interest;
use App\Models\Category;
use Illuminate\Support\Str;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define a mapping of Interests to potential Category Slugs
        // We will try to find the category, otherwise leave null.
        
        $interests = [
            // Home & Living
            ['name' => 'Home Cleaning', 'icon' => 'cleaning-service', 'category' => 'cleaning'],
            ['name' => 'Deep Cleaning', 'icon' => 'broom', 'category' => 'cleaning'],
            ['name' => 'Interior Design', 'icon' => 'interior', 'category' => 'design'],
            ['name' => 'Smart Home Setup', 'icon' => 'smart-home', 'category' => 'electronics'],
            ['name' => 'Gardening', 'icon' => 'gardening', 'category' => 'outdoor'],
            ['name' => 'Plumbing', 'icon' => 'plumbing', 'category' => 'repair'],
            ['name' => 'Electrical Repair', 'icon' => 'electricity', 'category' => 'repair'],
            ['name' => 'Painting', 'icon' => 'paint-roller', 'category' => 'repair'],
            ['name' => 'Furniture Assembly', 'icon' => 'furniture', 'category' => 'moving'],
            ['name' => 'Moving Service', 'icon' => 'move-stock', 'category' => 'moving'],
            ['name' => 'Pest Control', 'icon' => 'pest-control-service', 'category' => 'cleaning'],
            ['name' => 'AC Repair', 'icon' => 'air-conditioner', 'category' => 'repair'],
            ['name' => 'Roofing', 'icon' => 'roofing', 'category' => 'repair'],
            ['name' => 'Solar Panel Install', 'icon' => 'solar-panel', 'category' => 'repair'],
            ['name' => 'Pool Maintenance', 'icon' => 'pool', 'category' => 'outdoor'],

            // Wellness & Beauty
            ['name' => 'Yoga & Meditation', 'icon' => 'yoga', 'category' => 'wellness'],
            ['name' => 'Personal Training', 'icon' => 'dumbbell', 'category' => 'fitness'],
            ['name' => 'Massage Therapy', 'icon' => 'massage', 'category' => 'wellness'],
            ['name' => 'Skincare', 'icon' => 'skincare', 'category' => 'beauty'],
            ['name' => 'Hair Styling', 'icon' => 'hair-dryer', 'category' => 'beauty'],
            ['name' => 'Makeup Artist', 'icon' => 'makeup', 'category' => 'beauty'],
            ['name' => 'Nutrition Planning', 'icon' => 'healthy-food', 'category' => 'wellness'],
            ['name' => 'Physiotherapy', 'icon' => 'physical-therapy', 'category' => 'wellness'],
            ['name' => 'Mental Health', 'icon' => 'brain', 'category' => 'wellness'],
            ['name' => 'Nail Care', 'icon' => 'nail-polish', 'category' => 'beauty'],

            // Events & Lifestyle
            ['name' => 'Event Planning', 'icon' => 'planner', 'category' => 'events'],
            ['name' => 'Photography', 'icon' => 'camera', 'category' => 'photography'],
            ['name' => 'Catering', 'icon' => 'waiter', 'category' => 'events'],
            ['name' => 'DJ & Music', 'icon' => 'dj', 'category' => 'events'],
            ['name' => 'Wedding Planning', 'icon' => 'wedding-rings', 'category' => 'events'],
            ['name' => 'Personal Chef', 'icon' => 'chef-hat', 'category' => 'food'],
            ['name' => 'Travel Planning', 'icon' => 'airplane-tail-fin', 'category' => 'travel'],
            ['name' => 'Pet Grooming', 'icon' => 'dog-grooming', 'category' => 'pets'],
            ['name' => 'Dog Walking', 'icon' => 'dog-walking', 'category' => 'pets'],
            ['name' => 'Pet Sitting', 'icon' => 'cat-footprint', 'category' => 'pets'],

            // Business & Tech
            ['name' => 'Web Development', 'icon' => 'code', 'category' => 'business'],
            ['name' => 'Graphic Design', 'icon' => 'design', 'category' => 'design'],
            ['name' => 'Digital Marketing', 'icon' => 'bullish', 'category' => 'business'],
            ['name' => 'SEO Optimization', 'icon' => 'seo', 'category' => 'business'],
            ['name' => 'Legal Consulting', 'icon' => 'law', 'category' => 'legal'],
            ['name' => 'Financial Planning', 'icon' => 'money-bag', 'category' => 'finance'],
            ['name' => 'Content Writing', 'icon' => 'content', 'category' => 'business'],
            ['name' => 'Translation', 'icon' => 'translation', 'category' => 'business'],
            ['name' => 'IT Support', 'icon' => 'computer-support', 'category' => 'technology'],
            ['name' => 'Data Entry', 'icon' => 'keyboard', 'category' => 'business'],

            // Education & Skills
            ['name' => 'Language Learning', 'icon' => 'language', 'category' => 'education'],
            ['name' => 'Music Lessons', 'icon' => 'musical-notes', 'category' => 'education'],
            ['name' => 'Tutoring', 'icon' => 'teacher', 'category' => 'education'],
            ['name' => 'Cooking Classes', 'icon' => 'cooking-pot', 'category' => 'education'],
            ['name' => 'Art Classes', 'icon' => 'palette', 'category' => 'education'],
        ];

        foreach ($interests as $index => $item) {
            $slug = Str::slug($item['name']);
            
            // Try to find a category
            $categoryId = null;
            if (isset($item['category'])) {
                $category = Category::where('slug', $item['category'])->orWhere('name', 'LIKE', '%' . $item['category'] . '%')->first();
                if ($category) {
                    $categoryId = $category->id;
                }
            }

            Interest::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'icon' => 'https://img.icons8.com/fluency/96/' . $item['icon'] . '.png',
                    'category_id' => $categoryId,
                    'is_active' => true,
                    'order' => $index + 1,
                ]
            );
        }
    }
}
