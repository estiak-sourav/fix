<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin in dedicated 'admins' table
        Admin::updateOrCreate(
            ['email' => 'admin@fix.com.bd'],
            [
                'name' => 'FIX Operations Lead',
                'password' => Hash::make('password123'),
                'phone' => '+880 1700-000000',
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // 2. Demo Customer
        User::updateOrCreate(
            ['email' => 'client@fix.com.bd'],
            [
                'name' => 'Tanvir Rahman',
                'password' => Hash::make('password123'),
                'phone' => '+880 1819-123456',
                'company_name' => 'Summit Center',
                'property_type' => 'commercial',
                'default_address' => 'Floor 8, Summit Center, 18 Karwan Bazar',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Tejgaon / Karwan Bazar',
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
