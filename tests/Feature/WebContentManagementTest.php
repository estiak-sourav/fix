<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Service;
use App\Models\SiteContent;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class WebContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = Admin::first();
    }

    /**
     * Test web content CMS displays all organized pages: homepage, partner, legal, and support.
     */
    public function test_web_content_displays_organized_pages_and_primary_color_theme(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.web.contents'));
        $response->assertStatus(200);

        // Page Filter Tabs
        $response->assertSee('All Pages');
        $response->assertSee('Become a Partner');
        $response->assertSee('Legal &amp; Policies', false);
        $response->assertSee('Support Helpdesk');

        // Brand color --paces-primary: #3e60d5
        $response->assertSee('#3e60d5');

        // Homepage sections
        $response->assertSee('Hero &amp; Service Booking Banner', false);
        $response->assertSee('Corporate Partners (Clients Marquee)');
        $response->assertSee('Why Choose FIX');

        // Partner sections
        $response->assertSee('Partner Enlistment Hero &amp; Metrics', false);
        $response->assertSee('Contractor Benefits &amp; Advantage Perks', false);

        // Legal sections
        $response->assertSee('Privacy Policy &amp; Data Protection', false);
        $response->assertSee('Terms of Service &amp; General Conditions', false);
        $response->assertSee('Service Level Agreement (SLA) &amp; Guarantee', false);

        // Live Website Links (Direct Section Anchors replacing developer keys)
        $response->assertSee('View on Website');
        $response->assertSee('/#hero');
        $response->assertSee('/#how-it-works');
        $response->assertSee('/#why-fix');
        $response->assertSee('/vendor#vendor-hero');
        $response->assertSee('/privacy#legal-content');
        $response->assertSee('/support#support-portal');
        $response->assertDontSee('Key: site_content:why_fix', false);
        $response->assertDontSee('Identifier: site_content:why_fix', false);
    }

    /**
     * Test updating partner content in CMS updates the live vendor page.
     */
    public function test_updating_partner_content_updates_live_vendor_page(): void
    {
        $vendorHero = SiteContent::where('section_key', 'vendor_hero')->firstOrFail();

        $updateResponse = $this->actingAs($this->admin, 'admin')->post(
            route('admin.web.contents.update', $vendorHero),
            [
                'title' => 'Accelerate Your Engineering Business with <span>FIX Enterprise Contracts</span>',
                'subtitle' => 'Exclusive facility management work orders across Dhaka and regional export zones.',
                'button_text' => 'Enlist Your Company Today',
                'button_url' => '#vendorFormSection',
                'stat_partners' => '750+',
                'stat_partners_label' => 'Certified Contractors',
                'stat_payouts' => '৳ 5.0 Cr+',
                'stat_payouts_label' => 'Disbursed Vendor Payouts',
                'stat_verification' => '24h',
                'stat_verification_label' => 'Express Onboarding',
            ]
        );

        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success');

        $vendorPage = $this->get('/vendor');
        $vendorPage->assertStatus(200);
        $vendorPage->assertSee('Accelerate Your Engineering Business with');
        $vendorPage->assertSee('FIX Enterprise Contracts');
        $vendorPage->assertSee('750+');
        $vendorPage->assertSee('Certified Contractors');
        $vendorPage->assertSee('৳ 5.0 Cr+');
    }

    /**
     * Test updating legal privacy policy in CMS updates the live privacy page.
     */
    public function test_updating_legal_privacy_updates_live_privacy_page(): void
    {
        $legalPrivacy = SiteContent::where('section_key', 'legal_privacy')->firstOrFail();

        $updateResponse = $this->actingAs($this->admin, 'admin')->post(
            route('admin.web.contents.update', $legalPrivacy),
            [
                'title' => 'FIX Enterprise Privacy & Data Governance Policy',
                'subtitle' => 'Comprehensive data protection and customer privacy charter.',
                'badge' => 'SECURITY & COMPLIANCE',
                'legal_sections' => [
                    [
                        'heading' => '1. Enterprise Data Encryption & Audits',
                        'body' => 'All operational interactions and facility logs are encrypted using TLS 1.3 and audited regularly.',
                    ],
                    [
                        'heading' => '2. Third-Party Service Dispatch Privacy',
                        'body' => 'Only certified lead engineers receive verified site contact details strictly for executing dispatch orders.',
                    ],
                ],
            ]
        );

        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success');

        $privacyPage = $this->get('/privacy');
        $privacyPage->assertStatus(200);
        $privacyPage->assertSee('FIX Enterprise Privacy &amp; Data Governance Policy', false);
        $privacyPage->assertSee('1. Enterprise Data Encryption &amp; Audits', false);
        $privacyPage->assertSee('All operational interactions and facility logs are encrypted using TLS 1.3');
    }

    /**
     * Test content sections are public for all visitors without login requirements in CMS modals.
     */
    public function test_content_sections_are_public_without_login_requirements_in_modals(): void
    {
        $supportSection = SiteContent::where('section_key', 'support_desk')->firstOrFail();

        $updateResponse = $this->actingAs($this->admin, 'admin')->post(
            route('admin.web.contents.update', $supportSection),
            [
                'title' => 'FIX Support Helpdesk &amp; Rapid Ticket Portal',
                'subtitle' => 'Public customer engineering and facility operations desk.',
            ]
        );

        $updateResponse->assertRedirect();
        $supportSection->refresh();
        $this->assertEquals('FIX Support Helpdesk &amp; Rapid Ticket Portal', $supportSection->title);
        $this->assertArrayNotHasKey('requires_login', $supportSection->meta_data ?? []);

        $cmsResponse = $this->actingAs($this->admin, 'admin')->get(route('admin.web.contents'));
        $cmsResponse->assertStatus(200);
        $cmsResponse->assertDontSee('Require Customer Login to View');
        $cmsResponse->assertDontSee('গ্রাহক লগইন আবশ্যক');
        $cmsResponse->assertDontSee('Login Required');
    }

    /**
     * Test web login route preserves intended target with require_login parameter.
     */
    public function test_login_route_preserves_intended_redirect(): void
    {
        $targetUrl = route('home').'#how-it-works';
        $response = $this->get('/login?redirect='.urlencode($targetUrl));

        $response->assertStatus(302);
        $response->assertRedirect($targetUrl.'?require_login=1');
        $response->assertSessionHas('trigger_auth_modal', true);
    }

    /**
     * Test homepage displays dynamic client logos and enterprise names without static dummy SVGs.
     */
    public function test_homepage_displays_dynamic_client_logos_and_titles(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Section title
        $response->assertSee('Trusted by Leading Enterprises & Facilities', false);

        // Enterprise names
        $response->assertSee('Standard Chartered Tower');
        $response->assertSee('Grameenphone GPHouse');
        $response->assertSee('Shanta Holdings Towers');
        $response->assertSee('Apex Footwear Complex');
        $response->assertSee('United Hospital Complex');

        // Dynamic logo images with object-fit: contain styling
        $response->assertSee('client-logo-img', false);
        $response->assertSee('uploads/clients/standard_chartered.svg', false);
        $response->assertSee('uploads/clients/grameenphone.svg', false);
    }

    /**
     * Test admin can update clients with names and logo uploads.
     */
    public function test_admin_can_update_clients_with_dynamic_names_and_logos(): void
    {
        $clientsSection = SiteContent::where('section_key', 'clients')->firstOrFail();

        $fakeLogo = UploadedFile::fake()->image('meghna_group.png', 400, 150);

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.web.contents.update', $clientsSection),
            [
                'title' => 'Trusted by 500+ Leading Enterprises &amp; Facilities',
                'clients' => [
                    [
                        'name' => 'Meghna Group Industrial Hub',
                        'existing_logo' => '',
                    ],
                    [
                        'name' => 'Akij Resource Center',
                        'existing_logo' => 'uploads/clients/standard_chartered.svg',
                    ],
                ],
                'client_logos' => [
                    0 => $fakeLogo,
                ],
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $clientsSection->refresh();
        $this->assertSame('Meghna Group Industrial Hub', $clientsSection->meta_data['clients'][0]['name']);
        $this->assertNotNull($clientsSection->meta_data['clients'][0]['logo']);
        $this->assertStringStartsWith('uploads/clients/', $clientsSection->meta_data['clients'][0]['logo']);

        // Verify on user website homepage
        $homeResponse = $this->get('http://localhost/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Meghna Group Industrial Hub');
        $homeResponse->assertSee('Akij Resource Center');

        // Clean up test uploaded image
        $uploadedPath = public_path($clientsSection->meta_data['clients'][0]['logo']);
        if (file_exists($uploadedPath)) {
            @unlink($uploadedPath);
        }
    }

    /**
     * Test all "View on Website" section anchor IDs and data-section keys exist in the user-facing web DOM.
     */
    public function test_view_on_website_anchor_targets_exist_on_user_web(): void
    {
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);

        // All 11 homepage section IDs match the links in View on Website
        $homeResponse->assertSee('id="hero"', false);
        $homeResponse->assertSee('id="clients"', false);
        $homeResponse->assertSee('id="services"', false);
        $homeResponse->assertSee('id="amc-plans"', false);
        $homeResponse->assertSee('id="how-it-works"', false);
        $homeResponse->assertSee('id="video-portfolio"', false);
        $homeResponse->assertSee('id="why-fix"', false);
        $homeResponse->assertSee('id="who-we-serve"', false);
        $homeResponse->assertSee('id="testimonials"', false);
        $homeResponse->assertSee('id="faq"', false);
        $homeResponse->assertSee('id="contact"', false);

        // Also ensure legacy/navbar anchor aliases exist
        $homeResponse->assertSee('id="amc"', false);
        $homeResponse->assertSee('id="howItWorks"', false);
        $homeResponse->assertSee('id="portfolioVideo"', false);
        $homeResponse->assertSee('id="whyFix"', false);
        $homeResponse->assertSee('id="whoWeServe"', false);

        // Vendor page anchors
        $vendorResponse = $this->get('/vendor');
        $vendorResponse->assertStatus(200);
        $vendorResponse->assertSee('id="vendor-hero"', false);
        $vendorResponse->assertSee('id="vendor-benefits"', false);

        // Legal page anchors
        $legalResponse = $this->get('/privacy');
        $legalResponse->assertStatus(200);
        $legalResponse->assertSee('id="legal-content"', false);

        // Support page anchors
        $supportResponse = $this->get('/support');
        $supportResponse->assertStatus(200);
        $supportResponse->assertSee('id="support-portal"', false);
    }

    /**
     * Test service details page displays interactive photo gallery and fixed dimensions.
     */
    public function test_service_details_page_renders_gallery_images_and_fixed_photo_showcase(): void
    {
        $service = Service::first();
        $service->update([
            'gallery_images' => [
                'uploads/services/test_photo_1.jpg',
                'uploads/services/test_photo_2.jpg',
            ],
        ]);

        $response = $this->get(route('services.show', $service->slug ?? $service->id));
        $response->assertStatus(200);
        $response->assertSee('Service Photo Gallery');
        $response->assertSee('Completed Work &amp; Inspection Gallery', false);
        $response->assertSee('uploads/services/test_photo_1.jpg');
        $response->assertSee('uploads/services/test_photo_2.jpg');
        $response->assertSee('switchServiceGalleryPhoto');
    }

    /**
     * Test CMS service edit form shows recommended image size hints without blocking upload.
     */
    public function test_cms_service_edit_displays_recommended_image_size_guidelines(): void
    {
        $service = Service::first();

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.cms.services.edit', $service));
        $response->assertStatus(200);
        $response->assertSee('Recommended Dimensions:');
        $response->assertSee('800 × 500 px');
        $response->assertSee('800 × 600 px');
    }

    /**
     * Test public About Us page loads, displays CMS content, and handles aliases.
     */
    public function test_about_us_page_loads_and_displays_cms_content(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('About Us');
        $response->assertSee('Pioneering Modern Facility Engineering');
        $response->assertSee('Our Mission');
        $response->assertSee('Our Vision');
        $response->assertSee('Milestones in Facility Engineering');
        $response->assertSee('12+');

        // Check alias redirect
        $aliasResponse = $this->get('/about-us');
        $aliasResponse->assertRedirect(route('about'));
    }

    /**
     * Test CMS web content settings includes and updates About Us sections.
     */
    public function test_cms_web_content_manages_about_us_sections(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.web.contents'));
        $response->assertStatus(200);
        $response->assertSee('About Us');
        $response->assertSee('1. About Us Hero &amp; Enterprise Metrics', false);
        $response->assertSee('2. Mission, Vision, Values &amp; Company Milestones', false);

        // Update about_hero content
        $aboutHero = SiteContent::forSection('about_hero');
        $updateResponse = $this->actingAs($this->admin, 'admin')->post(route('admin.web.contents.update', $aboutHero), [
            'title' => 'Updated Premier Facility Engineering in Bangladesh',
            'subtitle' => 'Custom updated subtitle for testing.',
            'stat_experience' => '15+',
            'stat_experience_label' => 'Decades of Leadership',
            'story_title' => 'Our Proven Legacy in Property Care',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('site_contents', [
            'section_key' => 'about_hero',
            'title' => 'Updated Premier Facility Engineering in Bangladesh',
        ]);

        // Check update reflects on public about page
        $publicResponse = $this->get('/about');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Updated Premier Facility Engineering in Bangladesh');
        $publicResponse->assertSee('15+');
    }

    /**
     * Test section active status can be toggled via quick toggle button and hides from live website.
     */
    public function test_section_active_status_can_be_toggled_via_cms_and_hides_from_user_web(): void
    {
        $clientsContent = SiteContent::forSection('clients');
        $this->assertNotNull($clientsContent);
        $this->assertTrue((bool) $clientsContent->is_active);

        // Verify clients section is initially visible on homepage
        $initialHome = $this->get('http://localhost/');
        $initialHome->assertStatus(200);
        $initialHome->assertSee('id="clients"', false);

        // Toggle to inactive (hidden)
        $toggleResponse = $this->actingAs($this->admin, 'admin')->post(
            route('admin.web.contents.toggle', $clientsContent)
        );
        $toggleResponse->assertRedirect();
        $toggleResponse->assertSessionHas('success');

        $this->assertDatabaseHas('site_contents', [
            'id' => $clientsContent->id,
            'is_active' => false,
        ]);

        // CMS now displays Hidden badge and Show Section button
        $cmsResponse = $this->actingAs($this->admin, 'admin')->get(route('admin.web.contents'));
        $cmsResponse->assertStatus(200);
        $cmsResponse->assertSee('Hidden / Inactive');
        $cmsResponse->assertSee('Show Section');

        // User web homepage now hides the clients section
        $hiddenHome = $this->get('http://localhost/');
        $hiddenHome->assertStatus(200);
        $hiddenHome->assertDontSee('id="clients"', false);

        // Toggle back to active
        $reToggleResponse = $this->actingAs($this->admin, 'admin')->post(
            route('admin.web.contents.toggle', $clientsContent)
        );
        $reToggleResponse->assertRedirect();
        $this->assertDatabaseHas('site_contents', [
            'id' => $clientsContent->id,
            'is_active' => true,
        ]);

        // Homepage shows it again
        $activeHome = $this->get('http://localhost/');
        $activeHome->assertStatus(200);
        $activeHome->assertSee('id="clients"', false);
    }

    /**
     * Test section active status can be updated via edit modal.
     */
    public function test_section_active_status_can_be_updated_in_edit_modal(): void
    {
        $whyFixContent = SiteContent::forSection('why_fix');
        $this->assertNotNull($whyFixContent);

        // Deactivate via modal update
        $updateResponse = $this->actingAs($this->admin, 'admin')->post(
            route('admin.web.contents.update', $whyFixContent),
            [
                'title' => $whyFixContent->title,
                'subtitle' => $whyFixContent->subtitle,
                'is_active' => '0',
            ]
        );
        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('site_contents', [
            'id' => $whyFixContent->id,
            'is_active' => false,
        ]);

        // Homepage should now hide why_fix section
        $home = $this->get('http://localhost/');
        $home->assertStatus(200);
        $home->assertDontSee('id="why-fix"', false);
    }

    /**
     * Test homepage service cards display rating, review count, and exclude removed subtitle.
     */
    public function test_homepage_service_cards_display_ratings_and_exclude_service_subtitle(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Verify rating and review count badges are displayed on cards
        $response->assertSee('service-rating-wrap', false);
        $response->assertSee('reviews');

        // Verify the removed text is NOT displayed anywhere
        $response->assertDontSee('Comprehensive engineering and facility maintenance solutions delivered across Bangladesh.');
        $response->assertDontSee('Common questions regarding service booking, emergency dispatch, AMC contracts, and billing.');
        $response->assertDontSee('Predictable operational expenditure and scheduled preventive checkups that minimize costly equipment breakdowns.');
        $response->assertDontSee('Getting professional maintenance for your facility is straightforward, transparent, and hassle-free in just 4 simple steps.');
        $response->assertDontSee('We combine skilled engineering, vetted vendor networks, and institutional-grade accountability to protect property value and enhance liveability.');
        $response->assertDontSee('Tailored engineering and facility maintenance protocols across diverse industries and property classes.');
        $response->assertDontSee('Hear from building managers, facility heads, and property owners who trust FIX.');
        $response->assertDontSee('Have an urgent facility breakdown or need an on-site engineering audit for your commercial property?');
    }

    /**
     * Test CMS FAQ category is a dropdown and modal headers/buttons are clean without duplicates.
     */
    public function test_cms_faq_category_dropdown_and_clean_buttons(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.web.faqs'));
        $response->assertStatus(200);

        // Verify Category select dropdown exists
        $response->assertSee('<select name="category"', false);
        $response->assertSee('<option value="services"', false);

        // Verify clean Add button text (no duplicated "+ +")
        $response->assertSee('<span>Add New FAQ</span>', false);
        $response->assertDontSee('<span>+ Add New FAQ</span>', false);
    }

    /**
     * Test CMS Testimonials has clean buttons and edit modal footer has padding.
     */
    public function test_cms_testimonials_buttons_and_edit_modal(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.web.testimonials'));
        $response->assertStatus(200);

        // Verify clean Add button text
        $response->assertSee('<span>Add Testimonial</span>', false);
        $response->assertDontSee('<span>+ Add Testimonial</span>', false);

        // Verify edit modal has proper padding on footer
        $response->assertSee('modal-footer-paces', false);
        $response->assertSee('padding:1.25rem 1.5rem', false);
    }

    /**
     * Test CMS content edit modals have "View on Website" button and no raw Live Preview text.
     */
    public function test_cms_content_edit_modals_have_view_on_website_button(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.web.contents'));
        $response->assertStatus(200);

        // Modals must feature View on Website button
        $response->assertSee('View on Website');
        $response->assertDontSee('Live Preview:');
    }
}
