<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\District;
use App\Models\Division;
use App\Models\Partner;
use App\Models\PartnerNotification;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Thana;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LocationAndCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected Division $division;

    protected District $district;

    protected Thana $thana;

    protected ServiceCategory $category;

    protected Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = Admin::first();

        $this->division = Division::firstOrCreate(['name' => 'Dhaka'], ['bn_name' => 'ঢাকা']);

        $this->district = District::firstOrCreate(
            ['name' => 'Dhaka'],
            ['division_id' => $this->division->id, 'bn_name' => 'ঢাকা', 'is_active' => true]
        );

        $this->thana = Thana::firstOrCreate(
            ['name' => 'Uttara'],
            ['district_id' => $this->district->id, 'bn_name' => 'উত্তরা', 'is_active' => true]
        );

        $this->category = ServiceCategory::firstOrCreate(
            ['slug' => 'electrical-power-systems'],
            [
                'name' => 'Electrical & Power Systems',
                'description' => 'Substations, wiring, breakers and panels',
                'order' => 1,
                'is_active' => true,
            ]
        );

        $this->service = Service::firstOrCreate(
            ['slug' => 'commercial-electrical-audit'],
            [
                'category_id' => $this->category->id,
                'title' => 'Commercial Electrical Audit',
                'price_starting' => 5000,
                'is_active' => true,
            ]
        );
    }

    public function test_admin_can_manage_service_categories(): void
    {
        // 1. Create Category
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.web.categories.store'), [
                'name' => 'HVAC & Climate Control',
                'description' => 'Central chillers, VRF and ducts',
                'order' => 2,
                'is_active' => 1,
            ]);

        $response->assertRedirect(route('admin.web.categories'));
        $created = ServiceCategory::where('name', 'HVAC & Climate Control')->first();
        $this->assertNotNull($created);
        $this->assertSame('hvac-climate-control', $created->slug);

        // 2. Update Category
        $updateResponse = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.web.categories.update', $created), [
                'name' => 'HVAC & Precision Cooling',
                'description' => 'Updated precision cooling systems',
                'order' => 3,
                'is_active' => 1,
            ]);

        $updateResponse->assertRedirect(route('admin.web.categories'));
        $created->refresh();
        $this->assertSame('HVAC & Precision Cooling', $created->name);

        // 3. Toggle Status
        $toggleResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.web.categories.toggle-status', $created));

        $toggleResponse->assertRedirect();
        $created->refresh();
        $this->assertFalse($created->is_active);

        // 4. Delete Category
        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.web.categories.destroy', $created));

        $deleteResponse->assertRedirect(route('admin.web.categories'));
        $this->assertDatabaseMissing('service_categories', ['id' => $created->id]);
    }

    public function test_admin_can_toggle_districts_and_manage_thanas(): void
    {
        // 1. Toggle District Active Status
        $this->assertTrue($this->district->is_active);
        $toggleDist = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.locations.districts.toggle', $this->district));

        $toggleDist->assertRedirect();
        $this->district->refresh();
        $this->assertFalse($this->district->is_active);

        // 2. Add New Thana
        $addThana = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.locations.thanas.store'), [
                'district_id' => $this->district->id,
                'name' => 'Gulshan 2',
                'bn_name' => 'গুলশান ২',
            ]);

        $addThana->assertRedirect();
        $newThana = Thana::where('name', 'Gulshan 2')->first();
        $this->assertNotNull($newThana);
        $this->assertSame($this->district->id, $newThana->district_id);

        // 3. Edit Thana Name
        $editThana = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.locations.thanas.update', $newThana), [
                'name' => 'Gulshan Diplomatic Zone',
                'bn_name' => 'গুলশান ডিপ্লোম্যাটিক জোন',
                'is_active' => 1,
            ]);

        $editThana->assertRedirect();
        $newThana->refresh();
        $this->assertSame('Gulshan Diplomatic Zone', $newThana->name);

        // 4. Toggle Thana Active State
        $toggleTh = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.locations.thanas.toggle', $newThana));

        $toggleTh->assertRedirect();
        $newThana->refresh();
        $this->assertFalse($newThana->is_active);

        // 5. Delete Thana
        $delTh = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.locations.thanas.destroy', $newThana));

        $delTh->assertRedirect();
        $this->assertDatabaseMissing('thanas', ['id' => $newThana->id]);
    }

    public function test_admin_can_update_vendor_payout_and_vendor_sees_overview(): void
    {
        $partner = Partner::create([
            'company_name' => 'Delta Power Systems',
            'contact_person' => 'Kamal Hossain',
            'phone' => '01711223344',
            'trade_domain' => 'Electrical & Power Systems',
            'coverage_areas' => 'Dhaka, Gazipur',
            'status' => 'approved',
            'earning_model' => 'commission',
            'payout_rate' => 15.00,
            'commission_rate' => 15.00,
            'payment_method' => 'bkash',
            'mfs_number' => '01711223344',
        ]);

        $customer = User::factory()->create();

        $booking = Booking::create([
            'booking_number' => 'DHK-99001',
            'user_id' => $customer->id,
            'service_id' => $this->service->id,
            'service_type' => 'on_demand',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone ?? '01700000000',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Uttara',
            'street_address' => 'Road 12, Sector 4',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => '10:00 AM - 12:00 PM',
            'urgency' => 'standard',
            'problem_description' => 'Substation annual inspection required.',
            'status' => 'completed',
            'partner_job_status' => 'completed',
            'partner_id' => $partner->id,
            'estimated_cost' => 10000,
            'final_cost' => 10000,
            'partner_payout_amount' => 1500,
            'partner_payout_status' => 'unpaid',
        ]);

        // 1. Admin updates payout to 'paid'
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.bookings.partner-payout', $booking), [
                'partner_payout_amount' => 1500,
                'partner_payout_status' => 'paid',
                'partner_payout_method' => 'bkash',
                'partner_payout_reference' => 'BKASH-TXN-88192',
                'partner_payout_notes' => 'Settled via bKash merchant payout',
                'partner_payout_paid_at' => now()->toDateString(),
            ]);

        $response->assertRedirect();
        $booking->refresh();

        $this->assertSame('paid', $booking->partner_payout_status);
        $this->assertSame('BKASH-TXN-88192', $booking->partner_payout_reference);
        $this->assertNotNull($booking->partner_payout_paid_at);

        // 2. Verify Partner Notification generated
        $notification = PartnerNotification::where('partner_id', $partner->id)->first();
        $this->assertNotNull($notification);
        $this->assertSame('payout_settled', $notification->type);
        $this->assertStringContainsString('1,500', $notification->title);

        // 3. Verify Vendor Earnings View loads with totalPaid and pendingDue
        $portalResponse = $this->actingAs($partner, 'partner')
            ->get(route('partner.payouts.index'));

        $portalResponse->assertOk();
        $portalResponse->assertViewHas('totalEarned', 1500.0);
        $portalResponse->assertViewHas('totalPaid', 1500.0);
        $portalResponse->assertViewHas('pendingDue', 0.0);
    }

    public function test_smart_vendor_matching_filters_by_area_and_trade(): void
    {
        // Matching Partner (Dhaka + Electrical)
        $matchingPartner = Partner::create([
            'company_name' => 'Apex Electrical & Power Ltd',
            'contact_person' => 'Rashedul Islam',
            'phone' => '01711000111',
            'trade_domain' => 'Electrical & Power Systems',
            'coverage_areas' => 'Dhaka (Dhaka)',
            'status' => 'approved',
        ]);

        // Non-matching trade (Cleaning in Dhaka)
        $cleaningPartner = Partner::create([
            'company_name' => 'CleanPro Facilities',
            'contact_person' => 'Salma Begum',
            'phone' => '01722000222',
            'trade_domain' => 'Commercial Cleaning & Janitorial',
            'coverage_areas' => 'Dhaka (Dhaka)',
            'status' => 'approved',
        ]);

        // Non-matching area (Electrical in Sylhet)
        $sylhetPartner = Partner::create([
            'company_name' => 'Surma Electrical Tech',
            'contact_person' => 'Jamil Ahmed',
            'phone' => '01733000333',
            'trade_domain' => 'Electrical & Power Systems',
            'coverage_areas' => 'Sylhet (Sylhet)',
            'status' => 'approved',
        ]);

        $customer = User::factory()->create();

        $booking = Booking::create([
            'booking_number' => 'DHK-99002',
            'user_id' => $customer->id,
            'service_id' => $this->service->id, // Electrical Audit
            'service_type' => 'on_demand',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone ?? '01700000000',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Uttara',
            'street_address' => 'Road 12, Sector 4',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => '10:00 AM - 12:00 PM',
            'urgency' => 'standard',
            'problem_description' => 'Electrical substation testing.',
            'status' => 'pending_review',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.bookings.show', $booking));

        $response->assertOk();
        $response->assertViewHas('fieldCrews');

        /** @var Collection $fieldCrews */
        $fieldCrews = $response->viewData('fieldCrews');

        // Qualified partner IDs among partner entries
        $partnerCrews = $fieldCrews->filter(fn ($c) => $c->is_partner);

        // Matching partner should be present with Matched badge
        $matched = $partnerCrews->firstWhere('partner_id', $matchingPartner->id);
        $this->assertNotNull($matched);
        $this->assertStringContainsString('Matched Area & Trade', $matched->match_badge);

        // Cleaning partner should NOT be in the matching partner list when matching partners exist
        $cleaning = $partnerCrews->firstWhere('partner_id', $cleaningPartner->id);
        $this->assertNull($cleaning);

        // Sylhet partner should NOT be in the matching partner list
        $sylhet = $partnerCrews->firstWhere('partner_id', $sylhetPartner->id);
        $this->assertNull($sylhet);
    }

    public function test_public_active_locations_api_and_booking_page_only_include_active_districts(): void
    {
        // Deactivate Dhaka district
        $this->district->update(['is_active' => false]);

        // Create an active Chattogram district
        $ctgDiv = Division::firstOrCreate(['name' => 'Chattogram'], ['bn_name' => 'চট্টগ্রাম']);
        $ctgDist = District::create([
            'division_id' => $ctgDiv->id,
            'name' => 'Chattogram',
            'bn_name' => 'চট্টগ্রাম',
            'is_active' => true,
        ]);
        Thana::create([
            'district_id' => $ctgDist->id,
            'name' => 'Agrabad',
            'is_active' => true,
        ]);

        // 1. Check API endpoint
        $apiResponse = $this->get(route('api.locations.active'));
        $apiResponse->assertOk();
        $data = $apiResponse->json();

        // Dhaka should not be in the active districts of Dhaka division
        $dhakaDivData = collect($data)->firstWhere('name', 'Dhaka');
        if ($dhakaDivData) {
            $dhakaDistNames = collect($dhakaDivData['districts'])->pluck('name')->all();
            $this->assertNotContains('Dhaka', $dhakaDistNames);
        }

        // Chattogram should be present
        $ctgDivData = collect($data)->firstWhere('name', 'Chattogram');
        $this->assertNotNull($ctgDivData);
        $ctgDistNames = collect($ctgDivData['districts'])->pluck('name')->all();
        $this->assertContains('Chattogram', $ctgDistNames);

        // 2. Check Booking create page
        $bookingPageResponse = $this->get(route('booking.create'));
        $bookingPageResponse->assertOk();
        $bdLocations = $bookingPageResponse->viewData('bdLocations');

        $this->assertArrayHasKey('Chattogram', $bdLocations);
        $this->assertArrayHasKey('Chattogram', $bdLocations['Chattogram']);
        if (isset($bdLocations['Dhaka'])) {
            $this->assertArrayNotHasKey('Dhaka', $bdLocations['Dhaka']);
        }
    }
}
