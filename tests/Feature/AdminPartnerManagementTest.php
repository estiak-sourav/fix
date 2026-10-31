<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Partner;
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
                'Dhaka North (Uttara, Gulshan, Banani, Mirpur)',
                'Gazipur & Tongi Industrial Belt',
                'Savar, Ashulia & Dhaka EPZ',
            ],
            'other_coverage_areas' => 'Mymensingh Industrial Zone',
        ];

        $response = $this->post(route('vendor.apply'), $payload);

        $response->assertSessionHas('success');

        $partner = Partner::where('phone', '01799887766')->first();
        $this->assertNotNull($partner);
        $this->assertStringContainsString('Electrical & Substation Power', $partner->trade_domain);
        $this->assertStringContainsString('HVAC & Central Air Conditioning', $partner->trade_domain);
        $this->assertStringContainsString('Fire Safety & NFPA Suppression', $partner->trade_domain);
        $this->assertStringContainsString('Dhaka North', $partner->coverage_areas);
        $this->assertStringContainsString('Mymensingh Industrial Zone', $partner->coverage_areas);
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
                'Dhaka South (Dhanmondi, Motijheel, Old Dhaka)',
                'Narayanganj & Keraniganj Industrial Area',
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
        $this->assertStringContainsString('Narayanganj & Keraniganj Industrial Area', $partner->coverage_areas);
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
                'Nationwide / Pan-Bangladesh Coverage',
            ],
            'other_coverage_areas' => 'DEPZ Specialized Zone',
        ]);

        $updateResponse = $this->actingAs($admin, 'admin')
            ->put(route('admin.partners.update', $partner), $updateData);

        $partner->refresh();
        $this->assertSame('Vertex MEP Solutions Ltd', $partner->company_name);
        $this->assertStringContainsString('Electrical & Substation Power', $partner->trade_domain);
        $this->assertStringContainsString('Nationwide / Pan-Bangladesh Coverage', $partner->coverage_areas);
        $this->assertStringContainsString('DEPZ Specialized Zone', $partner->coverage_areas);
        $updateResponse->assertRedirect(route('admin.partners.show', $partner));
    }
}
