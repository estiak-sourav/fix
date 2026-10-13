<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            [
                'name' => 'Residential Complexes & Apartments',
                'slug' => 'residential-complexes',
                'icon' => 'home',
                'short_desc' => 'Society management, shared elevator/generator maintenance, water reservoir care, and residential repairs.',
                'key_metrics' => '100+ Gated Communities Managed',
                'order' => 1,
            ],
            [
                'name' => 'Corporate Offices',
                'slug' => 'corporate-offices',
                'icon' => 'briefcase',
                'short_desc' => 'Daily janitorial staffing, HVAC climate comfort, structured electrical distribution, and modern access control.',
                'key_metrics' => '99.98% Facility Uptime',
                'order' => 2,
            ],
            [
                'name' => 'Hospitals & Clinics',
                'slug' => 'hospitals-clinics',
                'icon' => 'activity',
                'short_desc' => 'Clinical-grade infection sanitization, medical gas line audits, uninterrupted power failover, and bio-waste safety.',
                'key_metrics' => 'Bio-Safety Level 2 Compliant',
                'order' => 3,
            ],
            [
                'name' => 'Educational Institutions',
                'slug' => 'educational-institutions',
                'icon' => 'book-open',
                'short_desc' => 'School and university campus facility management, fire drill protocols, hygiene facilities, and electrical safety.',
                'key_metrics' => '30+ Campus Partners',
                'order' => 4,
            ],
            [
                'name' => 'Government Buildings',
                'slug' => 'government-buildings',
                'icon' => 'award',
                'short_desc' => 'Structured facility contracts adhering to safety and environmental governance guidelines.',
                'key_metrics' => 'Standard Governance Compliant',
                'order' => 5,
            ],
            [
                'name' => 'Industrial Zones & EPZ Facilities',
                'slug' => 'industrial-epz',
                'icon' => 'layers',
                'short_desc' => 'High-voltage switchgear, fire hydrant infrastructure, factory floor scrubbing, and wastewater plant care.',
                'key_metrics' => 'Heavy Industry Audited',
                'order' => 6,
            ],
        ];

        foreach ($sectors as $sector) {
            Sector::updateOrCreate(['slug' => $sector['slug']], $sector);
        }
    }
}
