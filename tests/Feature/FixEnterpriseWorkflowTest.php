<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\EmailOtp;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FixEnterpriseWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test OTP dispatch and verification lifecycle.
     */
    public function test_customer_can_request_and_verify_email_otp(): void
    {
        $email = 'corporate.client@example.com';

        // 1. Send OTP (Registration flow with BD phone)
        $response = $this->postJson('/auth/send-otp', [
            'email' => $email,
            'name' => 'Tanvir Ahmed',
            'phone' => '01712345678',
            'purpose' => 'register',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // Verify OTP was saved in database
        $this->assertDatabaseHas('email_otps', [
            'email' => $email,
            'is_consumed' => false,
        ]);

        $otpRecord = EmailOtp::where('email', $email)->latest()->first();
        $this->assertNotNull($otpRecord);

        // 2. Verify with wrong OTP fails
        $wrongResponse = $this->postJson('/auth/verify-otp', [
            'email' => $email,
            'otp_code' => '000000',
        ]);
        $wrongResponse->assertStatus(422);

        // 3. Verify with valid OTP succeeds (simulate knowing the generated code)
        // Set known OTP hash
        $knownCode = '123456';
        $otpRecord->update(['otp_code_hash' => Hash::make($knownCode)]);

        $validResponse = $this->postJson('/auth/verify-otp', [
            'email' => $email,
            'otp_code' => $knownCode,
        ]);

        $validResponse->assertStatus(200);
        $validResponse->assertJson(['success' => true]);

        // Verify authenticated
        $this->assertAuthenticated();

        // Verify OTP consumed
        $this->assertTrue($otpRecord->fresh()->is_consumed);
    }

    /**
     * Test unauthenticated booking redirect / restriction.
     */
    public function test_guest_is_prompted_for_login_when_submitting_booking(): void
    {
        $service = Service::first();

        $response = $this->post('/booking', [
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => 'Unauthenticated User',
            'customer_phone' => '01711223344',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan 2',
            'street_address' => 'Plot 45, Road 11',
            'urgency' => 'scheduled',
            'preferred_date' => now()->addDay()->format('Y-m-d'),
            'preferred_time_slot' => 'morning',
            'problem_description' => 'Guest trying to submit without login verification',
        ]);

        // Unauthenticated user is redirected to booking form with auth modal trigger
        $response->assertStatus(302);
        $response->assertRedirect(route('booking.create'));
        $response->assertSessionHas('trigger_auth_modal', true);
    }

    /**
     * Test full booking creation, status timeline, and admin audio alert trigger.
     */
    public function test_authenticated_customer_can_book_and_triggers_admin_audio_notification(): void
    {
        $customer = User::where('email', 'client@fix.com.bd')->first();
        $this->actingAs($customer);

        $service = Service::first();

        $bookingData = [
            'service_type' => 'emergency',
            'service_id' => $service->id,
            'customer_name' => $customer->name,
            'customer_phone' => '01711223344',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'Plot 45, Gulshan Avenue, Dhaka',
            'urgency' => 'emergency_1h',
            'preferred_date' => now()->format('Y-m-d'),
            'preferred_time_slot' => 'morning',
            'problem_description' => 'Urgent central chiller leak in tower basement',
        ];

        $response = $this->post('/booking', $bookingData);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Verify booking in database
        $this->assertDatabaseHas('bookings', [
            'customer_email' => $customer->email,
            'urgency' => 'emergency_1h',
            'status' => 'pending_review',
        ]);

        $booking = Booking::where('customer_email', $customer->email)->latest()->first();
        $this->assertNotNull($booking);

        // Verify admin notification created with sound_played = false
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'emergency_alert',
            'sound_played' => 0,
        ]);

        // Verify customer can view their bookings list
        $customerBookingsResponse = $this->get('/my-bookings');
        $customerBookingsResponse->assertStatus(200);
        $customerBookingsResponse->assertSee($booking->booking_number);

        // Verify customer can view single booking tracking page
        $trackResponse = $this->get('/my-bookings/'.$booking->booking_number);
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee('Urgent central chiller leak in tower basement');
    }

    /**
     * Test admin alert checking endpoint and audio chime state.
     */
    public function test_admin_can_poll_alerts_and_manage_booking_status(): void
    {
        $admin = Admin::where('role', 'super_admin')->first();
        $this->actingAs($admin, 'admin');

        // Create an unplayed notification
        AdminNotification::create([
            'type' => 'emergency_alert',
            'title' => 'Critical HVAC Alert',
            'message' => 'Emergency chiller breakdown reported',
            'sound_played' => false,
            'is_read' => false,
        ]);

        // Admin checks alerts (sound polling endpoint)
        $alertResponse = $this->getJson('/admin/alerts/check');
        $alertResponse->assertStatus(200);
        $alertResponse->assertJson([
            'has_sound' => true,
        ]);

        // Now sound_played should be updated to true so audio doesn't loop forever
        $this->assertEquals(0, AdminNotification::where('sound_played', false)->count());

        // Create booking to update
        $customer = User::where('email', 'client@fix.com.bd')->first();
        $service = Service::first();
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'service_type' => 'emergency',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '01711223344',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'Plot 45, Gulshan Avenue',
            'preferred_date' => now()->format('Y-m-d'),
            'preferred_time_slot' => 'morning',
            'urgency' => 'emergency_1h',
            'problem_description' => 'Test emergency booking for admin dispatch',
            'status' => 'pending_review',
        ]);

        // Test updating a booking status
        $statusUpdate = $this->post('/admin/bookings/'.$booking->id.'/status', [
            'status' => 'in_progress',
            'note' => 'Technician team dispatched to site with equipment.',
        ]);

        $statusUpdate->assertRedirect();
        $this->assertEquals('in_progress', $booking->fresh()->status);

        // Verify audit log recorded
        $this->assertDatabaseHas('booking_status_logs', [
            'booking_id' => $booking->id,
            'to_status' => 'in_progress',
        ]);
    }

    /**
     * Test customer profile update.
     */
    public function test_customer_can_update_profile(): void
    {
        $customer = User::where('email', 'client@fix.com.bd')->first();
        $this->actingAs($customer, 'web');

        $profileResponse = $this->put('/profile', [
            'name' => 'Tanvir Enterprise',
            'phone' => '+880 1812-345678',
            'company_name' => 'Square Apex Ltd.',
            'property_type' => 'industrial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Banani',
            'default_address' => 'House 14, Road 2, Banani, Dhaka',
        ]);

        $profileResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'phone' => '+880 1812-345678',
            'company_name' => 'Square Apex Ltd.',
            'property_type' => 'industrial',
            'area' => 'Banani',
        ]);
    }

    /**
     * Test multi-guard authentication, isolated sessions and smart guest redirection.
     */
    public function test_multi_guard_isolation_and_smart_guest_redirection(): void
    {
        // 1. Unauthenticated admin access redirects to admin.login
        $unauthAdmin = $this->get('/admin');
        $unauthAdmin->assertRedirect(route('admin.login'));

        // 2. Unauthenticated customer access redirects to web login
        $unauthWeb = $this->get('/my-bookings');
        $unauthWeb->assertRedirect(route('login'));

        // 3. Admin can authenticate using Action-Driven login with checkbox 'on'
        $loginResponse = $this->post('/admin/login', [
            'email' => 'admin@fix.com.bd',
            'password' => 'password123',
            'remember' => 'on',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated('admin');

        // Verify web guard remains unauthenticated (Session isolation)
        $this->assertGuest('web');

        // 4. Admin logout terminates admin guard
        $logoutResponse = $this->post('/admin/logout');
        $logoutResponse->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    /**
     * Test contact inquiry submission triggers admin notification with alert chime.
     */
    public function test_customer_can_send_contact_inquiry_and_notify_admin(): void
    {
        $response = $this->postJson('/contact', [
            'name' => 'Farhan Kabir',
            'phone' => '+880 1712-345678',
            'email' => 'farhan@apex.com',
            'subject' => 'Corporate AMC',
            'message' => 'Requesting annual electrical substation & HVAC maintenance audit.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('admin_notifications', [
            'title' => 'New Facility Inquiry: Corporate AMC',
            'type' => 'inquiry',
            'sound_played' => false,
            'is_read' => false,
        ]);
    }

    /**
     * Test booking service order in nationwide divisions (e.g. Chattogram, Sylhet, Khulna).
     */
    public function test_customer_can_create_booking_in_any_bangladesh_division(): void
    {
        $service = Service::first();
        $customer = User::factory()->create([
            'role' => 'customer',
            'division' => 'Chattogram',
            'district' => 'Chattogram City',
            'area' => 'Agrabad Commercial',
        ]);
        $this->actingAs($customer, 'web');

        $bookingData = [
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $customer->name,
            'customer_phone' => '01811223344',
            'property_type' => 'commercial',
            'division' => 'Chattogram',
            'district' => 'Chattogram City',
            'area' => 'Agrabad Commercial',
            'street_address' => 'Agrabad C/A, Tower 3, Floor 5',
            'urgency' => 'standard',
            'preferred_date' => now()->addDay()->format('Y-m-d'),
            'preferred_time_slot' => 'morning',
            'problem_description' => 'Comprehensive electrical safety and wiring inspection.',
        ];

        $response = $this->postJson('/booking', $bookingData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('bookings', [
            'customer_email' => $customer->email,
            'division' => 'Chattogram',
            'district' => 'Chattogram City',
            'area' => 'Agrabad Commercial',
            'status' => 'pending_review',
        ]);
    }
}
