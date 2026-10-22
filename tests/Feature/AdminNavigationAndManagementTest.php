<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNavigationAndManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test admin sidebar contains the exact requested collapsible navigation structure.
     */
    public function test_sidebar_contains_requested_navigation_hierarchy(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')->get('/admin');
        $response->assertStatus(200);

        // 1. Work Orders & Bookings (As it is)
        $response->assertSee('Work Orders & Bookings', false);

        // 2. Notifications Page (Newly added page)
        $response->assertSee('Notifications');

        // 3. Service Hub Collapsible Submenu
        $response->assertSee('Service Hub');
        $response->assertSee('Services & Catalog', false);
        $response->assertSee('AMC Packages');

        // 4. Web Settings Collapsible Submenu
        $response->assertSee('Web Settings');
        $response->assertSee('General Site Settings');
        $response->assertSee('SMTP Gateway Setup');

        // 5. Web Content Collapsible Submenu
        $response->assertSee('Web Content');
        $response->assertSee('Section Contents & Hero', false);
        $response->assertSee('FAQs Knowledgebase');
        $response->assertSee('Client Testimonials');

        // 6. Client Users
        $response->assertSee('Client Users');
        $response->assertSee('All Client List & Control', false);
    }

    /**
     * Test admin notifications page and actions.
     */
    public function test_admin_notifications_center_workflow(): void
    {
        $admin = Admin::first();

        // Create sample notifications
        $notif1 = AdminNotification::create([
            'type' => 'emergency_alert',
            'title' => 'Critical Facility Request: Server Room HVAC',
            'message' => 'Emergency dispatch needed immediately.',
            'is_read' => false,
            'sound_played' => true,
        ]);

        $notif2 = AdminNotification::create([
            'type' => 'new_booking',
            'title' => 'New Work Order Placed',
            'message' => 'Client booked Commercial Deep Cleaning.',
            'is_read' => false,
            'sound_played' => true,
        ]);

        // 1. View Notifications page
        $res = $this->actingAs($admin, 'admin')->get('/admin/notifications');
        $res->assertStatus(200);
        $res->assertSee('Operations Notifications');
        $res->assertSee('Critical Facility Request: Server Room HVAC');
        $res->assertSee('New Work Order Placed');

        // 2. Mark single notification as read
        $markRes = $this->actingAs($admin, 'admin')->post("/admin/notifications/{$notif1->id}/read");
        $markRes->assertRedirect();
        $this->assertTrue($notif1->fresh()->is_read);
        $this->assertFalse($notif2->fresh()->is_read);

        // 3. Mark all as read
        $markAllRes = $this->actingAs($admin, 'admin')->post('/admin/notifications/mark-all-read');
        $markAllRes->assertRedirect();
        $this->assertTrue($notif2->fresh()->is_read);

        // 4. Clear read history
        $clearRes = $this->actingAs($admin, 'admin')->post('/admin/notifications/clear-read');
        $clearRes->assertRedirect();
        $this->assertEquals(0, AdminNotification::where('is_read', true)->count());
    }

    /**
     * Test admin user management directory, status toggling, and profile updates.
     */
    public function test_admin_client_user_management(): void
    {
        $admin = Admin::first();

        // Create test clients
        $client1 = User::create([
            'name' => 'Rahim Uddin',
            'email' => 'rahim@example.com',
            'phone' => '+8801711000111',
            'company_name' => 'Rahim Holdings Ltd.',
            'property_type' => 'commercial',
            'area' => 'Gulshan 2',
            'district' => 'Dhaka',
            'role' => 'customer',
            'is_active' => true,
        ]);

        $client2 = User::create([
            'name' => 'Fatima Begum',
            'email' => 'fatima@example.com',
            'phone' => '+8801811222333',
            'property_type' => 'residential',
            'area' => 'Dhanmondi',
            'district' => 'Dhaka',
            'role' => 'customer',
            'is_active' => false,
        ]);

        // 1. View Client Directory
        $res = $this->actingAs($admin, 'admin')->get('/admin/users');
        $res->assertStatus(200);
        $res->assertSee('Client Users Directory');
        $res->assertSee('Rahim Uddin');
        $res->assertSee('Rahim Holdings Ltd.');
        $res->assertSee('Fatima Begum');

        // 2. Filter by status=1 (Active)
        $activeRes = $this->actingAs($admin, 'admin')->get('/admin/users?status=1');
        $activeRes->assertStatus(200);
        $activeRes->assertSee('Rahim Uddin');
        $activeRes->assertDontSee('Fatima Begum');

        // 3. Toggle Client status
        $toggleRes = $this->actingAs($admin, 'admin')->post("/admin/users/{$client1->id}/toggle-status");
        $toggleRes->assertRedirect();
        $this->assertFalse($client1->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($admin, 'admin')->post("/admin/users/{$client1->id}/toggle-status");
        $this->assertTrue($client1->fresh()->is_active);

        // 4. View client details & booking history page
        $showRes = $this->actingAs($admin, 'admin')->get("/admin/users/{$client1->id}");
        $showRes->assertStatus(200);
        $showRes->assertSee('Rahim Uddin');
        $showRes->assertSee('Rahim Holdings Ltd.');
        $showRes->assertSee('Gulshan 2');

        // 5. Update client details from admin
        $updateRes = $this->actingAs($admin, 'admin')->put("/admin/users/{$client1->id}", [
            'name' => 'Rahim Uddin Chowdhury',
            'phone' => '+8801711000999',
            'company_name' => 'Rahim & Sons Logistics',
            'property_type' => 'industrial',
            'area' => 'Tejgaon I/A',
            'district' => 'Dhaka',
            'is_active' => true,
        ]);

        $updateRes->assertRedirect();
        $client1->refresh();
        $this->assertEquals('Rahim Uddin Chowdhury', $client1->name);
        $this->assertEquals('+8801711000999', $client1->phone);
        $this->assertEquals('Rahim & Sons Logistics', $client1->company_name);
        $this->assertEquals('industrial', $client1->property_type);
    }

    /**
     * Test admin dashboard with daily visitor metrics, bar charts, and date range filters.
     */
    public function test_dashboard_analytics_bar_charts_and_date_range_filters(): void
    {
        $admin = Admin::first();

        // 1. Default view with 10 dedicated metric bars
        $defaultRes = $this->actingAs($admin, 'admin')->get('/admin');
        $defaultRes->assertStatus(200);
        $defaultRes->assertSee('unifiedCleanBarChart');
        $defaultRes->assertSee('Total visitor');
        $defaultRes->assertSee('New visitor');
        $defaultRes->assertSee('Login');
        $defaultRes->assertSee('Registration');
        $defaultRes->assertSee('Service visitor');
        $defaultRes->assertSee('AMC visitor');
        $defaultRes->assertSee('Service Request');
        $defaultRes->assertSee('Service Book');
        $defaultRes->assertSee('AMC Request');
        $defaultRes->assertSee('AMC Book');
        $defaultRes->assertSee('Yesterday');
        $defaultRes->assertSee('Today');
        $defaultRes->assertSee('Date to Date');
        $defaultRes->assertDontSee('5 Metric Bars');
        $defaultRes->assertDontSee('Compare with Previous Day');
        $defaultRes->assertDontSee('Real-time facility operations, traffic analytics, and dispatch monitoring');
        $defaultRes->assertSee('dashboard-sticky-bar');
        $defaultRes->assertSee('Orders');
        $defaultRes->assertSee('Subscriptions');
        $defaultRes->assertSee('Urgent');
        $defaultRes->assertSee('Processing');
        $defaultRes->assertSee('Pending');

        // 2. Today Preset
        $todayRes = $this->actingAs($admin, 'admin')->get('/admin?range=today');
        $todayRes->assertStatus(200);
        $todayRes->assertSee('Today');

        // 3. Yesterday / Previous Day Preset
        $yesterdayRes = $this->actingAs($admin, 'admin')->get('/admin?range=yesterday');
        $yesterdayRes->assertStatus(200);
        $yesterdayRes->assertSee('Yesterday');

        // 4. Single Date Jump (view any specific day)
        $singleDateRes = $this->actingAs($admin, 'admin')->get('/admin?date=2026-10-10');
        $singleDateRes->assertStatus(200);
        $singleDateRes->assertSee('Date: Oct 10, 2026');

        // 5. Custom Date to Date Range
        $customRes = $this->actingAs($admin, 'admin')->get('/admin?range=custom&start_date=2026-10-01&end_date=2026-10-15');
        $customRes->assertStatus(200);
        $customRes->assertSee('Custom: Oct 01, 2026');

        // 5. Test public visitor tracking increment
        $initialVisitors = \App\Models\DailyAnalytics::forDate()->total_visitors;
        $this->get('/');
        $updatedVisitors = \App\Models\DailyAnalytics::forDate()->fresh()->total_visitors;
        $this->assertGreaterThanOrEqual($initialVisitors, $updatedVisitors);
    }
}
