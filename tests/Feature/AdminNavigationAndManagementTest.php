<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\DailyAnalytics;
use App\Models\Faq;
use App\Models\SiteContent;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        // 1. Booking (As it is)
        $response->assertSee('Booking', false);

        // 2. Notifications Page (Newly added page)
        $response->assertSee('Notifications');

        // 3. Service Hub Collapsible Submenu
        $response->assertSee('Service Hub');
        $response->assertSee('Service List', false);
        $response->assertSee('AMC Packages');

        // 4. Web Settings (Direct menu item, internal tabs)
        $response->assertSee('Web Settings');
        $settingsPage = $this->actingAs($admin, 'admin')->get(route('admin.web.settings'));
        $settingsPage->assertSee('General Site Settings');
        $settingsPage->assertSee('SMTP Gateway Setup');

        // 5. Web Content (Direct menu item, internal tabs)
        $response->assertSee('Web Content');
        $contentPage = $this->actingAs($admin, 'admin')->get(route('admin.web.contents'));
        $contentPage->assertSee('Section Content', false);
        $contentPage->assertSee('FAQs Knowledgebase');
        $contentPage->assertSee('Client Testimonials');

        // 6. Client Users
        $response->assertSee('Client Users');
        $response->assertSee('Active Accounts');
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
        $initialVisitors = DailyAnalytics::forDate()->total_visitors;
        $this->get('/');
        $updatedVisitors = DailyAnalytics::forDate()->fresh()->total_visitors;
        $this->assertGreaterThanOrEqual($initialVisitors, $updatedVisitors);
    }

    /**
     * Test nationwide operations hotspots display and division-based filtering.
     */
    public function test_nationwide_operations_hotspots_and_division_filter(): void
    {
        $admin = Admin::first();
        $user = User::first() ?? User::factory()->create();

        // Create bookings across different divisions
        Booking::create([
            'booking_number' => 'FIX-CTG-001',
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'customer_name' => 'Chattogram Client',
            'customer_email' => 'ctg@example.com',
            'customer_phone' => '01711223344',
            'property_type' => 'commercial',
            'division' => 'Chattogram',
            'district' => 'Chattogram City',
            'area' => 'Agrabad Commercial',
            'street_address' => 'Plot 45, Agrabad C/A',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Industrial chiller pump vibration issue.',
            'status' => 'pending_review',
        ]);

        Booking::create([
            'booking_number' => 'FIX-SYL-001',
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'customer_name' => 'Sylhet Client',
            'customer_email' => 'sylhet@example.com',
            'customer_phone' => '01811223344',
            'property_type' => 'commercial',
            'division' => 'Sylhet',
            'district' => 'Sylhet City',
            'area' => 'Zindabazar',
            'street_address' => 'City Center, Zindabazar',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Afternoon',
            'urgency' => 'standard',
            'problem_description' => 'Main electrical switchboard servicing.',
            'status' => 'pending_review',
        ]);

        Booking::create([
            'booking_number' => 'FIX-DHK-001',
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'customer_name' => 'Dhaka Client',
            'customer_email' => 'dhaka@example.com',
            'customer_phone' => '01911223344',
            'property_type' => 'residential',
            'division' => 'Dhaka',
            'district' => 'Dhaka City',
            'area' => 'Gulshan 1 & 2',
            'street_address' => 'Road 12, Gulshan 1',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Plumbing pressure booster line maintenance.',
            'status' => 'pending_review',
        ]);

        // 1. All Bangladesh view displays hotspots from multiple divisions
        $allRes = $this->actingAs($admin, 'admin')->get('/admin');
        $allRes->assertStatus(200);
        $allRes->assertSee('Operations Hotspots');
        $allRes->assertSee('Agrabad Commercial');
        $allRes->assertSee('Chattogram City, Chattogram');

        $allHotspots = $allRes->viewData('areaHotspots');
        $this->assertTrue($allHotspots->has('Agrabad Commercial'));
        $this->assertTrue($allHotspots->has('Zindabazar'));
        $this->assertTrue($allHotspots->has('Gulshan 1 & 2'));

        // 2. Filter by Chattogram division only
        $ctgRes = $this->actingAs($admin, 'admin')->get('/admin?hotspot_division=Chattogram');
        $ctgRes->assertStatus(200);
        $ctgRes->assertSee('Operations Hotspots');
        $ctgRes->assertSee('Agrabad Commercial');
        $ctgRes->assertSee('Chattogram City, Chattogram');

        $ctgHotspots = $ctgRes->viewData('areaHotspots');
        $this->assertTrue($ctgHotspots->has('Agrabad Commercial'));
        $this->assertFalse($ctgHotspots->has('Zindabazar'));
        $this->assertFalse($ctgHotspots->has('Gulshan 1 & 2'));

        // 3. Filter by Barishal division with 0 records
        $barishalRes = $this->actingAs($admin, 'admin')->get('/admin?hotspot_division=Barishal');
        $barishalRes->assertStatus(200);
        $barishalRes->assertSee('No hotspot records for Barishal.');
        $this->assertTrue($barishalRes->viewData('areaHotspots')->isEmpty());

        // 4. Verify Area Details link exists on dashboard
        $allRes->assertSee(route('admin.area-analytics'));
        $allRes->assertSee('Area Details →');
        $allRes->assertDontSee('Division Distribution:');
    }

    /**
     * Test dedicated area analytics directory page with division and search filters.
     */
    public function test_admin_can_view_and_filter_area_analytics_page(): void
    {
        $admin = Admin::first();
        $user = User::first() ?? User::factory()->create();

        Booking::create([
            'booking_number' => 'FIX-CTG-TEST',
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'customer_name' => 'Ctg Test Client',
            'customer_email' => 'ctgtest@example.com',
            'customer_phone' => '01711223344',
            'property_type' => 'commercial',
            'division' => 'Chattogram',
            'district' => 'Chattogram City',
            'area' => 'Agrabad Commercial',
            'street_address' => 'Plot 10, Agrabad C/A',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'emergency_1h',
            'problem_description' => 'Chiller emergency repair',
            'status' => 'pending_review',
            'estimated_cost' => 5000,
        ]);

        // 1. Visit area analytics directory
        $res = $this->actingAs($admin, 'admin')->get('/admin/area-analytics');
        $res->assertStatus(200);
        $res->assertSee('Operations Hotspots & Area Directory', false);
        $res->assertSee('Area Performance Directory');
        $res->assertSee('Covered Areas');
        $res->assertSee('Agrabad Commercial');
        $res->assertSee('Chattogram City');

        // 2. Filter by search query
        $searchRes = $this->actingAs($admin, 'admin')->get('/admin/area-analytics?search=Agrabad');
        $searchRes->assertStatus(200);
        $searchRes->assertSee('Agrabad Commercial');

        // 3. Filter by division
        $divRes = $this->actingAs($admin, 'admin')->get('/admin/area-analytics?division=Chattogram');
        $divRes->assertStatus(200);
        $divRes->assertSee('Agrabad Commercial');

        // 4. Filter by non-existent area
        $emptyRes = $this->actingAs($admin, 'admin')->get('/admin/area-analytics?search=NonExistentLocality123');
        $emptyRes->assertStatus(200);
        $emptyRes->assertSee('No Area Records Found');
    }

    /**
     * Test admin dashboard Earning and Sales Analytics cards use real database data in BDT (৳).
     */
    public function test_dashboard_earning_and_sales_analytics_use_real_database_data(): void
    {
        $admin = Admin::first();

        $res = $this->actingAs($admin, 'admin')->get('/admin');
        $res->assertStatus(200);

        // 1. Verify dummy values in Earning and Sales Analytics are eliminated
        $res->assertDontSee('$2453.35');
        $res->assertDontSee('$2281.04');
        $res->assertDontSee('$ 2,132');

        // 2. Verify real BDT currency symbol and card structures exist
        $res->assertSee('৳');
        $res->assertSee('Earning');
        $res->assertSee('Sales Analytics');
        $res->assertSee('earningMonthSelector');
        $res->assertSee('earningSplineChart');
        $res->assertSee('salesAnalyticsDonutChart');

        // 3. Verify view data has monthly earning mapping and sales analytics
        $res->assertViewHas('earningMonthlyData');
        $res->assertViewHas('salesAnalytics');
        $res->assertViewHas('currentMonth');

        $earningData = $res->viewData('earningMonthlyData');
        $this->assertIsArray($earningData);
        $this->assertCount(12, $earningData);

        // Current month (10) check:
        $this->assertArrayHasKey(10, $earningData);
        $this->assertStringContainsString('৳', $earningData[10]['thisMonth']);

        $salesAnalytics = $res->viewData('salesAnalytics');
        $this->assertIsArray($salesAnalytics);
        $this->assertArrayHasKey('categories', $salesAnalytics);
        $this->assertArrayHasKey('total', $salesAnalytics);
        $this->assertGreaterThan(0, $salesAnalytics['total']);
        $this->assertStringContainsString('৳', $salesAnalytics['total_formatted']);
    }

    /**
     * Test testimonials page, list display, avatar image upload, update, and deletion.
     */
    public function test_testimonials_page_and_avatar_upload_crud_workflow(): void
    {
        Storage::fake('public');
        $admin = Admin::first();

        // 1. Visit testimonials page
        $res = $this->actingAs($admin, 'admin')->get(route('admin.web.testimonials'));
        $res->assertStatus(200);
        $res->assertSee('Client Testimonials List');
        $res->assertSee('Add Testimonial');
        $res->assertSee('Section Content');

        // 2. Add new testimonial with avatar upload
        $avatarFile = UploadedFile::fake()->image('test_client.jpg', 200, 200);
        $storeRes = $this->actingAs($admin, 'admin')->post(route('admin.web.testimonials.store'), [
            'client_name' => 'Farhan Chowdhury',
            'client_title' => 'Chief Operations Officer',
            'company_name' => 'Summit Towers',
            'rating' => 5,
            'quote' => 'FIX provides exceptional engineering SLA response times.',
            'avatar' => $avatarFile,
            'is_featured' => '1',
            'is_active' => '1',
        ]);
        $storeRes->assertRedirect();

        $testimonial = Testimonial::where('client_name', 'Farhan Chowdhury')->first();
        $this->assertNotNull($testimonial);
        $this->assertNotNull($testimonial->avatar_path);
        Storage::disk('public')->assertExists($testimonial->avatar_path);
        $this->assertStringContainsString('storage/'.$testimonial->avatar_path, $testimonial->avatar_url);

        // 3. Update testimonial with new data and remove avatar
        $updateRes = $this->actingAs($admin, 'admin')->put(route('admin.web.testimonials.update', $testimonial), [
            'client_name' => 'Farhan Chowdhury Senior',
            'client_title' => 'Director of Facilities',
            'company_name' => 'Summit Towers Holdings',
            'rating' => 5,
            'quote' => 'Updated review text for FIX maintenance services.',
            'remove_avatar' => '1',
            'is_featured' => '1',
            'is_active' => '1',
        ]);
        $updateRes->assertRedirect();

        $testimonial->refresh();
        $this->assertSame('Farhan Chowdhury Senior', $testimonial->client_name);
        $this->assertNull($testimonial->avatar_path);

        // 4. Delete testimonial
        $deleteRes = $this->actingAs($admin, 'admin')->delete(route('admin.web.testimonials.destroy', $testimonial));
        $deleteRes->assertRedirect();
        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    /**
     * Test FAQs page, list display, store, update, and delete workflow.
     */
    public function test_faqs_page_and_crud_workflow(): void
    {
        $admin = Admin::first();

        // 1. Visit FAQs page
        $res = $this->actingAs($admin, 'admin')->get(route('admin.web.faqs'));
        $res->assertStatus(200);
        $res->assertSee('FAQs Knowledgebase List');
        $res->assertSee('Add New FAQ');
        $res->assertSee('Section Content');

        // 2. Store new FAQ
        $storeRes = $this->actingAs($admin, 'admin')->post(route('admin.web.faqs.store'), [
            'category' => 'Engineering',
            'question' => 'How quickly are facility engineers dispatched?',
            'answer' => 'Engineers are dispatched within 60 minutes for emergency alerts.',
            'order' => 1,
            'is_active' => '1',
        ]);
        $storeRes->assertRedirect();

        $faq = Faq::where('question', 'How quickly are facility engineers dispatched?')->first();
        $this->assertNotNull($faq);

        // 3. Update FAQ
        $updateRes = $this->actingAs($admin, 'admin')->put(route('admin.web.faqs.update', $faq), [
            'category' => 'Engineering',
            'question' => 'How quickly are facility engineers dispatched in Dhaka?',
            'answer' => 'Engineers arrive within 45 minutes across all metropolitan areas.',
            'order' => 2,
            'is_active' => '1',
        ]);
        $updateRes->assertRedirect();

        $faq->refresh();
        $this->assertSame('How quickly are facility engineers dispatched in Dhaka?', $faq->question);

        // 4. Delete FAQ
        $deleteRes = $this->actingAs($admin, 'admin')->delete(route('admin.web.faqs.destroy', $faq));
        $deleteRes->assertRedirect();
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    /**
     * Test Section Content view and update modal action.
     */
    public function test_section_content_page_and_update_workflow(): void
    {
        $admin = Admin::first();

        // 1. Visit Section Content page
        $res = $this->actingAs($admin, 'admin')->get(route('admin.web.contents'));
        $res->assertStatus(200);
        $res->assertSee('Section Content');
        $res->assertSee('Services');
        $res->assertSee('AMC Packages');
        $res->assertSee('Client Testimonials');
        $res->assertSee('FAQs Knowledgebase');
        $res->assertSee('Edit Section');

        // 2. Update a section (Hero)
        $content = SiteContent::firstOrCreate(
            ['section_key' => 'hero'],
            ['title' => 'Original Hero Title', 'subtitle' => 'Original Subtitle']
        );

        $updateRes = $this->actingAs($admin, 'admin')->post(route('admin.web.contents.update', $content), [
            'title' => 'Premier Facilities & Engineering Management in Bangladesh',
            'subtitle' => 'Trusted commercial building maintenance and 24/7 technical dispatch.',
            'badge' => 'OPERATIONS COMMAND',
            'button_text' => 'Book Maintenance',
            'button_url' => '/booking',
        ]);
        $updateRes->assertRedirect();

        $content->refresh();
        $this->assertSame('Premier Facilities & Engineering Management in Bangladesh', $content->title);

        // 3. Update Clients section partner names metadata
        $clientsContent = SiteContent::firstOrCreate(
            ['section_key' => 'clients'],
            ['title' => 'Trusted by Leading Enterprises & Facilities']
        );
        $clientUpdate = $this->actingAs($admin, 'admin')->post(route('admin.web.contents.update', $clientsContent), [
            'title' => 'Trusted by Leading Enterprises & Facilities',
            'partner_names' => "Alpha Tech\nBeta Towers\nGamma Logistics",
        ]);
        $clientUpdate->assertRedirect();
        $clientsContent->refresh();
        $this->assertEquals(['Alpha Tech', 'Beta Towers', 'Gamma Logistics'], $clientsContent->meta_data['partner_names']);

        // 4. Ensure live user homepage renders the dynamic client partners
        $homeRes = $this->get('http://localhost/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('Alpha Tech');
        $homeRes->assertSee('Beta Towers');
        $homeRes->assertSee('Gamma Logistics');
    }
}
