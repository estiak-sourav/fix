<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Service;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MobileResponsivenessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_style_css_contains_mobile_responsive_rules_for_web(): void
    {
        $cssPath = public_path('assets/css/style.css');
        $this->assertFileExists($cssPath);

        $cssContent = file_get_contents($cssPath);

        // Responsive queries for video portfolio & playlist
        $this->assertStringContainsString('.video-portfolio-grid', $cssContent);
        $this->assertStringContainsString('.video-playlist-wrapper', $cssContent);

        // Responsive form & booking layout queries
        $this->assertStringContainsString('.booking-layout-grid', $cssContent);
        $this->assertStringContainsString('.form-row-3col', $cssContent);

        // Customer portal mobile responsive classes
        $this->assertStringContainsString('.customer-booking-card', $cssContent);
        $this->assertStringContainsString('.customer-ticket-card', $cssContent);
        $this->assertStringContainsString('.customer-profile-card', $cssContent);
        $this->assertStringContainsString('.customer-stepper-wrap', $cssContent);
    }

    public function test_admin_layout_contains_mobile_drawer_and_grid_stacking_rules(): void
    {
        $adminLayoutPath = resource_path('views/components/layouts/admin.blade.php');
        $this->assertFileExists($adminLayoutPath);

        $content = file_get_contents($adminLayoutPath);

        // Mobile drawer backdrop & toggle
        $this->assertStringContainsString('id="sidebarBackdrop"', $content);
        $this->assertStringContainsString('toggleAdminSidebar()', $content);
        $this->assertStringContainsString('closeMobileSidebar()', $content);

        // Mobile CSS rules for layout, modals, topbar and auto-stacking grids
        $this->assertStringContainsString('@media (max-width: 992px)', $content);
        $this->assertStringContainsString('@media (max-width: 768px)', $content);
        $this->assertStringContainsString('@media (max-width: 576px)', $content);
        $this->assertStringContainsString('grid-template-columns: 1fr !important', $content);
        $this->assertStringContainsString('.modal-dialog-paces', $content);
    }

    public function test_public_pages_and_customer_portal_render_responsively(): void
    {
        $user = User::factory()->create([
            'email' => 'customer.test@example.com',
            'phone' => '01711000000',
        ]);

        $service = Service::first();

        Booking::create([
            'booking_number' => 'FX-2026-TEST',
            'user_id' => $user->id,
            'service_id' => $service?->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'service_type' => 'on_demand',
            'property_type' => 'Commercial Tower',
            'street_address' => 'Plot 4, Road 11, Banani',
            'area' => 'Banani',
            'district' => 'Dhaka',
            'division' => 'Dhaka',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time_slot' => 'Morning (9:00 AM - 12:00 PM)',
            'status' => 'accepted',
            'problem_description' => 'Chiller unit requires preventive compressor audit.',
        ]);

        SupportTicket::create([
            'ticket_number' => 'TCK-2026-TEST',
            'access_token' => Str::random(32),
            'user_id' => $user->id,
            'subject' => 'Water pressure check request',
            'category' => 'plumbing',
            'priority' => 'standard',
            'status' => 'open',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'message' => 'Please inspect pressure valves in pump house.',
        ]);

        // 1. Homepage & Video section
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('mobile-drawer');
        $response->assertSee('video-portfolio-grid');

        // 2. Booking form page
        $response = $this->get(route('booking.create'));
        $response->assertStatus(200);
        $response->assertSee('form-row-3col');

        // 3. Customer Bookings
        $response = $this->actingAs($user)->get(route('customer.bookings'));
        $response->assertStatus(200);
        $response->assertSee('customer-booking-card');
        $response->assertSee('customer-stepper-wrap');

        // 4. Customer Support Tickets
        $response = $this->actingAs($user)->get(route('customer.tickets.index'));
        $response->assertStatus(200);
        $response->assertSee('customer-ticket-card');

        // 5. Customer Profile
        $response = $this->actingAs($user)->get(route('customer.profile'));
        $response->assertStatus(200);
        $response->assertSee('customer-profile-card');
        $response->assertSee('form-row-3col');
    }

    public function test_admin_cms_screens_render_cleanly_with_responsive_containers(): void
    {
        $admin = Admin::where('role', 'super_admin')->first() ?? Admin::first();

        // Admin Dashboard
        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('app-topbar');
        $response->assertSee('sidebarBackdrop');

        // Admin Web Contents
        $response = $this->actingAs($admin, 'admin')->get(route('admin.web.contents'));
        $response->assertStatus(200);
        $response->assertSee('modal-dialog-paces');

        // Admin Services
        $response = $this->actingAs($admin, 'admin')->get(route('admin.web.services'));
        $response->assertStatus(200);
        $response->assertSee('table-responsive-paces');

        // Admin Activity Logs
        $response = $this->actingAs($admin, 'admin')->get(route('admin.activity-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('activity-filter-form');
    }
}
