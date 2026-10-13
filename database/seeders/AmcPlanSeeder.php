<?php

namespace Database\Seeders;

use App\Models\AmcPlan;
use Illuminate\Database\Seeder;

class AmcPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Standard AMC',
                'slug' => 'standard-amc',
                'badge' => null,
                'tier' => 'residential_commercial',
                'target_audience' => 'Ideal for residential apartments & medium corporate offices',
                'price' => null,
                'price_period' => '/ monthly (customized)',
                'sla_response_time' => '60-Minute Emergency Dispatch SLA',
                'is_popular' => false,
                'features' => [
                    'Quarterly Electrical & Switchboard Thermal Audit',
                    'Bi-Annual Split/Cassette AC Master Jet Servicing',
                    'Plumbing Pressure Balancing & Reservoir Sanitization',
                    'Monthly Fire Extinguisher Gauge Audits & Alarms',
                    'Guaranteed 60-Minute Emergency Dispatch SLA',
                ],
                'button_text' => 'Inquire Plan',
                'order' => 1,
            ],
            [
                'name' => 'Corporate Comprehensive',
                'slug' => 'corporate-comprehensive',
                'badge' => '★ Most Popular Enterprise Choice',
                'tier' => 'corporate_enterprise',
                'target_audience' => 'Tailored for commercial towers, IT tech parks & corporate HQs',
                'price' => null,
                'price_period' => '/ custom enterprise scope',
                'sla_response_time' => 'Priority 45-Minute Emergency Response SLA',
                'is_popular' => true,
                'features' => [
                    'Dedicated On-Site Facility Lead & Engineering Supervisor',
                    'Central Chillers, AHU/FCU & VRF Preventive Care',
                    'Generator, ATS Switch & Substation Load Testing',
                    'BNBC & NFPA Certified Fire Hydrant & Alarm Testing',
                    'Daily Uniformed Janitorial & Facility Workforce',
                    'Guaranteed Priority 45-Minute Emergency Response SLA',
                ],
                'button_text' => 'Request Consultation',
                'order' => 2,
            ],
            [
                'name' => 'Industrial & EPZ Plan',
                'slug' => 'industrial-amc',
                'badge' => null,
                'tier' => 'industrial_manufacturing',
                'target_audience' => 'Engineered for factories, pharma cleanrooms & manufacturing',
                'price' => null,
                'price_period' => '/ tailored industrial scope',
                'sla_response_time' => '30-Minute Heavy Industrial SLA',
                'is_popular' => false,
                'features' => [
                    'High-Tension Substation & 3-Phase Power Balancing',
                    'Heavy Industrial Water Booster & Effluent Pumps',
                    'Certified Fire Sprinkler Maintenance & Drills',
                    'HACCP Compliant Pest Control & Rodent Barriers',
                    'Monthly Digital Health Reports & Zero Cost Overruns',
                ],
                'button_text' => 'Inquire Plan',
                'order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            AmcPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
