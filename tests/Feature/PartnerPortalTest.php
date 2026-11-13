<?php

namespace Tests\Feature;

use App\Mail\AdminEventNotificationMail;
use App\Mail\PartnerJobAssignedMail;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\District;
use App\Models\Partner;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Services\NotificationDispatcher;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PartnerPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    protected function createBooking(array $attributes = []): Booking
    {
        $user = User::first();
        $service = Service::first();

        return Booking::create(array_merge([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_type' => 'on_demand',
            'customer_name' => 'Default Customer',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '01711223344',
            'property_type' => 'commercial',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Gulshan',
            'street_address' => 'House 10, Road 5, Gulshan',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => '10:00 AM - 11:00 AM',
            'urgency' => 'standard',
            'problem_description' => 'Cooling system inspection.',
            'status' => 'pending_review',
        ], $attributes));
    }

    public function test_guest_is_redirected_to_partner_login(): void
    {
        $response = $this->get(route('partner.dashboard'));
        $response->assertRedirect(route('partner.login'));
    }

    public function test_approved_partner_can_login_with_phone_and_password(): void
    {
        $partner = Partner::create([
            'company_name' => 'CoolTech Engineering',
            'contact_person' => 'Mohammad Rafiq',
            'phone' => '01799998888',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'technicians_count' => '5-15',
            'coverage_areas' => 'Dhaka',
            'commission_rate' => 15.00,
            'payout_terms' => 'bi_weekly',
        ]);

        $response = $this->post(route('partner.login.submit'), [
            'phone' => '01799998888',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('partner.dashboard'));
        $this->assertAuthenticatedAs($partner, 'partner');
    }

    public function test_unapproved_partner_cannot_login(): void
    {
        $partner = Partner::create([
            'company_name' => 'Pending Tech',
            'contact_person' => 'Kamal',
            'phone' => '01811112222',
            'password' => Hash::make('secret123'),
            'status' => 'pending',
            'partner_type' => 'individual',
            'trade_domain' => 'Electrical',
            'technicians_count' => '1',
            'coverage_areas' => 'Mirpur',
            'commission_rate' => 15.00,
            'payout_terms' => 'bi_weekly',
        ]);

        $response = $this->post(route('partner.login.submit'), [
            'phone' => '01811112222',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('phone');
        $this->assertGuest('partner');
    }

    public function test_partner_can_toggle_online_offline_status(): void
    {
        $partner = Partner::create([
            'company_name' => 'Swift Electric',
            'contact_person' => 'Sumon',
            'phone' => '01700112233',
            'password' => Hash::make('pass123'),
            'status' => 'approved',
            'partner_type' => 'individual',
            'trade_domain' => 'Electrical',
            'technicians_count' => '1',
            'coverage_areas' => 'Gulshan',
            'is_currently_offline' => false,
        ]);

        $response = $this->actingAs($partner, 'partner')
            ->post(route('partner.availability.toggle'), [
                'offline_reason' => 'Vehicle repair in progress',
            ]);

        $response->assertRedirect();
        $partner->refresh();
        $this->assertTrue($partner->is_currently_offline);
        $this->assertEquals('Vehicle repair in progress', $partner->offline_reason);

        // Toggle back to online
        $response = $this->actingAs($partner, 'partner')
            ->post(route('partner.availability.toggle'));

        $partner->refresh();
        $this->assertFalse($partner->is_currently_offline);
    }

    public function test_partner_can_schedule_and_cancel_leave(): void
    {
        $partner = Partner::create([
            'company_name' => 'Elite Plumbing',
            'contact_person' => 'Sohel',
            'phone' => '01733445566',
            'password' => Hash::make('pass123'),
            'status' => 'approved',
            'partner_type' => 'individual',
            'trade_domain' => 'Plumbing',
            'technicians_count' => '1',
            'coverage_areas' => 'Uttara',
        ]);

        $response = $this->actingAs($partner, 'partner')
            ->post(route('partner.availability.leave.store'), [
                'start_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
                'end_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
                'reason' => 'Annual Village Vacation',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('partner_leaves', [
            'partner_id' => $partner->id,
            'reason' => 'Annual Village Vacation',
        ]);

        $leave = $partner->leaves()->first();
        $delResponse = $this->actingAs($partner, 'partner')
            ->delete(route('partner.availability.leave.destroy', $leave));

        $delResponse->assertRedirect();
        $this->assertDatabaseMissing('partner_leaves', ['id' => $leave->id]);
    }

    public function test_partner_can_view_job_accept_and_progress_through_stages(): void
    {
        $partner = Partner::create([
            'company_name' => 'Apex Facility Tech',
            'contact_person' => 'Tariq',
            'phone' => '01755667788',
            'password' => Hash::make('pass123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'technicians_count' => '10',
            'coverage_areas' => 'Dhaka',
        ]);

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'partner_job_status' => 'assigned',
            'status' => 'pending_review',
            'problem_description' => 'Chiller unit tripping compressor circuit breaker',
        ]);

        // 1. Partner accepts job
        $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.accept', $booking), [
                'agreed_price' => 1500,
            ])
            ->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->partner_job_status);

        // 2. Partner starts journey (on_the_way)
        $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.status', $booking), [
                'partner_job_status' => 'on_the_way',
            ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('on_the_way', $booking->partner_job_status);

        // 3. Partner starts work on site (in_progress)
        $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.status', $booking), [
                'partner_job_status' => 'in_progress',
                'agreed_price' => 1500,
            ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('in_progress', $booking->partner_job_status);
        $this->assertEquals('in_progress', $booking->status);

        // 4. Partner completes service (completed)
        $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.status', $booking), [
                'partner_job_status' => 'completed',
                'partner_notes' => 'Capacitor bank replaced and compressor amps balanced.',
            ])->assertRedirect();

        $booking->refresh();
        $this->assertEquals('completed', $booking->partner_job_status);
        $this->assertEquals('completed', $booking->status);
        $this->assertNotNull($booking->completed_at);
        $this->assertStringContainsString('Capacitor bank replaced', $booking->partner_notes);
    }

    public function test_vendor_can_manage_internal_crew_and_assign_to_booking(): void
    {
        $partner = Partner::create([
            'company_name' => 'Multi-Trade Services',
            'contact_person' => 'Managing Director',
            'phone' => '01912345678',
            'password' => Hash::make('pass123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'Multi-Discipline',
            'technicians_count' => '20',
            'coverage_areas' => 'Dhaka Metro',
        ]);

        // Add technician to vendor roster
        $response = $this->actingAs($partner, 'partner')
            ->post(route('partner.crews.store'), [
                'name' => 'Md. Masud Rana',
                'phone' => '01999887766',
                'trade_skill' => 'Senior HVAC Specialist',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('partner_crews', [
            'partner_id' => $partner->id,
            'name' => 'Md. Masud Rana',
            'phone' => '01999887766',
        ]);

        $crew = $partner->crews()->first();

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'partner_job_status' => 'accepted',
            'status' => 'assigned',
        ]);

        // Assign crew member internally
        $assignResponse = $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.crew', $booking), [
                'assigned_partner_crew_id' => $crew->id,
            ]);

        $assignResponse->assertRedirect();
        $booking->refresh();
        $this->assertEquals($crew->id, $booking->assigned_partner_crew_id);
    }

    public function test_partner_email_notifications_and_preferences(): void
    {
        Mail::fake();

        $partner = Partner::create([
            'company_name' => 'Alert Tech Ltd',
            'contact_person' => 'Tanvir',
            'phone' => '01788776655',
            'email' => 'partner@alerttech.com',
            'password' => Hash::make('pass123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'technicians_count' => '10',
            'coverage_areas' => 'Dhaka',
            'notification_preferences' => [
                'job_assigned' => true,
                'job_updated' => false, // opted out
                'payout_sent' => true,
                'job_cancelled' => true,
            ],
        ]);

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'partner_job_status' => 'assigned',
            'status' => 'assigned',
        ]);

        // Test job assigned mail is sent
        NotificationDispatcher::notifyPartnerJobAssigned($booking);
        Mail::assertSent(PartnerJobAssignedMail::class, function ($mail) {
            return $mail->hasTo('partner@alerttech.com');
        });

        // Test job updated mail is NOT sent because preference is false
        Mail::fake();
        NotificationDispatcher::notifyPartnerJobUpdated($booking, 'Window changed');
        Mail::assertNothingSent();
    }

    public function test_admin_staff_email_preferences_matrix(): void
    {
        Mail::fake();

        // Create Super Admin (defaults to all sections)
        $superAdmin = Admin::create([
            'name' => 'Super User',
            'email' => 'super@fix.com.bd',
            'password' => Hash::make('secret123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Staff Admin with only 'orders' subscription
        $staffOrdersOnly = Admin::create([
            'name' => 'Orders Staff',
            'email' => 'orders@fix.com.bd',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'is_active' => true,
            'email_notification_preferences' => ['orders'],
        ]);

        // Create Staff Admin with only 'support' subscription
        $staffSupportOnly = Admin::create([
            'name' => 'Support Staff',
            'email' => 'support@fix.com.bd',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'is_active' => true,
            'email_notification_preferences' => ['support'],
        ]);

        // Dispatch an 'orders' notification
        NotificationDispatcher::notifyAdminSection('orders', 'New Order Test', 'Order created');

        // Super Admin & Orders Staff receive the mail; Support Staff does NOT
        Mail::assertSent(AdminEventNotificationMail::class, function ($mail) {
            return $mail->hasTo('super@fix.com.bd');
        });
        Mail::assertSent(AdminEventNotificationMail::class, function ($mail) {
            return $mail->hasTo('orders@fix.com.bd');
        });
        Mail::assertNotSent(AdminEventNotificationMail::class, function ($mail) {
            return $mail->hasTo('support@fix.com.bd');
        });
    }

    public function test_partner_earning_models_calculation_matrix(): void
    {
        // 1. Commission model (20%)
        $commissionPartner = Partner::create([
            'company_name' => 'Comm Partners',
            'contact_person' => 'Kamal',
            'phone' => '01711100001',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'trade_domain' => 'HVAC',
            'coverage_areas' => 'Gulshan',
            'earning_model' => 'commission',
            'payout_rate' => 20.00,
        ]);
        $booking = $this->createBooking([
            'final_cost' => 5000,
            'status' => 'completed',
        ]);
        $this->assertEquals(1000.00, $commissionPartner->calculateBookingEarning($booking));
        $this->assertEquals('Commission (%)', $commissionPartner->earning_model_label);
        $this->assertEquals('20.00%', $commissionPartner->formatted_rate);

        // 2. Hourly model (৳ 500 / hr)
        $hourlyPartner = Partner::create([
            'company_name' => 'Hourly Crew',
            'contact_person' => 'Jamal',
            'phone' => '01711100002',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'trade_domain' => 'Electrical',
            'coverage_areas' => 'Banani',
            'earning_model' => 'hourly',
            'payout_rate' => 500.00,
        ]);
        $hourlyBooking = $this->createBooking([
            'partner_worked_hours' => 3.5,
            'status' => 'completed',
        ]);
        $this->assertEquals(1750.00, $hourlyPartner->calculateBookingEarning($hourlyBooking));
        $this->assertEquals('৳ 500.00 / hr', $hourlyPartner->formatted_rate);

        // 3. Fixed per job model (৳ 850 / job)
        $perJobPartner = Partner::create([
            'company_name' => 'Per Job Techs',
            'contact_person' => 'Rana',
            'phone' => '01711100003',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'trade_domain' => 'Plumbing',
            'coverage_areas' => 'Dhanmondi',
            'earning_model' => 'per_job',
            'payout_rate' => 850.00,
        ]);
        $perJobBooking = $this->createBooking([
            'final_cost' => 12000,
            'status' => 'completed',
        ]);
        $this->assertEquals(850.00, $perJobPartner->calculateBookingEarning($perJobBooking));
        $this->assertEquals('৳ 850.00 / job', $perJobPartner->formatted_rate);

        // 4. Custom manual booking payout amount override
        $overrideBooking = $this->createBooking([
            'partner_payout_amount' => 1200.00,
            'status' => 'completed',
        ]);
        $this->assertEquals(1200.00, $commissionPartner->calculateBookingEarning($overrideBooking));
    }

    public function test_partner_dashboard_and_payouts_render_without_component_error(): void
    {
        $partner = Partner::create([
            'company_name' => 'Apex Aircon',
            'contact_person' => 'Tanvir',
            'phone' => '01711100004',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'coverage_areas' => 'Gulshan',
            'earning_model' => 'per_job',
            'payout_rate' => 750.00,
        ]);

        $this->actingAs($partner, 'partner');

        $dashResponse = $this->get(route('partner.dashboard'));
        $dashResponse->assertOk();
        $dashResponse->assertSee('Active Orders');
        $dashResponse->assertSee('Earnings');

        $payoutResponse = $this->get(route('partner.payouts.index'));
        $payoutResponse->assertOk();
        $payoutResponse->assertSee('Fixed Per Job');
        $payoutResponse->assertSee('৳ 750.00 / job');
    }

    public function test_partner_in_app_notifications_lifecycle(): void
    {
        $partner = Partner::create([
            'company_name' => 'Swift Fixers',
            'contact_person' => 'Jamil',
            'phone' => '01711100005',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'Electrical',
            'coverage_areas' => 'Mirpur',
        ]);

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'partner_job_status' => 'assigned',
        ]);

        // 1. Dispatch job assigned notification
        NotificationDispatcher::notifyPartnerJobAssigned($booking);

        $this->assertDatabaseHas('partner_notifications', [
            'partner_id' => $partner->id,
            'booking_id' => $booking->id,
            'type' => 'job_assigned',
            'is_read' => false,
        ]);

        $this->assertEquals(1, $partner->unreadNotificationsCount());

        // 2. Dispatch job updated notification
        NotificationDispatcher::notifyPartnerJobUpdated($booking, 'Time changed to 4:00 PM');
        $this->assertEquals(2, $partner->unreadNotificationsCount());

        // 3. Authenticate as partner and visit notifications page
        $this->actingAs($partner, 'partner');
        $response = $this->get(route('partner.notifications.index'));
        $response->assertOk();
        $response->assertSee("New Job Assigned: #{$booking->booking_number}");
        $response->assertSee('Time changed to 4:00 PM');

        // 4. Tap single notification item (marks as read and redirects to target)
        $notif = $partner->notifications()->first();
        $readResponse = $this->get(route('partner.notifications.read', $notif).'?redirect=1');
        $readResponse->assertRedirect($notif->action_url);
        $this->assertTrue($notif->fresh()->is_read);
        $this->assertEquals(1, $partner->unreadNotificationsCount());

        // 5. Mark all as read
        $markAllResponse = $this->post(route('partner.notifications.mark-all-read'));
        $markAllResponse->assertRedirect();
        $this->assertEquals(0, $partner->unreadNotificationsCount());
    }

    public function test_partner_can_view_dynamic_service_types_and_coverage_areas_in_profile(): void
    {
        $partner = Partner::create([
            'company_name' => 'ElectroCool Pro',
            'contact_person' => 'Jamil Ahmed',
            'phone' => '01711998877',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'Electrical',
            'technicians_count' => '5-15',
            'coverage_areas' => 'Dhaka (Dhaka)',
        ]);

        // Add custom category and activate a district
        $newCategory = ServiceCategory::create([
            'name' => 'Solar & Renewable Energy',
            'slug' => 'solar-renewable-energy',
            'is_active' => true,
            'order' => 10,
        ]);

        $inactiveCategory = ServiceCategory::create([
            'name' => 'Decommissioned Category',
            'slug' => 'decommissioned-category',
            'is_active' => false,
            'order' => 20,
        ]);

        $this->actingAs($partner, 'partner');
        $response = $this->get(route('partner.profile.index'));

        $response->assertOk();
        $response->assertSee('Solar & Renewable Energy');
        $response->assertDontSee('Decommissioned Category');
        $response->assertSee('Gulshan');
    }

    public function test_partner_can_update_service_types_and_coverage_areas(): void
    {
        $partner = Partner::create([
            'company_name' => 'Precision HVAC Services',
            'contact_person' => 'Tanvir Alam',
            'phone' => '01855667788',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'General',
            'technicians_count' => '5-15',
            'coverage_areas' => 'Dhaka (Dhaka)',
        ]);

        $category1 = ServiceCategory::firstOrCreate(
            ['name' => 'HVAC & Central Air Conditioning'],
            ['slug' => 'hvac-central-air-conditioning', 'is_active' => true]
        );
        $category2 = ServiceCategory::firstOrCreate(
            ['name' => 'Fire Safety & NFPA Suppression'],
            ['slug' => 'fire-safety-nfpa-suppression', 'is_active' => true]
        );

        $this->actingAs($partner, 'partner');

        $response = $this->put(route('partner.profile.update'), [
            'contact_person' => 'Tanvir Alam (Lead)',
            'trade_domain_submitted' => '1',
            'trade_domain' => [
                'HVAC & Central Air Conditioning',
                'Fire Safety & NFPA Suppression',
            ],
            'coverage_areas_submitted' => '1',
            'coverage_areas' => [
                'Gulshan',
                'Banani',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $partner->refresh();
        $this->assertEquals('Tanvir Alam (Lead)', $partner->contact_person);
        $this->assertStringContainsString('HVAC & Central Air Conditioning', $partner->trade_domain);
        $this->assertStringContainsString('Fire Safety & NFPA Suppression', $partner->trade_domain);
        $this->assertStringContainsString('Gulshan', $partner->coverage_areas);
        $this->assertStringContainsString('Banani', $partner->coverage_areas);
    }

    public function test_partner_can_upload_and_remove_profile_avatar(): void
    {
        Storage::fake('public');

        $partner = Partner::create([
            'company_name' => 'SolarGrid Engineering',
            'contact_person' => 'Hasibul Islam',
            'phone' => '01712000000',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'individual',
            'trade_domain' => 'Electrical',
            'technicians_count' => '1-5',
            'coverage_areas' => 'Dhaka (Dhaka)',
        ]);

        $this->actingAs($partner, 'partner');

        // 1. Upload avatar
        $fakeAvatar = UploadedFile::fake()->image('hasibul_avatar.jpg', 300, 300);
        $response = $this->put(route('partner.profile.update'), [
            'contact_person' => 'Hasibul Islam',
            'avatar' => $fakeAvatar,
        ]);

        $response->assertRedirect();
        $partner->refresh();
        $this->assertNotNull($partner->avatar);
        $this->assertStringStartsWith('avatars/partners/', $partner->avatar);
        Storage::disk('public')->assertExists($partner->avatar);

        // Verify profile view displays avatar image
        $viewResponse = $this->get(route('partner.profile.index'));
        $viewResponse->assertOk();
        $viewResponse->assertSee($partner->avatar_url);

        // 2. Remove avatar
        $oldPath = $partner->avatar;
        $removeResponse = $this->put(route('partner.profile.update'), [
            'contact_person' => 'Hasibul Islam',
            'remove_avatar' => '1',
        ]);

        $removeResponse->assertRedirect();
        $partner->refresh();
        $this->assertNull($partner->avatar);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_customer_view_does_not_expose_partner_phone_or_internal_details(): void
    {
        $user = User::first();
        $partner = Partner::create([
            'company_name' => 'Confidential Subcontractor Ltd',
            'contact_person' => 'Top Secret Partner Lead',
            'phone' => '01999887766',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'technicians_count' => '1-5',
            'coverage_areas' => 'Dhaka (Dhaka)',
        ]);

        $booking = $this->createBooking([
            'user_id' => $user->id,
            'partner_id' => $partner->id,
            'assigned_team' => 'Confidential Subcontractor Ltd',
            'partner_job_status' => 'assigned',
            'status' => 'assigned',
        ]);

        // Customer accesses /my-bookings
        $response = $this->actingAs($user, 'web')->get('/my-bookings');
        $response->assertOk();
        $response->assertDontSee('01999887766');
        $response->assertDontSee('Confidential Subcontractor Ltd');
        $response->assertSee('Field Crew Assigned & Confirmed', false);

        // Customer accesses /my-bookings/{booking_number}
        $detailResponse = $this->actingAs($user, 'web')->get('/my-bookings/'.$booking->booking_number);
        $detailResponse->assertOk();
        $detailResponse->assertDontSee('01999887766');
        $detailResponse->assertDontSee('Confidential Subcontractor Ltd');
    }

    public function test_crew_cannot_accept_job_without_setting_price_when_price_set_later(): void
    {
        $partner = Partner::create([
            'company_name' => 'Apex Cooling Solutions',
            'contact_person' => 'Kamal Hossain',
            'phone' => '01711229988',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'technicians_count' => '1-5',
            'coverage_areas' => 'Dhaka (Dhaka)',
        ]);

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'partner_job_status' => 'assigned',
            'status' => 'accepted',
            'estimated_cost' => null,
            'final_cost' => null,
        ]);

        // Attempt to accept without agreed_price fails validation
        $response = $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.accept', $booking));
        $response->assertSessionHasErrors('agreed_price');

        $booking->refresh();
        $this->assertEquals('assigned', $booking->partner_job_status);

        // Successfully accepting with agreed_price sets final_cost
        $acceptResponse = $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.accept', $booking), [
                'agreed_price' => 1750,
            ]);
        $acceptResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->partner_job_status);
        $this->assertEquals(1750.00, (float) $booking->final_cost);
        $this->assertNull($booking->estimated_cost);
    }

    public function test_crew_accepting_estimated_cost_converts_it_to_final_price(): void
    {
        $partner = Partner::create([
            'company_name' => 'Delta Tech Services',
            'contact_person' => 'Faruk Ahmed',
            'phone' => '01711339988',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'technicians_count' => '1-5',
            'coverage_areas' => 'Dhaka (Dhaka)',
        ]);

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'partner_job_status' => 'assigned',
            'status' => 'accepted',
            'estimated_cost' => 850.00,
            'final_cost' => null,
        ]);

        // Crew confirms/sets final price upon accept (e.g. 900)
        $response = $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.accept', $booking), [
                'agreed_price' => 900,
            ]);
        $response->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->partner_job_status);
        $this->assertEquals(900.00, (float) $booking->final_cost);
        // It is no longer estimated!
        $this->assertNull($booking->estimated_cost);
    }

    public function test_crew_can_see_and_accept_job_when_final_price_already_set(): void
    {
        $partner = Partner::create([
            'company_name' => 'Prime Electric Works',
            'contact_person' => 'Sabbir Hossain',
            'phone' => '01711449988',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'company',
            'trade_domain' => 'HVAC',
            'technicians_count' => '1-5',
            'coverage_areas' => 'Dhaka (Dhaka)',
        ]);

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'partner_job_status' => 'assigned',
            'status' => 'accepted',
            'estimated_cost' => null,
            'final_cost' => 2500.00,
        ]);

        // Crew views the job and sees the price
        $viewResponse = $this->actingAs($partner, 'partner')
            ->get(route('partner.jobs.show', $booking));
        $viewResponse->assertOk();
        $viewResponse->assertSee('Agreed Price: ৳ 2,500.00');

        // Crew accepts directly
        $acceptResponse = $this->actingAs($partner, 'partner')
            ->post(route('partner.jobs.accept', $booking));
        $acceptResponse->assertRedirect();

        $booking->refresh();
        $this->assertEquals('accepted', $booking->partner_job_status);
        $this->assertEquals(2500.00, (float) $booking->final_cost);
    }

    public function test_partner_notification_row_has_no_view_button_and_accessing_cancelled_job_does_not_throw_403(): void
    {
        $partner = Partner::create([
            'company_name' => 'Safe Air Systems',
            'contact_person' => 'Rahim',
            'phone' => '01711009988',
            'password' => Hash::make('secret123'),
            'status' => 'approved',
            'partner_type' => 'individual',
            'trade_domain' => 'HVAC',
            'coverage_areas' => 'Gulshan',
        ]);

        $booking = $this->createBooking([
            'partner_id' => $partner->id,
            'status' => 'assigned',
        ]);

        // Send cancelled notification
        NotificationDispatcher::notifyPartnerJobCancelled($booking, 'Client requested cancellation');

        // Unassign partner (e.g. order restarted or recalled)
        $booking->update([
            'partner_id' => null,
            'status' => 'cancelled',
        ]);

        // 1. Partner visits notifications list
        $response = $this->actingAs($partner, 'partner')->get(route('partner.notifications.index'));
        $response->assertOk();

        // Must see modal and row, but MUST NOT see the "View" button
        $response->assertSee('id="notifDetailModal"', false);
        $response->assertDontSee('>View<', false);
        $response->assertDontSee('<span>View</span>', false);

        // 2. Mark notification read via AJAX returns JSON
        $notif = $partner->notifications()->latest()->first();
        $ajaxResponse = $this->actingAs($partner, 'partner')->postJson(route('partner.notifications.read', $notif));
        $ajaxResponse->assertOk();
        $ajaxResponse->assertJson(['success' => true]);
        $this->assertTrue($notif->fresh()->is_read);

        // 3. Accessing the job route for a job where partner was notified does NOT 403
        $jobResponse = $this->actingAs($partner, 'partner')->get(route('partner.jobs.show', $booking));
        $jobResponse->assertRedirect(route('partner.jobs.index'));
        $jobResponse->assertSessionHas('info');
    }
}
