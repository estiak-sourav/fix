<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Facility & Technical Maintenance',
                'slug' => 'maintenance',
                'description' => 'Electrical, HVAC, Plumbing, Generator, and building plant engineering.',
                'icon' => 'tool',
                'order' => 1,
            ],
            [
                'name' => 'Cleaning & Specialized Sanitization',
                'slug' => 'cleaning',
                'description' => 'Corporate janitorial, deep carpet scrubbing, and clinical disinfection.',
                'icon' => 'sparkles',
                'order' => 2,
            ],
            [
                'name' => 'Security & Fire Safety Systems',
                'slug' => 'security',
                'description' => 'BNBC/NFPA fire hydrants, smoke detectors, CCTV IP surveillance, and biometrics.',
                'icon' => 'shield',
                'order' => 3,
            ],
            [
                'name' => 'Interior Works & Renovation',
                'slug' => 'renovation',
                'description' => 'Office fit-outs, drywall partitions, acoustic dampening, epoxy, and premium paint.',
                'icon' => 'layout',
                'order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
