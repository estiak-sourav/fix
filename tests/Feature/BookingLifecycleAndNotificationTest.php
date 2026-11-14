<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\Partner;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingLifecycleAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_complete_booking_crew_lifecycle_pricing_and_notifications(): void
    {
        $admin = Admin::first();
        $user = User::first();
        $service = Service::first();

        $partner1 = Partner::create([
            'company_name' => 'Fast Electricians Ltd',
            'contact_person' => 'Rahim Ahmed',
            'phone' => '01711000001',
            'email' => 'rahim@fastelectric.com',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'coverage_areas' => 'Dhaka, Banani',
            'trade_domain' => 'Electrical',
            'earning_model' => 'commission',
            'payout_rate' => 80.00,
        ]);

        $partner2 = Partner::create([
            'company_name' => 'Apex Engineering Corp',
            'contact_person' => 'Karim Ullah',
            'phone' => '01811000002',
            'email' => 'karim@apex.com',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'coverage_areas' => 'Dhaka, Banani',
            'trade_domain' => 'Electrical',
            'earning_model' => 'commission',
            'payout_rate' => 80.00,
        ]);

        // 1. User requests booking -> shows pending in user panel
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber('Dhaka', 'Dhaka'),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $user->name,
            'customer_phone' => '01711223344',
            'customer_email' => $user->email,
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Banani',
            'street_address' => 'Road 11, Block D',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Circuit breaker tripping constantly',
            'status' => 'pending_review',
        ]);

        $userPanel = $this->actingAs($user, 'web')->get('/my-bookings');
        $userPanel->assertStatus(200);
        $userPanel->assertSee('Pending');

        // 2. Admin accepts booking with "pricing_mode = later" -> Status is accepted
        $this->actingAs($admin, 'admin')->post(route('admin.bookings.accept', $booking), [
            'pricing_mode' => 'later',
            'partner_id' => $partner1->id,
        ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->status);
        $this->assertEquals('assigned', $booking->partner_job_status);
        $this->assertEquals($partner1->id, $booking->partner_id);

        // User panel still displays Accepted because crew has not yet accepted
        $userPanel = $this->actingAs($user, 'web')->get('/my-bookings');
        $userPanel->assertStatus(200);
        $userPanel->assertSee('Accepted');

        // 3. Crew 1 declines/rejects the assignment -> CMS receives notification & status remains accepted for reassignment
        $this->actingAs($partner1, 'partner')
            ->post(route('partner.jobs.decline', $booking), [
                'decline_reason' => 'Technician on emergency leave',
            ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('declined', $booking->partner_job_status);
        $this->assertEquals('accepted', $booking->status);

        // Check CMS emergency notification created for decline
        $declineNotif = AdminNotification::where('booking_id', $booking->id)
            ->where('type', 'emergency_alert')
            ->latest()
            ->first();
        $this->assertNotNull($declineNotif);
        $this->assertStringContainsString('declined', strtolower($declineNotif->title));

        // 4. Admin reassigns crew (Partner 2)
        $this->actingAs($admin, 'admin')->put(route('admin.bookings.update', $booking), [
            'status' => 'accepted',
            'partner_id' => $partner2->id,
            'street_address' => $booking->street_address,
        ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals($partner2->id, $booking->partner_id);
        $this->assertEquals('assigned', $booking->partner_job_status);

        // 5. Pricing Logic: Admin set price later -> Crew accepts assignment, price will be set before starting work
        $acceptResponse = $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.accept', $booking));
        $acceptResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->partner_job_status);
        $this->assertEquals('assigned', $booking->status);
        $this->assertTrue($booking->is_price_set_later);

        $acceptNotif = AdminNotification::where('booking_id', $booking->id)
            ->where('title', 'like', '%Crew Accepted%')
            ->latest()
            ->first();
        $this->assertNotNull($acceptNotif);

        // User panel verifies status is Assigned
        $userPanel = $this->actingAs($user, 'web')->get('/my-bookings');
        $userPanel->assertStatus(200);
        $userPanel->assertSee('Assigned');

        // 6. Crew starts journey -> Status becomes 'on_the_way', User sees crew on the way, CMS notified
        $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.status', $booking), [
                'partner_job_status' => 'on_the_way',
            ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('on_the_way', $booking->partner_job_status);
        $this->assertEquals('on_the_way', $booking->status);

        $journeyNotif = AdminNotification::where('booking_id', $booking->id)
            ->where('title', 'like', '%Started Journey%')
            ->latest()
            ->first();
        $this->assertNotNull($journeyNotif);

        $userPanel = $this->actingAs($user, 'web')->get('/my-bookings');
        $userPanel->assertStatus(200);
        $userPanel->assertSee('On the Way');
        $userPanel->assertSee('Crew is on the way');

        // 7. Work starts on site: Since price was set later, crew MUST set agreed price before starting work
        $failStart = $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.status', $booking), [
                'partner_job_status' => 'in_progress',
            ]);
        $failStart->assertSessionHasErrors('agreed_price');

        // Crew sets agreed price and starts work
        $startWorkResponse = $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.status', $booking), [
                'partner_job_status' => 'in_progress',
                'agreed_price' => 2500,
            ]);
        $startWorkResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals('in_progress', $booking->partner_job_status);
        $this->assertEquals('in_progress', $booking->status);
        $this->assertEquals(2500.00, (float) $booking->final_cost);
        $this->assertNull($booking->estimated_cost);

        // CMS notified of work started
        $workStartedNotif = AdminNotification::where('booking_id', $booking->id)
            ->where('title', 'like', '%Work Started%')
            ->latest()
            ->first();
        $this->assertNotNull($workStartedNotif);

        // User panel verifies status is 'Working'
        $userPanel = $this->actingAs($user, 'web')->get('/my-bookings');
        $userPanel->assertStatus(200);
        $userPanel->assertSee('Working');

        // 8. Crew reviews and updates price mid-work -> CMS notified of price change
        $updatePriceResponse = $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.price', $booking), [
                'agreed_price' => 3200,
                'price_notes' => 'Replaced burnt fuse holder with client consent',
            ]);
        $updatePriceResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals(3200, (float) $booking->estimated_cost);

        $priceRevisedNotif = AdminNotification::where('booking_id', $booking->id)
            ->where('title', 'like', '%Price Revised%')
            ->latest()
            ->first();
        $this->assertNotNull($priceRevisedNotif);
        $this->assertStringContainsString('3,200', $priceRevisedNotif->message);

        // 9. CMS Notification UX: Tapping notification item marks as read and returns JSON
        $this->assertFalse($priceRevisedNotif->is_read);

        $ajaxReadResponse = $this->actingAs($admin, 'admin')
            ->postJson(route('admin.notifications.read', $priceRevisedNotif));

        $ajaxReadResponse->assertStatus(200);
        $ajaxReadResponse->assertJson([
            'success' => true,
            'notification_id' => $priceRevisedNotif->id,
        ]);

        $priceRevisedNotif->refresh();
        $this->assertTrue($priceRevisedNotif->is_read);

        // 10. CMS notification center view renders cards with data attributes for instant modal details
        $cmsNotifs = $this->actingAs($admin, 'admin')->get(route('admin.notifications.index'));
        $cmsNotifs->assertStatus(200);
        $cmsNotifs->assertSee('notificationDetailsModal');
        $cmsNotifs->assertSee('handleNotificationClick');
        $cmsNotifs->assertSee('modalBookingSection');
        // Assert separate "✓ Read" button form is removed from items
        $cmsNotifs->assertDontSee('title="Mark as Read"');
    }

    public function test_crew_payout_methods_withdrawal_requests_balance_disbursal_and_filtering(): void
    {
        $admin = Admin::first();
        $user = User::first();
        $service = Service::first();

        // Partner A: Electrical in Gulshan
        $partnerA = Partner::create([
            'company_name' => 'Gulshan Spark Electricals',
            'contact_person' => 'Jamil Hasan',
            'phone' => '01722000001',
            'email' => 'jamil@spark.com',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'coverage_areas' => 'Gulshan, Banani',
            'trade_domain' => 'Electrical',
            'earning_model' => 'commission',
            'commission_rate' => 80.00,
            'payout_rate' => 80.00,
        ]);

        // Partner B: Plumbing in Dhanmondi
        $partnerB = Partner::create([
            'company_name' => 'Dhanmondi Pipes & Plumbing',
            'contact_person' => 'Salam Kabir',
            'phone' => '01822000002',
            'email' => 'salam@pipes.com',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'individual',
            'coverage_areas' => 'Dhanmondi, Mohammadpur',
            'trade_domain' => 'Plumbing',
            'earning_model' => 'commission',
            'commission_rate' => 80.00,
            'payout_rate' => 80.00,
        ]);

        // 1. Crew can add multiple payment methods with Personal / Agent number type
        $addBkash = $this->actingAs($partnerA, 'partner')->post(route('partner.payment-methods.store'), [
            'method_type' => 'bkash',
            'number_type' => 'agent',
            'account_number' => '01722000001',
            'account_name' => 'Gulshan Spark Agent Counter',
            'is_default' => 1,
        ]);
        $addBkash->assertRedirect();

        $addNagad = $this->actingAs($partnerA, 'partner')->post(route('partner.payment-methods.store'), [
            'method_type' => 'nagad',
            'number_type' => 'personal',
            'account_number' => '01922000001',
            'account_name' => 'Jamil Personal',
            'is_default' => 0,
        ]);
        $addNagad->assertRedirect();

        $this->assertDatabaseHas('partner_payment_methods', [
            'partner_id' => $partnerA->id,
            'method_type' => 'bkash',
            'number_type' => 'agent',
        ]);
        $this->assertDatabaseHas('partner_payment_methods', [
            'partner_id' => $partnerA->id,
            'method_type' => 'nagad',
            'number_type' => 'personal',
        ]);

        // 2. Complete a booking for partnerA to earn balance (৳2,000 final cost, 80% = ৳1,600)
        $completedBooking = Booking::create([
            'booking_number' => Booking::generateBookingNumber('Dhaka', 'Dhaka'),
            'user_id' => $user->id,
            'partner_id' => $partnerA->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $user->name,
            'customer_phone' => '01711223344',
            'customer_email' => $user->email,
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'Road 1, Block A',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Electrical system repair',
            'final_cost' => 2000,
            'status' => 'completed',
            'partner_job_status' => 'completed',
            'completed_at' => now(),
        ]);

        $partnerA->refresh();
        $this->assertEquals(1600.0, $partnerA->total_earned);
        $this->assertEquals(0.0, $partnerA->total_paid);
        $this->assertEquals(1600.0, $partnerA->payable_balance);

        // 3. Crew requests payout withdrawal for custom amount (e.g. ৳600 of ৳1,600)
        $agentMethod = $partnerA->paymentMethods()->where('method_type', 'bkash')->first();
        $requestPayoutRes = $this->actingAs($partnerA, 'partner')->post(route('partner.payouts.request'), [
            'amount' => 600,
            'partner_payment_method_id' => $agentMethod->id,
            'partner_notes' => 'Please disburse to agent wallet',
        ]);
        $requestPayoutRes->assertRedirect(route('partner.payouts.index'));

        // Admin and Crew are notified of payout request
        $this->assertDatabaseHas('partner_payouts', [
            'partner_id' => $partnerA->id,
            'amount' => 600.00,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'payout_request',
        ]);
        $this->assertDatabaseHas('partner_notifications', [
            'partner_id' => $partnerA->id,
            'type' => 'payout_requested',
        ]);

        // 4. Admin Dashboard displays the pending payout request and top payable crews
        $dashRes = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Pending Crew Payout Requests');
        $dashRes->assertSee('Gulshan Spark Electricals');
        $dashRes->assertSee('Top Outstanding Payable Crews');
        $dashRes->assertSee('1,600.00');

        // 5. Admin visits Crew details page and disburses a partial payment of ৳500 from the balance
        $disburseRes = $this->actingAs($admin, 'admin')->post(route('admin.partners.payouts.disburse', $partnerA), [
            'amount' => 500,
            'partner_payment_method_id' => $agentMethod->id,
            'transaction_reference' => 'BKASH-TRX-998877',
            'admin_notes' => 'Partial mid-week balance payment',
        ]);
        $disburseRes->assertRedirect(route('admin.partners.show', $partnerA));

        $partnerA->refresh();
        $this->assertEquals(500.0, $partnerA->total_paid);
        $this->assertEquals(1100.0, $partnerA->payable_balance); // 1600 - 500 = 1100 remaining unpaid

        // Check Partner Notification for payout disbursed
        $this->assertDatabaseHas('partner_notifications', [
            'partner_id' => $partnerA->id,
            'type' => 'payout_disbursed',
        ]);

        // 6. Strict Crew Dropdown Filter:
        // Booking in Gulshan with Electrical service
        $testBooking = Booking::create([
            'booking_number' => Booking::generateBookingNumber('Dhaka', 'Dhaka'),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => 'Corporate Client',
            'customer_phone' => '01799887766',
            'customer_email' => 'client@corporate.com',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'Gulshan Avenue',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Electrical line failure',
            'status' => 'pending_review',
        ]);

        $bookingShowRes = $this->actingAs($admin, 'admin')->get(route('admin.bookings.show', $testBooking));
        $bookingShowRes->assertStatus(200);

        // Partner A (Gulshan + Electrical) MUST appear in the dropdown
        $bookingShowRes->assertSee('Gulshan Spark Electricals');
        // Partner B (Dhanmondi + Plumbing) MUST NOT appear in the dropdown
        $bookingShowRes->assertDontSee('Dhanmondi Pipes & Plumbing');
    }

    public function test_customer_can_cancel_booking_before_admin_accepts(): void
    {
        $user = User::first();
        $service = Service::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber('Dhaka', 'Dhaka'),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '01712345678',
            'customer_email' => $user->email,
            'property_type' => 'residential',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Mirpur',
            'street_address' => 'Mirpur 10',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'AC not blowing cold air',
            'status' => 'pending_review',
        ]);

        $this->assertTrue($booking->canBeCancelledByCustomer());

        // View in customer portal before acceptance: Cancel Request is visible
        $portalRes = $this->actingAs($user, 'web')->get(route('customer.bookings'));
        $portalRes->assertStatus(200);
        $portalRes->assertSee('Cancel Request');

        $detailRes = $this->actingAs($user, 'web')->get(route('customer.bookings.show', $booking->booking_number));
        $detailRes->assertStatus(200);
        $detailRes->assertSee('Cancel Request');
        $detailRes->assertSee('Need to cancel this service request?');

        // Customer cancels the request
        $cancelRes = $this->actingAs($user, 'web')->post(route('customer.bookings.cancel', $booking->booking_number), [
            'cancellation_reason' => 'Schedule conflict',
        ]);
        $cancelRes->assertRedirect(route('customer.bookings'));
        $cancelRes->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertNotNull($booking->cancelled_at);
        $this->assertFalse($booking->canBeCancelledByCustomer());
        $this->assertEquals('Schedule conflict', $booking->cancellation_reason);

        // Check Admin Notification
        $this->assertDatabaseHas('admin_notifications', [
            'booking_id' => $booking->id,
            'type' => 'order_status',
        ]);

        // Cancelled section visible in customer portal
        $afterCancelRes = $this->actingAs($user, 'web')->get(route('customer.bookings'));
        $afterCancelRes->assertStatus(200);
        $afterCancelRes->assertSee('Cancelled Requests');
        $afterCancelRes->assertSee('Schedule conflict');
    }

    public function test_customer_cannot_cancel_booking_after_admin_accepts_only_admin_can_cancel(): void
    {
        $admin = Admin::first();
        $user = User::first();
        $service = Service::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber('Dhaka', 'Dhaka'),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '01712345678',
            'customer_email' => $user->email,
            'property_type' => 'residential',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Uttara',
            'street_address' => 'Sector 3',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time_slot' => 'Afternoon',
            'urgency' => 'standard',
            'problem_description' => 'Water leakage in bathroom pipe',
            'status' => 'pending_review',
        ]);

        // Admin accepts the work order
        $acceptRes = $this->from(route('admin.bookings.show', $booking))
            ->actingAs($admin, 'admin')
            ->post(route('admin.bookings.update-status', $booking), [
                'status' => 'accepted',
                'cost_type' => 'final',
                'cost_amount' => 1500,
                'assigned_team' => 'Rapid Response Team',
                'comment' => 'Confirmed with client via call and accepted.',
            ]);
        $acceptRes->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();
        $this->assertEquals('accepted', $booking->status);
        $this->assertNotNull($booking->accepted_at);
        $this->assertFalse($booking->canBeCancelledByCustomer());

        // In Customer Portal: Cancel button must NOT appear
        $portalRes = $this->actingAs($user, 'web')->get(route('customer.bookings'));
        $portalRes->assertStatus(200);
        $portalRes->assertDontSee('Cancel Request');

        $detailRes = $this->actingAs($user, 'web')->get(route('customer.bookings.show', $booking->booking_number));
        $detailRes->assertStatus(200);
        $detailRes->assertDontSee('Cancel Request');
        $detailRes->assertSee('Work Order Accepted by Operations');

        // Customer attempts to cancel via POST request -> MUST be rejected
        $customerCancelAttempt = $this->actingAs($user, 'web')->post(route('customer.bookings.cancel', $booking->booking_number), [
            'cancellation_reason' => 'I want to cancel now',
        ]);
        $customerCancelAttempt->assertSessionHas('error');

        $booking->refresh();
        $this->assertEquals('accepted', $booking->status); // Status is NOT cancelled

        // However, Admin CAN cancel the accepted work order
        $adminCancelRes = $this->actingAs($admin, 'admin')->post(route('admin.bookings.cancel', $booking), [
            'cancellation_reason' => 'Cancelled by Operations Headquarters after customer phone request.',
        ]);
        $adminCancelRes->assertRedirect();
        $adminCancelRes->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertNotNull($booking->cancelled_at);
    }

    public function test_partner_and_crew_details_hidden_from_client_portal_and_visible_in_admin_portal(): void
    {
        $admin = Admin::first();
        $user = User::first();
        $service = Service::first();

        $partner = Partner::create([
            'company_name' => 'Delta Electro-Mechanical Ltd',
            'contact_person' => 'Subcontractor Rahim',
            'phone' => '01799001122',
            'email' => 'rahim@delta-electro.com',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'coverage_areas' => 'Dhaka',
            'trade_domain' => 'Electrical',
            'earning_model' => 'commission',
            'payout_rate' => 80.00,
        ]);

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber('Dhaka', 'Dhaka'),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '01712345678',
            'customer_email' => $user->email,
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Banani',
            'street_address' => 'Banani Road 11',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Substation maintenance',
            'status' => 'assigned',
            'partner_id' => $partner->id,
            'assigned_team' => "{$partner->company_name} ({$partner->contact_person})",
            'partner_job_status' => 'accepted',
            'arrival_window' => '09:00 AM - 12:00 PM',
        ]);

        // Add status logs that explicitly mention the partner
        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => 'pending_review',
            'to_status' => 'assigned',
            'comment' => "Partner '{$partner->company_name}' accepted the job assignment.",
            'is_customer_visible' => true,
        ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'from_status' => 'assigned',
            'to_status' => 'on_the_way',
            'comment' => "Technician/Crew from '{$partner->company_name}' is currently on the way to the service address.",
            'is_customer_visible' => true,
        ]);

        // 1. Client user visits /my-bookings
        $clientIndex = $this->actingAs($user, 'web')->get(route('customer.bookings'));
        $clientIndex->assertOk();
        $clientIndex->assertDontSee('Delta Electro-Mechanical Ltd');
        $clientIndex->assertDontSee('Subcontractor Rahim');
        $clientIndex->assertDontSee('01799001122');
        $clientIndex->assertDontSee('rahim@delta-electro.com');

        // 2. Client user visits /my-bookings/{bookingNumber}
        $clientShow = $this->actingAs($user, 'web')->get(route('customer.bookings.show', $booking->booking_number));
        $clientShow->assertOk();
        $clientShow->assertDontSee('Delta Electro-Mechanical Ltd');
        $clientShow->assertDontSee('Subcontractor Rahim');
        $clientShow->assertDontSee('01799001122');
        $clientShow->assertDontSee('rahim@delta-electro.com');
        $clientShow->assertDontSee('Awaiting Crew Acceptance');
        $clientShow->assertDontSee('Crew Assigned & Confirmed');
        // Sanitized comments are rendered
        $clientShow->assertSee('Work order confirmed and scheduled for service.');
        $clientShow->assertSee('Technician team is on the way to the service address.');

        // 3. Admin visits /admin/bookings/{id} -> Partner & crew details ARE visible
        $adminShow = $this->actingAs($admin, 'admin')->get(route('admin.bookings.show', $booking));
        $adminShow->assertOk();
        $adminShow->assertSee('Delta Electro-Mechanical Ltd');
        $adminShow->assertSee('01799001122');
    }

    public function test_unreviewed_completed_service_is_prominently_visible_for_review_in_user_panel(): void
    {
        $user = User::first();
        $service = Service::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber('Dhaka', 'Dhaka'),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '01711223344',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'House 12, Road 4',
            'property_type' => 'residential',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'morning',
            'urgency' => 'standard',
            'problem_description' => 'Electrical circuit issue repaired.',
            'status' => 'completed',
            'completed_at' => now()->subDay(),
            'final_cost' => 2500,
        ]);

        // 1. Visit /my-bookings: pending review section is prominently rendered at top
        $response = $this->actingAs($user, 'web')->get(route('customer.bookings'));
        $response->assertOk();
        $response->assertSee('pendingReviewsSection', false);
        $response->assertSee('Rate Your Completed Service');
        $response->assertSee('Submit Review');
        $response->assertSee('Review Needed');
        $response->assertSee('Rate Service');

        // 2. Submit review
        $reviewResponse = $this->actingAs($user, 'web')->post(route('customer.bookings.review', $booking->booking_number), [
            'review_rating' => 5,
            'review_comment' => 'Excellent electrical repair service!',
        ]);
        $reviewResponse->assertRedirect();
        $reviewResponse->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals(5, $booking->review_rating);
        $this->assertEquals('Excellent electrical repair service!', $booking->review_comment);

        // 3. Visit /my-bookings again: pending review section is gone, reviewed rating is displayed
        $afterReview = $this->actingAs($user, 'web')->get(route('customer.bookings'));
        $afterReview->assertOk();
        $afterReview->assertDontSee('pendingReviewsSection', false);
        $afterReview->assertSee('★ 5.0');
    }
}
