<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AmcPlan;
use App\Models\Booking;
use App\Models\Service;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AmcContractAndDynamicVideoTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected User $user;

    protected AmcPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = Admin::where('role', 'super_admin')->first() ?? Admin::first();
        $this->user = User::factory()->create();

        $this->plan = AmcPlan::firstOrCreate(
            ['slug' => 'test-enterprise-amc'],
            [
                'name' => 'Corporate Enterprise AMC',
                'badge' => 'Enterprise',
                'tier' => 'Enterprise',
                'price' => 120000,
                'price_period' => 'Per Year',
                'is_active' => true,
            ]
        );
    }

    /**
     * Test admin can activate AMC contract with agreement document upload.
     */
    public function test_admin_can_activate_amc_contract_with_agreement_upload(): void
    {
        Storage::fake('public');

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $this->user->id,
            'service_type' => 'on_demand',
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
            'problem_description' => 'Need full facility preventive inspection.',
            'status' => 'pending_review',
        ]);

        $agreementFile = UploadedFile::fake()->image('signed_contract.jpg', 600, 800);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.bookings.activate-amc', $booking), [
            'amc_plan_id' => $this->plan->id,
            'contract_start_date' => now()->toDateString(),
            'contract_end_date' => now()->addYear()->toDateString(),
            'contract_cost' => 85000,
            'amc_package_notes' => 'Quarterly preventive maintenance with thermal imaging and priority 2h emergency response.',
            'agreement_file' => $agreementFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals($this->plan->id, $booking->amc_plan_id);
        $this->assertEquals('amc_contract', $booking->service_type);
        $this->assertEquals('active', $booking->contract_status);
        $this->assertEquals(85000, $booking->final_cost);
        $this->assertNotNull($booking->agreement_document_path);
        Storage::disk('public')->assertExists($booking->agreement_document_path);

        // Check Admin booking show page displays active contract details
        $showResponse = $this->actingAs($this->admin, 'admin')->get(route('admin.bookings.show', $booking));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Active Contract');
        $showResponse->assertSee('Corporate Enterprise AMC');
        $showResponse->assertSee('85,000.00');
        $showResponse->assertSee('View Agreement Copy');

        // Check Customer Portal displays active contract details
        $custResponse = $this->actingAs($this->user, 'web')->get('http://localhost/my-bookings/'.$booking->booking_number);
        $custResponse->assertStatus(200);
        $custResponse->assertSee('Active Annual Maintenance Contract (AMC)');
        $custResponse->assertSee('Corporate Enterprise AMC');
        $custResponse->assertSee('View Signed Agreement Document');
    }

    /**
     * Test admin can update video portfolio dynamic playlist and toggle single vs multi video mode.
     */
    public function test_admin_can_update_video_portfolio_dynamic_playlist(): void
    {
        $content = SiteContent::firstOrCreate(
            ['section_key' => 'video_portfolio'],
            ['title' => 'Watch FIX in Action']
        );

        $playlistData = [
            [
                'title' => 'Central HVAC & Chiller Overhaul',
                'meta' => 'Cooling Tower Banani',
                'video_id' => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
            ],
            [
                'title' => 'Substation & Transformer Testing',
                'meta' => 'Savar EPZ',
                'video_id' => 'dQw4w9WgXcQ',
            ],
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.web.contents.update', $content), [
            'title' => 'Watch FIX in Action: Operations Showcase',
            'playlist' => $playlistData,
        ]);

        $response->assertRedirect();
        $content->refresh();

        $savedPlaylist = $content->meta_data['playlist'] ?? [];
        $this->assertCount(2, $savedPlaylist);
        $this->assertEquals('LXb3EKWsInQ', $savedPlaylist[0]['video_id']);
        $this->assertEquals('dQw4w9WgXcQ', $savedPlaylist[1]['video_id']);

        // Frontend with 2 videos renders playlist
        $homeResponse = $this->get('http://localhost/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Central HVAC &amp; Chiller Overhaul', false);
        $homeResponse->assertSee('Substation &amp; Transformer Testing', false);

        // Update to single video: right playlist should hide
        $content->update([
            'meta_data' => [
                'playlist' => [
                    [
                        'title' => 'Solo Showcase Video',
                        'meta' => 'Full Width Preview',
                        'video_id' => 'LXb3EKWsInQ',
                    ],
                ],
            ],
        ]);

        $singleResponse = $this->get('http://localhost/');
        $singleResponse->assertStatus(200);
        $singleResponse->assertSee('video-single-player');
        $singleResponse->assertDontSee('video-playlist-card');
    }

    /**
     * Test services creation and details display dynamic quote instead of fixed starting price / SLA.
     */
    public function test_services_create_page_and_details_exclude_fixed_pricing_inputs(): void
    {
        // 1. Check CMS create page does not render starting price or target SLA inputs
        $createRes = $this->actingAs($this->admin, 'admin')->get(route('admin.web.services.create'));
        $createRes->assertStatus(200);
        $createRes->assertDontSee('Starting Price (৳)');
        $createRes->assertDontSee('Pricing Unit / Model');
        $createRes->assertDontSee('Target SLA (Hours)');

        // 2. Check service details page displays custom quote on inspection
        $service = Service::first();
        $detailRes = $this->get('http://localhost/services/'.$service->slug);
        $detailRes->assertStatus(200);
        $detailRes->assertSee('Custom Quote on Inspection');
        $detailRes->assertSee('Direct Engineering Team Coordination');
        $detailRes->assertDontSee('Starting Price:');
        $detailRes->assertDontSee('Emergency SLA Dispatch:');
    }
}
