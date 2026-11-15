<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AmcPlan;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AmcContractWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    protected function createPartner(array $attributes = []): Partner
    {
        return Partner::create(array_merge([
            'company_name' => 'CoolTech Engineering',
            'contact_person' => 'Mohammad Rafiq',
            'phone' => '01799998888',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC & Plumbing',
            'technicians_count' => '5-15',
            'coverage_areas' => 'Dhaka',
            'commission_rate' => 15.00,
            'payout_terms' => 'bi_weekly',
        ], $attributes));
    }

    protected function createAmcBooking(User $user, AmcPlan $plan, array $attributes = []): Booking
    {
        return Booking::create(array_merge([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_phone' => '01711223344',
            'customer_email' => $user->email,
            'service_type' => 'amc_contract',
            'amc_plan_id' => $plan->id,
            'status' => 'pending_review',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'House 10, Road 11',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => '10:00 AM - 12:00 PM',
            'urgency' => 'standard',
            'problem_description' => 'Regular annual facility maintenance contract.',
            'contract_status' => 'pending_activation',
        ], $attributes));
    }

    public function test_amc_contract_booking_generates_recurring_shifts_and_assigns_default_partner(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $partner = $this->createPartner();
        $plan = AmcPlan::first();

        // 1. Create an AMC booking
        $booking = $this->createAmcBooking($user, $plan);

        $this->assertTrue($booking->is_amc);
        $this->assertCount(0, $booking->amcShifts);

        // 2. Generate shifts
        $count = $booking->generateAmcShifts();
        $this->assertGreaterThan(0, $count);
        $this->assertEquals($count, $booking->amcShifts()->count());

        // 3. Assign partner to the booking & propagate to pending shifts
        $booking->reassignAmcPartner($partner->id);
        $booking->refresh();

        $this->assertEquals($partner->id, $booking->partner_id);
        $this->assertEquals($count, $booking->amcShifts()->where('partner_id', $partner->id)->count());
    }

    public function test_customer_can_request_amc_deal_cancellation_and_admin_approves(): void
    {
        $admin = Admin::first();
        $user = User::factory()->create(['role' => 'customer']);
        $partner = $this->createPartner(['phone' => '01711112222']);
        $plan = AmcPlan::first();

        $booking = $this->createAmcBooking($user, $plan, [
            'status' => 'in_progress',
            'contract_status' => 'active',
            'partner_id' => $partner->id,
        ]);

        $booking->generateAmcShifts();
        $booking->reassignAmcPartner($partner->id);

        // Customer posts cancellation request
        $response = $this->actingAs($user)->post(route('customer.bookings.amc-cancel-request', $booking->booking_number), [
            'cancellation_reason' => 'Relocating company to a different city next month.',
        ]);

        $response->assertSessionHas('success');
        $booking->refresh();

        $this->assertTrue($booking->cancellation_requested);
        $this->assertEquals('pending', $booking->cancellation_review_status);
        $this->assertEquals('Relocating company to a different city next month.', $booking->cancellation_request_reason);

        // Admin reviews and approves cancellation
        $reviewResponse = $this->actingAs($admin, 'admin')->post(route('admin.bookings.amc-cancellation.review', $booking), [
            'action' => 'approve',
            'admin_notes' => 'Contract terminated per customer request with pro-rata settlement.',
        ]);

        $reviewResponse->assertSessionHas('success');
        $booking->refresh();

        $this->assertEquals('approved', $booking->cancellation_review_status);
        $this->assertEquals('cancelled', $booking->contract_status);
        $this->assertEquals('cancelled', $booking->status);

        // All scheduled shifts must now be marked cancelled
        $this->assertEquals(0, $booking->amcShifts()->where('status', 'scheduled')->count());
        $this->assertGreaterThan(0, $booking->amcShifts()->where('status', 'cancelled')->count());
    }

    public function test_partner_crew_can_complete_routine_shift_and_relinquish_amc_assignment(): void
    {
        $partner = $this->createPartner(['phone' => '01733334444']);
        $user = User::factory()->create(['role' => 'customer']);
        $plan = AmcPlan::first();

        $booking = $this->createAmcBooking($user, $plan, [
            'status' => 'in_progress',
            'contract_status' => 'active',
            'partner_id' => $partner->id,
            'partner_job_status' => 'accepted',
        ]);

        $booking->generateAmcShifts();
        $booking->reassignAmcPartner($partner->id);

        $shift = $booking->amcShifts()->first();
        $this->assertNotNull($shift);

        // 1. Crew completes a shift
        $response = $this->actingAs($partner, 'partner')->post(route('partner.jobs.amc-shifts.complete', [$booking, $shift]), [
            'completion_notes' => 'All filters replaced and system pressure checked. Normal operation.',
        ]);

        $response->assertSessionHas('success');
        $shift->refresh();

        $this->assertEquals('completed', $shift->status);
        $this->assertEquals('All filters replaced and system pressure checked. Normal operation.', $shift->completion_notes);

        // 2. Crew relinquishes AMC contract assignment
        $relinquishResponse = $this->actingAs($partner, 'partner')->post(route('partner.jobs.amc-cancel-deal', $booking), [
            'crew_cancellation_reason' => 'Shortage of dedicated staff for monthly recurring visits.',
        ]);

        $relinquishResponse->assertSessionHas('success');
        $booking->refresh();

        $this->assertEquals('declined', $booking->partner_job_status);
        $this->assertNotNull($booking->crew_cancellation_reason);

        // Remaining pending shifts should now require reassignment
        $remainingScheduled = $booking->amcShifts()->where('status', 'scheduled')->count();
        $this->assertEquals(0, $remainingScheduled);

        $reassignNeeded = $booking->amcShifts()->where('status', 'reassignment_needed')->count();
        $this->assertGreaterThan(0, $reassignNeeded);
    }

    public function test_customer_can_post_notes_for_amc_and_admin_can_reply(): void
    {
        $admin = Admin::first();
        $user = User::factory()->create(['role' => 'customer']);
        $plan = AmcPlan::first();

        $booking = $this->createAmcBooking($user, $plan, [
            'status' => 'in_progress',
            'contract_status' => 'active',
        ]);

        // Customer posts note
        $custResponse = $this->actingAs($user)->post(route('customer.bookings.amc-notes', $booking->booking_number), [
            'note' => 'Please ask the crew to bring ladder for 3rd floor AC units.',
        ]);

        $custResponse->assertSessionHas('success');
        $this->assertDatabaseHas('amc_notes', [
            'booking_id' => $booking->id,
            'sender_type' => 'customer',
            'note' => 'Please ask the crew to bring ladder for 3rd floor AC units.',
        ]);

        // Admin replies
        $adminResponse = $this->actingAs($admin, 'admin')->post(route('admin.bookings.amc-notes.store', $booking), [
            'note' => 'Noted. Field team has been equipped with a 12ft aluminum ladder.',
        ]);

        $adminResponse->assertSessionHas('success');
        $this->assertDatabaseHas('amc_notes', [
            'booking_id' => $booking->id,
            'sender_type' => 'admin',
            'note' => 'Noted. Field team has been equipped with a 12ft aluminum ladder.',
        ]);
    }

    public function test_admin_can_activate_amc_contract_with_automatic_default_dates(): void
    {
        $admin = Admin::first();
        $user = User::factory()->create(['role' => 'customer']);
        $plan = AmcPlan::first();

        $booking = $this->createAmcBooking($user, $plan, [
            'status' => 'pending_review',
        ]);

        // Submit activation without manual start/end dates (relying on smart auto-fill)
        $response = $this->actingAs($admin, 'admin')->post(route('admin.bookings.activate-amc', $booking), [
            'amc_plan_id' => $plan->id,
            'billing_cycle' => 'quarterly',
            'contract_cost' => 12000,
        ]);

        $response->assertSessionHas('success');
        $booking->refresh();

        $this->assertEquals('active', $booking->contract_status);
        $this->assertEquals('amc_contract', $booking->service_type);
        $this->assertNotNull($booking->contract_start_date);
        $this->assertNotNull($booking->contract_end_date);
        $this->assertEquals(12000, $booking->final_cost);
    }

    public function test_customer_can_select_contract_tenure_during_amc_booking(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $plan = AmcPlan::first();

        // 1. Booking page renders contract tenure options
        $pageResponse = $this->actingAs($user)->get(route('booking.create', ['plan' => $plan->slug]));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Select Contract Tenure');
        $pageResponse->assertSee('3 Months');
        $pageResponse->assertSee('6 Months');
        $pageResponse->assertSee('1 Year');
        $pageResponse->assertSee('2 Years');
        $pageResponse->assertSee('3 Years');

        // 2. Submit booking with 6 Months tenure
        $startDate = now()->addDay()->toDateString();
        $submitResponse = $this->actingAs($user)->post(route('booking.store'), [
            'service_type' => 'amc_contract',
            'amc_plan_id' => $plan->id,
            'contract_tenure' => '6 Months',
            'customer_name' => 'Corporate Facility Manager',
            'customer_phone' => '01711002233',
            'property_type' => 'Corporate Office',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'Floor 8, Tower 12, Gulshan Avenue',
            'preferred_date' => $startDate,
            'preferred_time_slot' => 'Morning (9:00 AM - 12:00 PM)',
            'urgency' => 'scheduled',
            'problem_description' => 'Comprehensive HVAC and electrical corporate AMC routine maintenance.',
        ]);

        $submitResponse->assertSessionHas('success');
        $booking = Booking::where('customer_phone', '01711002233')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('amc_contract', $booking->service_type);
        $this->assertEquals('6 Months', $booking->contract_tenure);
        $this->assertEquals($startDate, $booking->contract_start_date->toDateString());
        $this->assertEquals(now()->addDay()->addMonths(6)->toDateString(), $booking->contract_end_date->toDateString());

        // 3. Customer portal reflects contract tenure
        $portalResponse = $this->actingAs($user)->get(route('customer.bookings'));
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee('Contract Tenure');
        $portalResponse->assertSee('6 Months');
    }
}
