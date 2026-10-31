<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PurgeDemoDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-demo-data {--force : Run without interactive confirmation} {--no-backup : Skip taking a JSON backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely purge customer test accounts, demo bookings, tickets, and outreach tasks to prepare for production.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->warn('=====================================================');
        $this->warn('  FIX FACILITY MANAGEMENT — PRODUCTION CLEANUP TOOL  ');
        $this->warn('=====================================================');

        if (! $this->option('force') && ! $this->confirm('This will delete all test users, demo bookings, support tickets, and test logs. Are you sure you want to proceed?', true)) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        // 1. Take JSON Backup unless --no-backup
        if (! $this->option('no-backup')) {
            $this->takeBackup();
        }

        // 2. Perform safe cleanup inside a transaction or disabled FK checks
        $this->info('Purging demo transactional tables...');

        Schema::disableForeignKeyConstraints();

        try {
            $deletedCounts = [];

            // Transactional & Customer Demo Data
            $tablesToClean = [
                'booking_status_logs',
                'amc_invoices',
                'bookings',
                'support_ticket_messages',
                'support_tickets',
                'outreach_tasks',
                'partners',
                'email_otps',
                'users',
                'admin_notifications',
                'admin_activity_logs',
                'daily_analytics',
                'sessions',
            ];

            foreach ($tablesToClean as $table) {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    DB::table($table)->truncate();
                    $deletedCounts[$table] = $count;
                    $this->line("  ✓ Truncated <comment>{$table}</comment> ({$count} records removed)");
                }
            }

            // Ensure Super Admin Account is intact
            $superAdmin = Admin::where('role', 'super_admin')->first();
            if (! $superAdmin) {
                $superAdmin = Admin::create([
                    'name' => 'FIX Super Admin',
                    'email' => 'admin@fix.com.bd',
                    'password' => Hash::make('password123'),
                    'role' => 'super_admin',
                    'permissions' => ['*'],
                    'phone' => '+8801700000000',
                    'status' => 'active',
                ]);
                $this->info('  ✓ Re-created default Super Admin (admin@fix.com.bd / password123)');
            } else {
                $this->info("  ✓ Super Admin preserved: <comment>{$superAdmin->email}</comment>");
            }

            Schema::enableForeignKeyConstraints();
        } catch (\Throwable $e) {
            Schema::enableForeignKeyConstraints();
            $this->error('Cleanup failed: '.$e->getMessage());

            return self::FAILURE;
        }

        // 3. Clear all caches
        $this->info('Clearing application caches...');
        $this->callSilently('cache:clear');
        $this->callSilently('view:clear');
        $this->callSilently('route:clear');
        $this->callSilently('config:clear');

        $this->newLine();
        $this->info('=====================================================');
        $this->info('  PROJECT IS NOW CLEAN & READY FOR LIVE PRODUCTION!  ');
        $this->info('=====================================================');
        $this->table(['Setting / Catalog', 'Status'], [
            ['Services Catalog', DB::table('services')->count().' active services ready'],
            ['Service Categories', DB::table('service_categories')->count().' categories ready'],
            ['AMC Plans', DB::table('amc_plans')->count().' annual maintenance plans ready'],
            ['Bangladesh Coverage Areas', DB::table('divisions')->count().' divisions, '.DB::table('districts')->count().' districts, '.DB::table('thanas')->count().' thanas ready'],
            ['CMS Site Settings', DB::table('site_settings')->count().' settings configured'],
            ['CMS Page Contents', DB::table('site_contents')->count().' sections configured'],
            ['Admin Access', "Preserved: {$superAdmin->email}"],
            ['Customer Users', '0 (Clean)'],
            ['Bookings & Orders', '0 (Clean)'],
            ['Support Tickets', '0 (Clean)'],
        ]);

        return self::SUCCESS;
    }

    /**
     * Export all demo records to a JSON file for safety.
     */
    private function takeBackup(): void
    {
        $backupDir = storage_path('app/backups');
        if (! File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = date('Y_m_d_His');
        $backupFile = "{$backupDir}/demo_data_backup_{$timestamp}.json";

        $backupData = [
            'created_at' => now()->toIso8601String(),
            'users' => DB::table('users')->get(),
            'bookings' => DB::table('bookings')->get(),
            'amc_invoices' => DB::table('amc_invoices')->get(),
            'booking_status_logs' => DB::table('booking_status_logs')->get(),
            'support_tickets' => DB::table('support_tickets')->get(),
            'support_ticket_messages' => DB::table('support_ticket_messages')->get(),
            'outreach_tasks' => DB::table('outreach_tasks')->get(),
            'partners' => DB::table('partners')->get(),
        ];

        File::put($backupFile, json_encode($backupData, JSON_PRETTY_PRINT));
        $this->info("Backup saved: <comment>{$backupFile}</comment>");
    }
}
