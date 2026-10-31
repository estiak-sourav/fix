<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AmcInvoice;
use App\Models\AmcPlan;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AmcRecurringBillingAndWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function createBooking(array $overrides = []): Booking
    {
        $user = User::factory()->create();

        return Booking::create(array_merge([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'service_type' => 'service',
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '01700000000',
            'customer_email' => $user->email,
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Banani',
            'street_address' => 'Test Address',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => '09:00 AM - 01:00 PM',
            'urgency' => 'standard',
            'problem_description' => 'Test description',
            'status' => 'pending_review',
        ], $overrides));
    }

    public function test_admin_can_filter_bookings_by_amc_and_running_status(): void
    {
        $admin = Admin::where('role', 'super_admin')->first() ?? Admin::first();

        // One-off regular booking
        $this->createBooking([
            'service_type' => 'service',
            'status' => 'completed',
        ]);

        // Running AMC Booking
        $plan = AmcPlan::first();
        $amcBooking = $this->createBooking([
            'service_type' => 'amc_contract',
            'amc_plan_id' => $plan?->id,
            'contract_status' => 'active',
            'billing_cycle' => 'monthly',
            'recurring_amount' => 12500,
            'contract_start_date' => now()->subMonth(),
            'contract_end_date' => now()->addMonths(11),
        ]);

        // Filter: service_type = amc_contract
        $response = $this->actingAs($admin, 'admin')->get(route('admin.bookings.index', [
            'service_type' => 'amc_contract',
        ]));
        $response->assertStatus(200);
        $response->assertSee($amcBooking->booking_number);
        $response->assertSee('🏢 AMC Contract');

        // Filter: running AMC contracts
        $responseRunning = $this->actingAs($admin, 'admin')->get(route('admin.bookings.index', [
            'service_type' => 'amc_contract',
            'amc_status' => 'running',
        ]));
        $responseRunning->assertStatus(200);
        $responseRunning->assertSee('Running Contract');
    }

    public function test_admin_can_activate_amc_and_auto_generate_first_recurring_invoice(): void
    {
        $admin = Admin::where('role', 'super_admin')->first() ?? Admin::first();
        $plan = AmcPlan::first();

        $booking = $this->createBooking([
            'service_type' => 'amc_contract',
            'amc_plan_id' => $plan?->id,
            'contract_status' => 'draft',
            'status' => 'assigned',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.bookings.activate-amc', $booking), [
            'amc_plan_id' => $plan?->id,
            'contract_start_date' => now()->format('Y-m-d'),
            'contract_end_date' => now()->addYear()->format('Y-m-d'),
            'contract_period_months' => 12,
            'billing_cycle' => 'monthly',
            'recurring_amount' => 15000,
            'billing_day' => 5,
            'first_bill_due_date' => now()->addDays(5)->format('Y-m-d'),
            'notes' => 'Annual contract signed for corporate headquarters.',
        ]);

        $response->assertRedirect();
        $booking->refresh();

        $this->assertEquals('active', $booking->contract_status);
        $this->assertEquals('monthly', $booking->billing_cycle);
        $this->assertEquals(15000, (float) $booking->recurring_amount);

        // Check that initial recurring invoice was created
        $this->assertDatabaseHas('amc_invoices', [
            'booking_id' => $booking->id,
            'amount' => 15000,
            'status' => 'unpaid',
        ]);
    }

    public function test_admin_can_record_amc_recurring_bill_payment(): void
    {
        $admin = Admin::where('role', 'super_admin')->first() ?? Admin::first();
        $booking = $this->createBooking([
            'service_type' => 'amc_contract',
            'contract_status' => 'active',
            'billing_cycle' => 'monthly',
            'recurring_amount' => 10000,
        ]);

        $invoice = AmcInvoice::create([
            'booking_id' => $booking->id,
            'invoice_number' => 'INV-TEST-001',
            'billing_cycle_label' => 'Monthly Cycle (Nov 2026)',
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'due_date' => now()->addDays(5),
            'amount' => 10000,
            'status' => 'issued',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.bookings.amc-bills.collect', [
            'booking' => $booking,
            'invoice' => $invoice,
        ]), [
            'payment_method' => 'bank_transfer',
            'payment_reference' => 'EBL-TRX-883921',
            'paid_at' => now()->format('Y-m-d H:i:s'),
            'notes' => 'Received via corporate bank transfer',
        ]);

        $response->assertRedirect();
        $invoice->refresh();

        $this->assertEquals('paid', $invoice->status);
        $this->assertEquals('bank_transfer', $invoice->payment_method);
        $this->assertEquals('EBL-TRX-883921', $invoice->payment_reference);
        $this->assertEquals($admin->id, $invoice->collected_by_admin_id);
    }

    public function test_customer_portal_renders_dedicated_amc_card(): void
    {
        $user = User::factory()->create();
        $plan = AmcPlan::first();

        $booking = $this->createBooking([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'service_type' => 'amc_contract',
            'amc_plan_id' => $plan?->id,
            'contract_status' => 'active',
            'contract_start_date' => now(),
            'contract_end_date' => now()->addYear(),
            'billing_cycle' => 'monthly',
            'recurring_amount' => 18000,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)->get(route('customer.bookings'));
        $response->assertStatus(200);
        $response->assertSee('🏢 AMC Contract');
        $response->assertSee('Running Contract');
        $response->assertSee('Contract Tenure');
        $response->assertSee('Recurring Billing');
        $response->assertSee('18,000.00');
    }
}
