<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Booking;
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

        // 5. Pricing Logic: Admin set price later -> Crew MUST set price before accepting assignment
        $failAccept = $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.accept', $booking));
        $failAccept->assertSessionHasErrors('agreed_price');

        // Crew 2 sets agreed price and accepts the assignment -> User panel now shows 'Assigned' & CMS alerted
        $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.accept', $booking), [
                'agreed_price' => 2500,
            ])
            ->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->partner_job_status);
        $this->assertEquals('assigned', $booking->status);
        $this->assertEquals(2500.00, (float) $booking->final_cost);
        $this->assertNull($booking->estimated_cost);

        $acceptNotif = AdminNotification::where('booking_id', $booking->id)
            ->where('title', 'like', '%Crew Accepted%')
            ->latest()
            ->first();
        $this->assertNotNull($acceptNotif);
        $this->assertStringContainsString('2,500', $acceptNotif->message);

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

        // 7. Work starts on site (Price was already confirmed upon acceptance)
        $startWorkResponse = $this->actingAs($partner2, 'partner')
            ->post(route('partner.jobs.status', $booking), [
                'partner_job_status' => 'in_progress',
            ]);
        $startWorkResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals('in_progress', $booking->partner_job_status);
        $this->assertEquals('in_progress', $booking->status);
        $this->assertEquals(2500.00, (float) $booking->final_cost);

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
}
