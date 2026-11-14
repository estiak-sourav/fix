<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminPaymentMethod;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\PartnerCrew;
use App\Models\PartnerPayout;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPartnerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_view_create_partner_page(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.partners.create'));

        $response->assertStatus(200);
        $response->assertSee('Enlist New Partner / Field Crew');
        $response->assertSee('Company / Trade Name');
        $response->assertSee('Lead Representative');
    }

    public function test_admin_can_store_new_partner_and_field_crew(): void
    {
        $admin = Admin::first();

        $data = [
            'company_name' => 'Apex Central Cooling Systems Ltd',
            'contact_person' => 'Engr. Mofizul Islam',
            'phone' => '+8801712345678',
            'email' => 'operations@apexcooling.com',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC & Central Air Conditioning',
            'technicians_count' => '16-50',
            'coverage_areas' => 'Dhaka Metro, Savar EPZ, Gazipur',
            'status' => 'approved',
            'payout_terms' => 'bi_weekly',
            'commission_rate' => 12.50,
            'payment_method' => 'bank',
            'bank_name' => 'BRAC Bank PLC',
            'account_holder' => 'Apex Central Cooling Systems Ltd',
            'account_number' => '1501204892001',
            'branch_name' => 'Gulshan Corporate Branch',
            'admin_notes' => 'Directly enlisted and verified by lead operations admin.',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.partners.store'), $data);

        $partner = Partner::where('company_name', 'Apex Central Cooling Systems Ltd')->first();

        $this->assertNotNull($partner);
        $this->assertSame('approved', $partner->status);
        $this->assertSame($admin->id, $partner->approved_by_admin_id);
        $this->assertNotNull($partner->approved_at);
        $this->assertEquals(12.50, $partner->commission_rate);

        $response->assertRedirect(route('admin.partners.show', $partner));
    }

    public function test_admin_can_view_edit_partner_page_and_update(): void
    {
        $admin = Admin::first();

        $partner = Partner::create([
            'company_name' => 'Legacy Volt Electro',
            'contact_person' => 'Kamrul Hasan',
            'phone' => '+8801811223344',
            'email' => 'volt@legacy.com',
            'trade_domain' => 'Electrical & Substation',
            'technicians_count' => '1-5',
            'coverage_areas' => 'Dhaka Metro',
            'status' => 'pending',
            'partner_type' => 'individual',
        ]);

        $editResponse = $this->actingAs($admin, 'admin')
            ->get(route('admin.partners.edit', $partner));

        $editResponse->assertStatus(200);
        $editResponse->assertSee('Legacy Volt Electro');

        $updateData = [
            'company_name' => 'Volt Electro Certified Engineers',
            'contact_person' => 'Engr. Kamrul Hasan',
            'phone' => '+8801811223344',
            'email' => 'volt@certified.com',
            'partner_type' => 'company',
            'trade_domain' => 'Electrical & Substation',
            'technicians_count' => '6-15',
            'coverage_areas' => 'Dhaka, Gazipur, Narayanganj',
            'status' => 'approved',
            'payout_terms' => 'weekly',
            'commission_rate' => 15.00,
            'payment_method' => 'bkash',
            'mfs_number' => '01811223344',
            'admin_notes' => 'Upgraded from individual technician to certified partner team.',
        ];

        $updateResponse = $this->actingAs($admin, 'admin')
            ->put(route('admin.partners.update', $partner), $updateData);

        $partner->refresh();

        $this->assertSame('Volt Electro Certified Engineers', $partner->company_name);
        $this->assertSame('approved', $partner->status);
        $this->assertSame('bkash', $partner->payment_method);
        $this->assertSame('01811223344', $partner->mfs_number);
        $this->assertNotNull($partner->approved_at);

        $updateResponse->assertRedirect(route('admin.partners.show', $partner));
    }

    public function test_web_partner_application_with_multiple_checkboxes(): void
    {
        $payload = [
            'company_name' => 'United MEP Contractors',
            'contact_person' => 'Engr. Zahirul Haque',
            'phone' => '01799887766',
            'email' => 'contact@unitedmep.com',
            'trade_domain' => [
                'Electrical & Substation Power',
                'HVAC & Central Air Conditioning',
                'Fire Safety & NFPA Suppression',
            ],
            'technicians_count' => '16-50',
            'coverage_areas' => [
                'Gulshan',
                'Banani',
                'Uttara',
                'Tongi',
            ],
        ];

        $response = $this->post(route('vendor.apply'), $payload);

        $response->assertSessionHas('success');

        $partner = Partner::where('phone', '01799887766')->first();
        $this->assertNotNull($partner);
        $this->assertStringContainsString('Electrical & Substation Power', $partner->trade_domain);
        $this->assertStringContainsString('HVAC & Central Air Conditioning', $partner->trade_domain);
        $this->assertStringContainsString('Fire Safety & NFPA Suppression', $partner->trade_domain);
        $this->assertStringContainsString('Gulshan', $partner->coverage_areas);
        $this->assertStringContainsString('Tongi', $partner->coverage_areas);
        $this->assertSame('pending', $partner->status);
    }

    public function test_admin_can_store_and_update_partner_with_multiple_checkboxes(): void
    {
        $admin = Admin::first();

        $storeData = [
            'company_name' => 'Vertex Industrial Engineering',
            'contact_person' => 'Md. Farhad Hossain',
            'phone' => '+8801955667788',
            'email' => 'admin@vertexengineering.com',
            'partner_type' => 'company',
            'trade_domain' => [
                'Plumbing & Water Boosting Systems',
                'Fire Safety & NFPA Suppression',
            ],
            'technicians_count' => '6-15',
            'coverage_areas' => [
                'Gulshan',
                'Banani',
            ],
            'status' => 'approved',
            'payout_terms' => 'bi_weekly',
            'commission_rate' => 10.0,
            'payment_method' => 'bank',
            'bank_name' => 'Eastern Bank PLC',
            'account_holder' => 'Vertex Industrial Engineering',
            'account_number' => '1029384756',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.partners.store'), $storeData);

        $partner = Partner::where('phone', '+8801955667788')->first();
        $this->assertNotNull($partner);
        $this->assertStringContainsString('Plumbing & Water Boosting Systems', $partner->trade_domain);
        $this->assertStringContainsString('Fire Safety & NFPA Suppression', $partner->trade_domain);
        $this->assertStringContainsString('Gulshan', $partner->coverage_areas);
        $this->assertStringContainsString('Banani', $partner->coverage_areas);
        $response->assertRedirect(route('admin.partners.show', $partner));

        // Update with new set of checkboxes
        $updateData = array_merge($storeData, [
            'company_name' => 'Vertex MEP Solutions Ltd',
            'trade_domain' => [
                'Electrical & Substation Power',
                'HVAC & Central Air Conditioning',
                'Plumbing & Water Boosting Systems',
            ],
            'coverage_areas' => [
                'Uttara',
                'Mirpur',
            ],
        ]);

        $updateResponse = $this->actingAs($admin, 'admin')
            ->put(route('admin.partners.update', $partner), $updateData);

        $partner->refresh();
        $this->assertSame('Vertex MEP Solutions Ltd', $partner->company_name);
        $this->assertStringContainsString('Electrical & Substation Power', $partner->trade_domain);
        $this->assertStringContainsString('Uttara', $partner->coverage_areas);
        $this->assertStringContainsString('Mirpur', $partner->coverage_areas);
        $updateResponse->assertRedirect(route('admin.partners.show', $partner));
    }

    public function test_admin_can_view_partner_show_page_with_tabbed_menu_and_payables(): void
    {
        $admin = Admin::first();
        $partner = Partner::firstOrCreate([
            'phone' => '+8801700000001',
        ], [
            'company_name' => 'Apex Cooling Engineering',
            'contact_person' => 'Mofizul Islam',
            'trade_domain' => 'HVAC & Central Air Conditioning',
            'partner_type' => 'company',
            'status' => 'approved',
            'coverage_areas' => 'Dhaka Metro',
        ]);

        $crew = PartnerCrew::firstOrCreate([
            'partner_id' => $partner->id,
            'phone' => '+8801700000099',
        ], [
            'name' => 'Kabir Hossain',
            'trade_skill' => 'AC Specialist',
        ]);

        $service = Service::first();
        $user = User::first() ?? User::factory()->create();

        Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'partner_id' => $partner->id,
            'service_id' => $service?->id,
            'assigned_partner_crew_id' => $crew->id,
            'customer_name' => 'Rahim Chowdhury',
            'customer_email' => 'rahim@example.com',
            'customer_phone' => '+8801711112233',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'House 10, Road 5, Gulshan',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => '10:00 AM - 11:00 AM',
            'urgency' => 'standard',
            'problem_description' => 'Cooling system inspection.',
            'status' => 'confirmed',
            'service_type' => 'on_demand',
            'total_amount' => 2500,
            'partner_payout_amount' => 2000,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.partners.show', $partner));

        $response->assertStatus(200);
        $response->assertSee('Work History');
        $response->assertSee('Payables & Financials', false);
        $response->assertSee('Crew Members');
        $response->assertSee('Admin & Payout Setup', false);
        $response->assertSee('Business Profile & Docs', false);
        $response->assertSee('Field Crew Status');
        $response->assertSee('Payable Due');
        $response->assertSee('Kabir Hossain');
    }

    public function test_admin_earnings_displays_vendor_payables_breakdown(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.earnings.index'));

        $response->assertStatus(200);
        $response->assertSee('Vendor Payables & Outstanding Disbursements', false);
        $response->assertSee('Total Pending Payables');
    }

    public function test_admin_partner_create_and_edit_reflects_dynamic_categories_and_active_districts(): void
    {
        $admin = Admin::first();

        // 1. Create a dynamic active category & an inactive category
        $activeCat = ServiceCategory::create([
            'name' => 'Industrial Robotics & Automation',
            'slug' => 'industrial-robotics-automation',
            'is_active' => true,
            'order' => 99,
        ]);

        $inactiveCat = ServiceCategory::create([
            'name' => 'Legacy Discontinued Division',
            'slug' => 'legacy-discontinued-division',
            'is_active' => false,
            'order' => 100,
        ]);

        // 2. View create page
        $createResponse = $this->actingAs($admin, 'admin')
            ->get(route('admin.partners.create'));

        $createResponse->assertOk();
        $createResponse->assertSee('Industrial Robotics & Automation');
        $createResponse->assertDontSee('Legacy Discontinued Division');
        $createResponse->assertSee('Gulshan');

        // 3. View edit page
        $partner = Partner::first() ?? Partner::create([
            'company_name' => 'Legacy Partner Co',
            'contact_person' => 'Kamal',
            'phone' => '+8801700000000',
            'partner_type' => 'company',
            'trade_domain' => 'Electrical',
            'technicians_count' => '1-5',
            'coverage_areas' => 'Gulshan',
            'status' => 'approved',
        ]);
        $editResponse = $this->actingAs($admin, 'admin')
            ->get(route('admin.partners.edit', $partner));

        $editResponse->assertOk();
        $editResponse->assertSee('Industrial Robotics & Automation');
        $editResponse->assertDontSee('Legacy Discontinued Division');
        $editResponse->assertSee('Gulshan');
    }

    public function test_vendor_application_page_renders_dynamic_active_categories_and_active_districts(): void
    {
        $activeCat = ServiceCategory::create([
            'name' => 'High Voltage Substation Automation',
            'slug' => 'high-voltage-substation-automation',
            'is_active' => true,
            'order' => 88,
        ]);

        $inactiveCat = ServiceCategory::create([
            'name' => 'Archived Trade Competency',
            'slug' => 'archived-trade-competency',
            'is_active' => false,
            'order' => 89,
        ]);

        $response = $this->get(route('vendor'));
        $response->assertOk();
        $response->assertSee('High Voltage Substation Automation');
        $response->assertDontSee('Archived Trade Competency');
        $response->assertSee('Gulshan');
    }

    public function test_admin_can_manage_official_receiving_payment_methods(): void
    {
        $admin = Admin::first();

        // 1. View listing page
        $indexResponse = $this->actingAs($admin, 'admin')
            ->get(route('admin.payment-methods.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Receiving Payment Methods');

        // 2. Create a new receiving payment method (e.g. Rocket Merchant)
        $storeResponse = $this->actingAs($admin, 'admin')
            ->post(route('admin.payment-methods.store'), [
                'method_type' => 'rocket',
                'account_number' => '01811223344-9',
                'account_name' => 'FIX Facility Rocket Merchant',
                'number_type' => 'merchant',
                'instructions' => 'Use Rocket Merchant Payment Option',
                'is_active' => true,
                'is_default' => false,
            ]);

        $storeResponse->assertRedirect(route('admin.payment-methods.index'));
        $storeResponse->assertSessionHas('success');

        $method = AdminPaymentMethod::where('account_number', '01811223344-9')->first();
        $this->assertNotNull($method);
        $this->assertEquals('rocket', $method->method_type);
        $this->assertEquals('merchant', $method->number_type);

        // 3. Set as default
        $defaultResponse = $this->actingAs($admin, 'admin')
            ->post(route('admin.payment-methods.default', $method));
        $defaultResponse->assertRedirect(route('admin.payment-methods.index'));
        $method->refresh();
        $this->assertTrue((bool) $method->is_default);

        // 4. Delete method
        $deleteResponse = $this->actingAs($admin, 'admin')
            ->delete(route('admin.payment-methods.destroy', $method));
        $deleteResponse->assertRedirect(route('admin.payment-methods.index'));
        $this->assertDatabaseMissing('admin_payment_methods', ['id' => $method->id]);
    }

    public function test_admin_can_request_dues_settlement_from_partner(): void
    {
        $admin = Admin::first();

        $partner = Partner::create([
            'company_name' => 'Dhaka Electric Pro',
            'contact_person' => 'Kamal',
            'phone' => '01700998877',
            'trade_domain' => 'Electrical',
            'coverage_areas' => 'Mirpur',
            'status' => 'approved',
            'earning_model' => 'per_job',
            'payout_rate' => 300.00,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.partners.request-payment', $partner), [
                'amount' => 1500.00,
                'due_date' => now()->addDays(2)->format('Y-m-d'),
                'admin_notes' => 'Please clear cash collected from customer booking DHK-00010',
            ]);

        $response->assertRedirect(route('admin.partners.show', $partner));
        $response->assertSessionHas('success');

        $payoutRequest = PartnerPayout::where('partner_id', $partner->id)->first();
        $this->assertNotNull($payoutRequest);
        $this->assertEquals('partner_to_admin', $payoutRequest->direction);
        $this->assertEquals('requested', $payoutRequest->status);
        $this->assertEquals('admin', $payoutRequest->initiated_by);
        $this->assertEquals(1500.00, (float) $payoutRequest->amount);
    }

    public function test_admin_can_approve_partner_settlement_payment_and_clear_dues(): void
    {
        $admin = Admin::first();

        $partner = Partner::create([
            'company_name' => 'Metro Cooling Care',
            'contact_person' => 'Rashed',
            'phone' => '01800112233',
            'trade_domain' => 'HVAC',
            'coverage_areas' => 'Gulshan',
            'status' => 'approved',
            'earning_model' => 'per_job',
            'payout_rate' => 500.00,
        ]);

        // Partner has submitted a settlement payment of 2000 to admin
        $settlement = PartnerPayout::create([
            'payout_number' => PartnerPayout::generatePayoutNumber('partner_to_admin'),
            'direction' => 'partner_to_admin',
            'initiated_by' => 'partner',
            'partner_id' => $partner->id,
            'payment_method_type' => 'bkash',
            'amount' => 2000.00,
            'status' => 'pending',
            'payment_method_details' => 'bKash Merchant 01700000000',
            'transaction_reference' => 'bKash TrxID TRX992211',
            'requested_at' => now(),
        ]);

        $approveResponse = $this->actingAs($admin, 'admin')
            ->post(route('admin.partners.payouts.approve', $settlement), [
                'admin_notes' => 'Verified on bKash merchant statement. Dues cleared.',
            ]);

        $approveResponse->assertRedirect(route('admin.partners.show', $partner));
        $approveResponse->assertSessionHas('success');

        $settlement->refresh();
        $this->assertEquals('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
        $this->assertEquals($admin->id, $settlement->processed_by_admin_id);

        $partner->refresh();
        $this->assertEquals(2000.00, $partner->paid_by_crew);
    }
}
