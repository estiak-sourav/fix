<?php

namespace Database\Seeders;

use App\Models\AmcPlan;
use App\Models\Booking;
use App\Models\DailyAnalytics;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DailyAnalyticsSeeder extends Seeder
{
    /**
     * Seed realistic daily traffic, analytics, and sample bookings across past 30 days.
     */
    public function run(): void
    {
        $services = Service::all();
        $amcPlans = AmcPlan::all();
        $demoUser = User::firstOrCreate(
            ['email' => 'analytics.demo@fix.com.bd'],
            [
                'name' => 'Summit Holdings Ops',
                'phone' => '+8801819999999',
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $dhakaAreas = ['Gulshan 1', 'Gulshan 2', 'Banani', 'Dhanmondi', 'Uttara Sector 3', 'Motijheel C/A', 'Tejgaon Industrial Area', 'Mirpur 10'];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();

            // 1. Seed or update Daily Analytics
            $isWeekend = in_array($date->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY]);
            $baseMultiplier = $isWeekend ? 0.75 : 1.15;

            $totalVisitors = (int) (rand(140, 310) * $baseMultiplier);
            $newVisitors = (int) ($totalVisitors * rand(55, 70) / 100);
            $serviceVisitors = (int) ($totalVisitors * rand(35, 45) / 100);
            $amcVisitors = (int) ($totalVisitors * rand(18, 28) / 100);
            $logins = rand(15, 45);
            $registrations = rand(2, 9);

            DailyAnalytics::updateOrCreate(
                ['date' => $dateStr],
                [
                    'total_visitors' => $totalVisitors,
                    'new_visitors' => $newVisitors,
                    'service_visitors' => $serviceVisitors,
                    'amc_visitors' => $amcVisitors,
                    'logins_count' => $logins,
                    'registrations_count' => $registrations,
                ]
            );

            // 2. Ensure bookings exist on past days ($i >= 1) so charts display historical data
            $existingCount = Booking::whereDate('created_at', $dateStr)->count();
            if ($i >= 1 && $existingCount === 0 && $services->isNotEmpty()) {
                $ordersCount = rand(2, 4);

                for ($j = 0; $j < $ordersCount; $j++) {
                    $isAmc = ($j === 1 && $amcPlans->isNotEmpty());
                    $service = $services->random();
                    $amcPlan = $isAmc ? $amcPlans->random() : null;
                    $area = $dhakaAreas[array_rand($dhakaAreas)];
                    $urgency = $j === 0 ? 'emergency_1h' : ($j === 1 ? 'urgent_4h' : 'standard');
                    $cost = $isAmc ? ($amcPlan->price ?? 12000) : ($service->base_price ? ($service->base_price * rand(1, 3)) : 3500);

                    $booking = Booking::create([
                        'booking_number' => sprintf('FIX-%s-%04d-%s', $date->format('Ymd'), rand(10, 99), strtoupper(bin2hex(random_bytes(2)))),
                        'user_id' => $demoUser->id,
                        'service_id' => $isAmc ? null : $service->id,
                        'amc_plan_id' => $isAmc ? $amcPlan->id : null,
                        'service_type' => $isAmc ? 'amc_contract' : ($urgency === 'emergency_1h' ? 'emergency' : 'on_demand'),
                        'customer_name' => $demoUser->name,
                        'customer_email' => $demoUser->email,
                        'customer_phone' => $demoUser->phone,
                        'company_name' => $j % 2 === 0 ? 'Summit Power Hub' : 'Bashundhara Towers',
                        'property_type' => $j % 2 === 0 ? 'commercial' : 'residential',
                        'area' => $area,
                        'district' => 'Dhaka',
                        'street_address' => "Road 7, Block B, {$area}",
                        'preferred_date' => $dateStr,
                        'preferred_time_slot' => 'morning_09_12',
                        'urgency' => $urgency,
                        'problem_description' => $isAmc ? "Annual Maintenance Contract: {$amcPlan->name}" : "Maintenance inspection for {$service->title}",
                        'status' => $i > 2 ? 'completed' : ($i === 0 ? 'pending_review' : 'in_progress'),
                        'estimated_cost' => $cost,
                        'final_cost' => $i > 2 ? $cost : null,
                        'payment_status' => $i > 2 ? 'paid' : 'pending',
                        'completed_at' => $i > 2 ? $date->copy()->addHours(4) : null,
                    ]);

                    // Manually adjust created_at to the past date
                    $booking->created_at = $date->copy()->setHour(rand(9, 18))->setMinute(rand(5, 55));
                    $booking->updated_at = $date->copy()->setHour(rand(10, 19));
                    $booking->saveQuietly();
                }
            }
        }
    }
}
