<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_legacy_booking_urls_redirect_to_booking_with_query_params(): void
    {
        // 1. /booking.html redirects to /booking
        $response = $this->get('/booking.html');
        $response->assertRedirect(route('booking.create'));

        // 2. /booking.html with query parameters preserves them
        $responseWithParams = $this->get('/booking.html?service=HVAC+%26+Air+Conditioning&property=Corporate+Office');
        $responseWithParams->assertRedirect(route('booking.create', [
            'service' => 'HVAC & Air Conditioning',
            'property' => 'Corporate Office',
        ]));

        // 3. /book-now redirects to /booking
        $bookNowResponse = $this->get('/book-now');
        $bookNowResponse->assertRedirect(route('booking.create'));
    }

    public function test_booking_page_renders_with_database_services_and_no_category_dropdown(): void
    {
        $response = $this->get('/booking');
        $response->assertStatus(200);

        // Asserts Service label and select are present
        $response->assertSee('Select Service Required');
        $response->assertSee('-- Choose Required Service --');

        // Confirms legacy confusing category selector is gone
        $response->assertDontSee('Select Service Category...');
        $response->assertDontSee('First choose a Category above');

        // Confirms active services exist in the select
        $service = Service::where('is_active', true)->first();
        $this->assertNotNull($service);
        $response->assertSee($service->title);
    }

    public function test_booking_page_preselects_service_from_query_parameter(): void
    {
        /** @var Service $targetService */
        $targetService = Service::where('is_active', true)->first();
        $this->assertNotNull($targetService);

        $response = $this->get('/booking?service='.urlencode($targetService->title));
        $response->assertStatus(200);

        // Check that the service option is selected
        $response->assertSee('selected');
        $response->assertSee($targetService->title);
    }

    public function test_instant_service_finder_on_homepage_has_no_category_field(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('Instant Service Finder & Booking', false);
        $response->assertSee('quickService');
        $response->assertDontSee('quickCategory');
        $response->assertDontSee('1. Service Category');
        $response->assertSee('1. Select Service');
        $response->assertSee('2. Property Type');
        $response->assertSee('3. Preferred Schedule');
    }

    public function test_booking_lifecycle_from_accept_to_assigned_and_working(): void
    {
        $admin = Admin::first();
        $user = User::first();
        $service = Service::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => 'Test Customer',
            'customer_phone' => '01711223344',
            'customer_email' => 'customer@example.com',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Banani',
            'street_address' => 'House 10, Road 5, Banani',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Electrical wiring inspection and troubleshooting.',
            'status' => 'pending_review',
        ]);

        // Customer sees Pending initially
        $initialResponse = $this->actingAs($user, 'web')->get('/my-bookings');
        $initialResponse->assertStatus(200);
        $initialResponse->assertSee('Pending');

        // 1. Admin accepts order -> Status becomes 'accepted'
        $this->actingAs($admin, 'admin')->post(route('admin.bookings.accept', $booking), [
            'pricing_mode' => 'price',
            'cost_amount' => 5000,
        ]);

        $booking->refresh();
        $this->assertEquals('accepted', $booking->status);

        // Verify customer tracking page shows Accepted status
        $acceptedResponse = $this->actingAs($user, 'web')->get('/my-bookings');
        $acceptedResponse->assertStatus(200);
        $acceptedResponse->assertSee('Accepted');

        // 2. Booking status is updated when assigned
        $booking->update([
            'assigned_team' => 'Delta HVAC Engineering & Industrial Solutions — Engr. Kamrul Hasan - 01711223344',
            'status' => 'assigned',
        ]);

        $assignedResponse = $this->actingAs($user, 'web')->get('/my-bookings');
        $assignedResponse->assertStatus(200);
        $assignedResponse->assertSee('Assigned');

        // 3. Crew starts journey -> on_the_way
        $booking->update([
            'status' => 'on_the_way',
            'partner_job_status' => 'on_the_way',
        ]);
        $onTheWayResponse = $this->actingAs($user, 'web')->get('/my-bookings');
        $onTheWayResponse->assertStatus(200);
        $onTheWayResponse->assertSee('On the Way');

        // 4. Crew starts work -> in_progress (Working)
        $booking->update([
            'status' => 'in_progress',
            'partner_job_status' => 'in_progress',
        ]);
        $workingResponse = $this->actingAs($user, 'web')->get('/my-bookings');
        $workingResponse->assertStatus(200);
        $workingResponse->assertSee('Working');

        // Verify customer booking details page does not expose internal contractor or crew details
        $detailsResponse = $this->actingAs($user, 'web')->get(route('customer.bookings.show', $booking->booking_number));
        $detailsResponse->assertStatus(200);
        $detailsResponse->assertDontSee('Delta HVAC Engineering');
        $detailsResponse->assertSee('Working On Site');
    }

    public function test_booking_page_has_conditional_area_sqft_field(): void
    {
        $response = $this->get('/booking');
        $response->assertStatus(200);

        // Asserts sqftFormGroup exists with conditional display none by default
        $response->assertSee('id="propertySqftRow"', false);
        $response->assertSee('id="sqftFormGroup"', false);
        $response->assertSee('display: none;', false);
        $response->assertSee('Estimated Area Size (sq ft)');
        $response->assertSee('(Optional)');
    }

    public function test_admin_booking_show_orders_operational_audit_timeline_latest_first(): void
    {
        $admin = Admin::first();
        $booking = Booking::first();

        // Add older status log
        $booking->statusLogs()->create([
            'from_status' => 'initiated',
            'to_status' => 'pending_review',
            'changed_by_admin_id' => $admin->id,
            'comment' => 'FIRST_OLDER_AUDIT_LOG_NOTE',
            'created_at' => now()->subHours(2),
        ]);

        // Add newer status log
        $booking->statusLogs()->create([
            'from_status' => 'pending_review',
            'to_status' => 'accepted',
            'changed_by_admin_id' => $admin->id,
            'comment' => 'SECOND_NEWEST_AUDIT_LOG_NOTE',
            'created_at' => now()->subMinute(),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.bookings.show', $booking));
        $response->assertStatus(200);

        $content = $response->getContent();
        $newerPosition = strpos($content, 'SECOND_NEWEST_AUDIT_LOG_NOTE');
        $olderPosition = strpos($content, 'FIRST_OLDER_AUDIT_LOG_NOTE');

        $this->assertNotFalse($newerPosition);
        $this->assertNotFalse($olderPosition);
        $this->assertLessThan($olderPosition, $newerPosition, 'Latest audit log must appear before older audit logs in the timeline.');
    }

    public function test_manage_dispatch_tab_has_radio_pricing_and_read_only_highlighted_status(): void
    {
        $admin = Admin::first();
        $booking = Booking::first();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.bookings.show', $booking));
        $response->assertStatus(200);

        // Status dropdown must NOT exist
        $response->assertDontSee('<select name="status"', false);
        $response->assertDontSee('id="workOrderStatusSelect"', false);

        // Status must be shown as read-only highlighted badge
        $response->assertSee('Work Order Status');
        $response->assertSee('Auto-calculated (Read Only)');
        $response->assertSee($booking->status_label);

        // Price agreement radio buttons and cost inputs must exist
        $response->assertSee('id="pricingModePrice_edit"', false);
        $response->assertSee('id="pricingModeLater_edit"', false);
        $response->assertSee('id="costAmountInput_edit"', false);
        $response->assertSee('id="isEstimatedCheckbox_edit"', false);
        $response->assertSee('Price Agreed Now');
        $response->assertSee('Set Price Later (Crew sets before work starts)');
    }

    public function test_admin_booking_update_handles_radio_pricing_mode_and_preserves_status(): void
    {
        $admin = Admin::first();
        $booking = Booking::first();
        $initialStatus = $booking->status;

        // 1. Update with Price Agreed Now (Estimated)
        $this->actingAs($admin, 'admin')->put(route('admin.bookings.update', $booking), [
            'pricing_mode' => 'price',
            'cost_amount' => 1250.50,
            'is_estimated' => '1',
            'cost_type' => 'estimated',
            'preferred_time_slot' => '02:00 PM - 04:00 PM',
        ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals(1250.50, (float) $booking->estimated_cost);
        $this->assertNull($booking->final_cost);
        $this->assertEquals($initialStatus, $booking->status);

        // 2. Update to Set Price Later (clears estimated and final costs)
        $this->actingAs($admin, 'admin')->put(route('admin.bookings.update', $booking), [
            'pricing_mode' => 'later',
        ])->assertRedirect();

        $booking->refresh();
        $this->assertNull($booking->estimated_cost);
        $this->assertNull($booking->final_cost);
        $this->assertEquals($initialStatus, $booking->status);
    }

    public function test_cancelled_work_order_can_be_restarted_and_reaccepted(): void
    {
        $admin = Admin::first();
        $booking = Booking::first();

        // 1. Cancel the work order
        $this->actingAs($admin, 'admin')->post(route('admin.bookings.cancel', $booking), [
            'cancellation_reason' => 'Client requested cancellation due to scheduling conflict',
        ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertNotNull($booking->cancelled_at);

        // 2. View cancelled booking page - restart controls must be visible
        $showResponse = $this->actingAs($admin, 'admin')->get(route('admin.bookings.show', $booking));
        $showResponse->assertOk();
        $showResponse->assertSee('Re-start Work Order');
        $showResponse->assertSee('id="restartWorkOrderModal"', false);

        // 3. Restart the cancelled booking
        $restartResponse = $this->actingAs($admin, 'admin')->post(route('admin.bookings.restart', $booking), [
            'restart_reason' => 'Client called back and wants to resume service',
        ]);
        $restartResponse->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();
        $this->assertEquals('pending_review', $booking->status);
        $this->assertNull($booking->cancelled_at);
        $this->assertNull($booking->cancellation_reason);
        $this->assertNull($booking->partner_id);
        $this->assertNull($booking->partner_job_status);

        // Verify status log recorded the restart
        $latestLog = $booking->statusLogs()->first();
        $this->assertEquals('cancelled', $latestLog->from_status);
        $this->assertEquals('pending_review', $latestLog->to_status);
        $this->assertStringContainsString('restarted', strtolower($latestLog->comment));

        // 4. Now booking is in pending_review, admin can re-accept it with client
        $acceptResponse = $this->actingAs($admin, 'admin')->post(route('admin.bookings.accept', $booking), [
            'pricing_mode' => 'price',
            'cost_amount' => 1500,
            'is_estimated' => '0',
            'cost_type' => 'final',
        ]);
        $acceptResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->status);
        $this->assertEquals(1500.00, (float) $booking->final_cost);
    }

    public function test_cancelled_booking_is_shown_under_cancelled_requests_and_not_completed_in_customer_portal(): void
    {
        $admin = Admin::first();
        $user = User::first();
        $service = Service::first();

        // 1. Create a booking for the customer
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $user->name,
            'customer_phone' => '01711223344',
            'customer_email' => $user->email,
            'property_type' => 'residential',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'Road 11, Block D',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'AC cooling fan is not working properly.',
            'status' => 'pending_review',
        ]);

        // 2. Admin cancels the booking with a specific reason
        $this->actingAs($admin, 'admin')->post(route('admin.bookings.cancel', $booking), [
            'cancellation_reason' => 'Customer requested cancellation due to personal reasons',
        ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertEquals('Customer requested cancellation due to personal reasons', $booking->cancellation_reason);

        // 3. Customer visits /my-bookings
        $customerListResponse = $this->actingAs($user, 'web')->get(route('customer.bookings'));
        $customerListResponse->assertOk();

        // Must appear under Cancelled Requests
        $customerListResponse->assertSee('Cancelled Requests');
        $customerListResponse->assertSee('#'.$booking->booking_number);
        $customerListResponse->assertSee('Cancelled ✕');
        $customerListResponse->assertSee('Customer requested cancellation due to personal reasons');

        // Must NOT appear under Completed Services
        $content = $customerListResponse->getContent();
        $this->assertStringNotContainsString('Completed Services (1)', $content);

        // 4. Customer visits /my-bookings/{booking_number}
        $customerDetailsResponse = $this->actingAs($user, 'web')->get(route('customer.bookings.show', $booking->booking_number));
        $customerDetailsResponse->assertOk();

        // Must display prominent cancellation banner with reason
        $customerDetailsResponse->assertSee('Service Request Cancelled');
        $customerDetailsResponse->assertSee('Customer requested cancellation due to personal reasons');
        $customerDetailsResponse->assertSee('Cancelled (No charge)');

        // Stepper must be hidden for cancelled bookings
        $customerDetailsResponse->assertDontSee('customer-stepper-wrap');
    }

    public function test_admin_can_accept_work_order_with_none_cost_type_or_set_price_later(): void
    {
        $admin = Admin::first();
        $user = User::first();
        $service = Service::first();

        // 1. Create a fresh booking with no cost set (cost_type is 'none')
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => 'Pending Client',
            'customer_phone' => '01711223344',
            'customer_email' => 'client@example.com',
            'property_type' => 'residential',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'Road 11',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Fix leaking pipe',
            'status' => 'pending_review',
        ]);

        $this->assertEquals('none', $booking->cost_type);

        // 2. Admin accepts booking when hidden cost_type is 'none' (or default from restarted/new orders)
        $acceptResponse = $this->actingAs($admin, 'admin')->post(route('admin.bookings.accept', $booking), [
            'pricing_mode' => 'price',
            'cost_type' => 'none',
            'cost_amount' => 1200,
            'is_estimated' => '1',
        ]);

        $acceptResponse->assertRedirect();
        $acceptResponse->assertSessionHasNoErrors();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->status);
        $this->assertEquals(1200, (float) $booking->estimated_cost);
        $this->assertNull($booking->final_cost);

        // 3. Admin can also accept with Set Price Later
        $booking2 = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => 'Pending Client 2',
            'customer_phone' => '01711223344',
            'customer_email' => 'client2@example.com',
            'property_type' => 'residential',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Banani',
            'street_address' => 'Road 5',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Afternoon',
            'urgency' => 'standard',
            'problem_description' => 'Electrical fix',
            'status' => 'pending_review',
        ]);

        $acceptLaterResponse = $this->actingAs($admin, 'admin')->post(route('admin.bookings.accept', $booking2), [
            'pricing_mode' => 'later',
            'cost_type' => 'none',
        ]);

        $acceptLaterResponse->assertRedirect();
        $acceptLaterResponse->assertSessionHasNoErrors();

        $booking2->refresh();
        $this->assertEquals('accepted', $booking2->status);
        $this->assertTrue($booking2->is_price_set_later);
    }
}
