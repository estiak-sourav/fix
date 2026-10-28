<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Service;
use App\Models\SiteSetting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterAndLegalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_renders_enhanced_dynamic_footer(): void
    {
        SiteSetting::set('facebook_url', 'https://facebook.com/fixfacility');
        SiteSetting::set('head_office_address', 'Plot 99, Banani Commercial Area');

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Plot 99, Banani Commercial Area');
        $response->assertSee('https://facebook.com/fixfacility');
        $response->assertSee(route('sla'));
        $response->assertDontSee(route('sitemap'));
        $response->assertDontSee('<h4>Core Services</h4>', false);
        $response->assertSee('Book a Service');
        $response->assertSee('Become a Partner');
        $response->assertSee('<h4>Support</h4>', false);
        $response->assertSee('Helpdesk & Support');
        $response->assertSee('Track Ticket');
        $response->assertSee('About Us');
        $response->assertSee('Testimonials');
    }

    public function test_support_create_page_has_white_title_and_no_unwanted_text(): void
    {
        $response = $this->get(route('support.create'));

        $response->assertStatus(200);
        $response->assertSee('Submit a Support Ticket');
        $response->assertDontSee('24/7 ENGINEERING HELPDESK');
        $response->assertDontSee('No account needed:');
        $response->assertSee('Track Ticket');
    }

    public function test_sla_page_returns_successful_response(): void
    {
        $response = $this->get(route('sla'));

        $response->assertStatus(200);
        $response->assertSee('Service Level Agreement (SLA) & Guarantee');
        $response->assertSee('60 minutes');
        $response->assertSee('30-Day Certified Workmanship Guarantee');
        $response->assertDontSee('SLA GUARANTEE');
        $response->assertDontSee('Compliance & Policies');
    }

    public function test_privacy_page_returns_successful_response_without_header_badge(): void
    {
        $response = $this->get(route('privacy'));

        $response->assertStatus(200);
        $response->assertSee('Privacy Policy');
        $response->assertDontSee('SLA GUARANTEE');
        $response->assertDontSee('Compliance & Policies');
    }

    public function test_terms_page_returns_successful_response_without_header_badge(): void
    {
        $response = $this->get(route('terms'));

        $response->assertStatus(200);
        $response->assertSee('Terms of Service');
        $response->assertDontSee('SLA GUARANTEE');
        $response->assertDontSee('Compliance & Policies');
    }

    public function test_sitemap_page_returns_successful_response_and_lists_services(): void
    {
        $service = Service::where('is_active', true)->first();

        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertSee('Website Sitemap');
        $response->assertSee('Facility Engineering Services');
        if ($service) {
            $response->assertSee($service->title);
        }
    }

    public function test_admin_can_update_social_settings_and_operating_hours(): void
    {
        /** @var Admin $admin */
        $admin = Admin::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.web.settings.update'), [
            'site_name' => 'FIX Facility Bangladesh',
            'site_tagline' => 'Engineering Excellence',
            'head_office_address' => 'Dhaka Headquarters',
            'emergency_phone' => '+8801700999888',
            'support_email' => 'help@fix.com.bd',
            'copyright_text' => '© 2026 FIX Facility',
            'operating_hours' => '24/7 Operations Desk',
            'facebook_url' => 'https://facebook.com/newfix',
            'linkedin_url' => 'https://linkedin.com/company/newfix',
            'instagram_url' => 'https://instagram.com/newfix',
            'whatsapp_number' => '+8801711223344',
        ]);

        $response->assertRedirect();
        $this->assertEquals('https://facebook.com/newfix', SiteSetting::get('facebook_url'));
        $this->assertEquals('+8801711223344', SiteSetting::get('whatsapp_number'));
        $this->assertEquals('24/7 Operations Desk', SiteSetting::get('operating_hours'));
    }
}
