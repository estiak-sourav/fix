<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            AmcPlanSeeder::class,
            SectorSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
            SiteCmsSeeder::class,
            BangladeshLocationsSeeder::class,
        ]);

        if (app()->environment('local', 'testing')) {
            $this->call([
                DailyAnalyticsSeeder::class,
            ]);
        }
    }
}
