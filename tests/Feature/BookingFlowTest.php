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

    public function test_assigning_crew_automatically_advances_status_to_in_progress(): void
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

        // Accept and assign crew
        $this->actingAs($admin, 'admin')->post(route('admin.bookings.accept', $booking), [
            'assigned_team' => 'Delta HVAC Engineering & Industrial Solutions — Engr. Kamrul Hasan - 01711223344',
            'pricing_mode' => 'price',
            'cost_amount' => 5000,
        ]);

        $booking->refresh();
        $this->assertEquals('in_progress', $booking->status);
        $this->assertEquals('Delta HVAC Engineering & Industrial Solutions — Engr. Kamrul Hasan - 01711223344', $booking->assigned_team);

        // Verify customer tracking page shows Assigned and In Progress
        $trackerResponse = $this->actingAs($user, 'web')->get('http://localhost/my-bookings');
        $trackerResponse->assertStatus(200);
        $trackerResponse->assertSee('In Progress');
        $trackerResponse->assertSee('Delta HVAC Engineering');
    }
}
