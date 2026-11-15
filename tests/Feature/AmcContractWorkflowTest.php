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

    public function test_crew_can_record_payment_collection_when_completing_amc_shift(): void
    {
        $partner = $this->createPartner(['phone' => '01799887766']);
        $user = User::factory()->create(['role' => 'customer']);
        $plan = AmcPlan::first();

        $booking = $this->createAmcBooking($user, $plan, [
            'status' => 'in_progress',
            'contract_status' => 'active',
            'partner_id' => $partner->id,
            'partner_job_status' => 'accepted',
            'amc_billing_type' => 'per_shift',
            'payout_per_shift' => 600.00,
        ]);

        $booking->generateAmcShifts();
        $booking->reassignAmcPartner($partner->id);

        $shift = $booking->amcShifts()->first();
        $this->assertNotNull($shift);

        // Crew completes shift and records cash collection from client
        $response = $this->actingAs($partner, 'partner')->post(route('partner.jobs.amc-shifts.complete', [$booking, $shift]), [
            'completion_notes' => 'Routine monthly inspection performed.',
            'payment_collected' => 'yes',
            'collected_amount' => 1500.00,
            'payment_method' => 'Cash',
            'payment_reference' => 'Cash receipt #101',
        ]);

        $response->assertSessionHas('success');
        $shift->refresh();

        $this->assertEquals('completed', $shift->status);
        $this->assertEquals('crew', $shift->payment_collected_by);
        $this->assertEquals(1500.00, (float) $shift->collected_amount);
        $this->assertEquals('Cash', $shift->payment_method);
        $this->assertEquals('Cash receipt #101', $shift->payment_reference);
        $this->assertEquals('payable', $shift->payout_status);
        $this->assertEquals(600.00, (float) $shift->payout_amount);

        // Partner cash collected should now include 1500.00
        $partner->refresh();
        $this->assertGreaterThanOrEqual(1500.00, $partner->cash_collected_by_crew);
    }

    public function test_admin_can_record_direct_office_payment_for_amc_contract(): void
    {
        $admin = Admin::first();
        $partner = $this->createPartner(['phone' => '01711224455']);
        $user = User::factory()->create(['role' => 'customer']);
        $plan = AmcPlan::first();

        $booking = $this->createAmcBooking($user, $plan, [
            'status' => 'in_progress',
            'contract_status' => 'active',
            'partner_id' => $partner->id,
            'partner_job_status' => 'accepted',
            'amc_billing_type' => 'monthly',
            'final_cost' => 12000.00,
            'payout_per_shift' => 500.00,
        ]);

        $booking->generateAmcShifts();
        $shift = $booking->amcShifts()->first();
        $shift->update([
            'status' => 'completed',
            'payout_amount' => 500.00,
            'payout_status' => 'pending_client_payment',
        ]);

        // Admin records direct office payment
        $response = $this->actingAs($admin, 'admin')->post(route('admin.bookings.amc-office-payment.store', $booking), [
            'amount' => 4000.00,
            'payment_method' => 'Bank Transfer',
            'payment_reference' => 'Chq #998210',
            'paid_at' => now()->toDateString(),
            'notes' => 'Received first tranche advance at office.',
        ]);

        $response->assertSessionHas('success');
        $booking->refresh();
        $shift->refresh();

        $this->assertEquals(4000.00, $booking->amc_office_collected);
        $this->assertEquals(4000.00, $booking->amc_total_paid);
        $this->assertEquals(8000.00, $booking->amc_remaining_due);
        $this->assertEquals('payable', $shift->payout_status);

        // Verify customer view renders the updated financials
        $clientPage = $this->actingAs($user, 'web')->get(route('customer.bookings.show', $booking->booking_number));
        $clientPage->assertStatus(200);
        $clientPage->assertSee('Contract Budget &amp; Payment Status', false);
        $clientPage->assertSee('4,000.00');
        $clientPage->assertSee('8,000.00');
    }

    public function test_crew_dashboard_renders_today_schedule_upcoming_3_days_active_amc_and_recent_work(): void
    {
        $partner = $this->createPartner(['company_name' => 'FastCare Facility Team']);
        $user = User::factory()->create();
        $plan = AmcPlan::first();

        // 1. Active AMC booking
        $amcBooking = $this->createAmcBooking($user, $plan, [
            'status' => 'in_progress',
            'contract_status' => 'active',
            'partner_id' => $partner->id,
            'partner_job_status' => 'accepted',
            'amc_billing_type' => 'monthly',
            'payout_per_shift' => 600.00,
        ]);
        $amcBooking->generateAmcShifts();

        // 2. Schedule one shift for Today
        $todayShift = $amcBooking->amcShifts()->first();
        $todayShift->update([
            'scheduled_date' => now()->toDateString(),
            'scheduled_time_slot' => 'Morning (9:00 AM - 12:00 PM)',
            'status' => 'scheduled',
            'payout_amount' => 600.00,
        ]);

        // 3. Schedule another shift for Tomorrow (within next 3 days)
        $tomorrowShift = $amcBooking->amcShifts()->skip(1)->first();
        $tomorrowShift->update([
            'scheduled_date' => now()->addDay()->toDateString(),
            'scheduled_time_slot' => 'Afternoon (2:00 PM - 5:00 PM)',
            'status' => 'scheduled',
            'payout_amount' => 600.00,
        ]);

        // 4. Schedule a shift 10 days away (outside next 3 days)
        $farShift = $amcBooking->amcShifts()->skip(2)->first();
        $farShift->update([
            'scheduled_date' => now()->addDays(10)->toDateString(),
            'status' => 'scheduled',
        ]);

        // 5. Complete a shift today (in recent work last 3 days)
        $completedShift = $amcBooking->amcShifts()->skip(3)->first();
        $completedShift->update([
            'status' => 'completed',
            'completed_at' => now(),
            'payout_amount' => 600.00,
        ]);

        $response = $this->actingAs($partner, 'partner')->get(route('partner.dashboard'));
        $response->assertStatus(200);

        // Assert Today's Schedule section appears
        $response->assertSee("Today's Schedule", false);
        $response->assertSee('1 Tasks Today');
        $response->assertSee('Shift #'.$todayShift->shift_number);

        // Assert Upcoming Schedule (Next 3 Days) section appears
        $response->assertSee('Upcoming Schedule (Next 3 Days)');
        $response->assertSee('Tomorrow');
        $response->assertSee('Shift #'.$tomorrowShift->shift_number);
        // Shift 10 days away should NOT be in the 3-day schedule section
        $response->assertDontSee('Shift #'.$farShift->shift_number);

        // Assert Active Work section shows the AMC contract
        $response->assertSee('Active Work');
        $response->assertSee('AMC CONTRACT');
        $response->assertSee($amcBooking->booking_number);

        // Assert Recent Work (Last 3 Days) section appears
        $response->assertSee('Recent Work (Last 3 Days)');
        $response->assertSee('Shift #'.$completedShift->shift_number);
        $response->assertSee('View All History');
    }
}
