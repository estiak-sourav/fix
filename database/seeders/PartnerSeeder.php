<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'company_name' => 'Delta HVAC Engineering & Industrial Solutions',
                'contact_person' => 'Engr. Kamrul Hasan',
                'phone' => '01711223344',
                'email' => 'delta.hvac.bd@gmail.com',
                'trade_domain' => 'HVAC & Central Air Conditioning',
                'technicians_count' => '16-50',
                'coverage_areas' => 'Dhaka Metro, Savar, Gazipur Industrial Belt',
                'status' => 'approved',
                'partner_type' => 'company',
                'payment_method' => 'bank',
                'bank_name' => 'City Bank PLC',
                'account_holder' => 'Delta HVAC Engineering',
                'account_number' => '1102938475001',
                'branch_name' => 'Gulshan Branch',
                'commission_rate' => 12.50,
                'payout_terms' => 'bi_weekly',
                'admin_notes' => 'Verified HVAC contractor with certified safety harness equipment.',
                'approved_at' => now()->subDays(10),
            ],
            [
                'company_name' => 'PowerGrid Substation & High-Voltage Crew',
                'contact_person' => 'Md. Rafiqul Islam (Lead Electrical)',
                'phone' => '01822334455',
                'email' => 'powergrid.crew@gmail.com',
                'trade_domain' => 'Electrical & Substation',
                'technicians_count' => '6-15',
                'coverage_areas' => 'Chattogram EPZ, Agrabad, Sitakunda, Karnaphuli',
                'status' => 'approved',
                'partner_type' => 'individual',
                'payment_method' => 'bkash',
                'mfs_number' => '01822334455',
                'commission_rate' => 15.00,
                'payout_terms' => 'bi_weekly',
                'admin_notes' => 'Grade-A Electrical license holder. Ready for 24/7 industrial callout.',
                'approved_at' => now()->subDays(5),
            ],
            [
                'company_name' => 'Bengal Hydro Plumbers & Boosters',
                'contact_person' => 'Tariqul Anam',
                'phone' => '01933445566',
                'email' => 'bengal.hydro@gmail.com',
                'trade_domain' => 'Plumbing & Water Boosting',
                'technicians_count' => '6-15',
                'coverage_areas' => 'Dhaka North, Uttara, Mirpur, Ashulia',
                'status' => 'pending',
                'partner_type' => 'company',
                'admin_notes' => 'Awaiting admin trade license verification and payment account input.',
            ],
            [
                'company_name' => 'FireShield NFPA Safety Contractors',
                'contact_person' => 'Engr. Nazmul Huda',
                'phone' => '01644556677',
                'email' => 'fireshield.huda@gmail.com',
                'trade_domain' => 'Fire Safety & NFPA Suppression',
                'technicians_count' => '16-50',
                'coverage_areas' => 'Sylhet, Habiganj, Moulvibazar Industrial Park',
                'status' => 'pending',
                'partner_type' => 'company',
                'admin_notes' => 'Application submitted from web portal. Call scheduled for document verification.',
            ],
        ];

        foreach ($partners as $data) {
            Partner::updateOrCreate(['phone' => $data['phone']], $data);
        }
    }
}
