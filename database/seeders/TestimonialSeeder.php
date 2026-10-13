<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'Farhan Ahmed',
                'client_title' => 'Head of Operations',
                'company_name' => 'Apex Capital Tower (Banani)',
                'avatar_path' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                'rating' => 5,
                'quote' => 'Managing HVAC, generator maintenance, and janitorial staff across our 12-story commercial tower in Banani used to be a nightmare of disconnected vendors. FIX took over our complete AMC and reduced downtime by over 80%.',
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'client_name' => 'Nusrat Jahan',
                'client_title' => 'Secretary',
                'company_name' => 'Gulshan Park View Residents Society',
                'avatar_path' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
                'rating' => 5,
                'quote' => 'When our apartment complex experienced a major underground pipe burst, FIX dispatched their leak detection engineers within 40 minutes. They repaired the pressurized water line cleanly without damaging our tiles.',
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'client_name' => 'Engr. Rafiqul Islam',
                'client_title' => 'Plant Facility Manager',
                'company_name' => 'Gazipur Industrial Park',
                'avatar_path' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
                'rating' => 5,
                'quote' => 'Their specialized sanitization and fire safety compliance teams are exceptionally professional. They provide detailed inspection logs that pass all international compliance audits with zero remarks.',
                'is_featured' => true,
                'order' => 3,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(
                ['client_name' => $t['client_name'], 'company_name' => $t['company_name']],
                $t
            );
        }
    }
}
