<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteCmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Site Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'FIX Facility Management', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'value' => 'Certified Commercial & Residential Facility Engineering in Bangladesh', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'emergency_phone', 'value' => '+880 1700-000000', 'group' => 'contact', 'label' => 'Emergency Hotline'],
            ['key' => 'support_email', 'value' => 'support@fix.com.bd', 'group' => 'contact', 'label' => 'Support Email'],
            ['key' => 'head_office_address', 'value' => 'Plot 12, Road 11, Block D, Banani, Dhaka-1213, Bangladesh', 'group' => 'contact', 'label' => 'Head Office Address'],
            ['key' => 'operating_hours', 'value' => '24/7 Operations Command Center', 'group' => 'contact', 'label' => 'Operating Hours'],
            ['key' => 'header_logo', 'value' => 'assets/images/logo-dark-crop.png', 'group' => 'branding', 'label' => 'Header Dark Logo'],
            ['key' => 'footer_logo', 'value' => 'assets/images/logo-white-crop.png', 'group' => 'branding', 'label' => 'Footer Light Logo'],
            ['key' => 'meta_title', 'value' => 'FIX - Facility Management & Maintenance Services in Bangladesh', 'group' => 'seo', 'label' => 'Default SEO Title'],
            ['key' => 'meta_description', 'value' => 'Professional facility management, HVAC chillers, electrical, plumbing, fire safety & AMC services in Dhaka, Bangladesh.', 'group' => 'seo', 'label' => 'Default Meta Description'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 2. Site Contents (All Page Sections for CMS Control)
        $contents = [
            [
                'section_key' => 'hero',
                'title' => 'Your Trusted Partner for <span>Every Building</span> Service',
                'subtitle' => 'Certified engineering, rapid dispatch, and transparent facility operations across Bangladesh.',
                'badge' => '24/7 NATIONWIDE SERVICE DISPATCH',
                'button_text' => 'Book a Service',
                'button_url' => '/booking',
                'secondary_button_text' => 'Explore Services',
                'secondary_button_url' => '#services',
                'media_path' => 'uploads/hero/slide_1.jpg',
                'media_type' => 'slides',
                'meta_data' => [
                    'slides' => [
                        'uploads/hero/slide_1.jpg',
                        'uploads/hero/slide_2.jpg',
                        'uploads/hero/slide_3.jpg',
                    ],
                    'floating_badge_title' => 'Quality Guaranteed',
                    'floating_badge_text' => '100% Verified Technical Teams & Rigorous Safety',
                    'trust_badges' => [
                        'Verified & Certified Providers',
                        'Quick Service Request & Dispatch',
                        'Multiple Building Solutions',
                        '24/7 Dedicated Operations Support',
                    ],
                ],
            ],
            [
                'section_key' => 'clients',
                'title' => 'Trusted by Leading Enterprises & Facilities',
                'subtitle' => 'Over 250+ Commercial Towers, Financial Institutions, and Industrial Complexes Managed.',
                'badge' => 'CORPORATE TRUST',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => null,
            ],
            [
                'section_key' => 'services',
                'title' => 'Our Core Maintenance Capabilities',
                'subtitle' => 'Comprehensive engineering and facility maintenance solutions delivered across Bangladesh.',
                'badge' => 'SERVICES & SOLUTIONS',
                'button_text' => 'Explore All Services',
                'button_url' => '#services',
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'grid',
                'meta_data' => null,
            ],
            [
                'section_key' => 'amc_plans',
                'title' => 'Annual Maintenance Contracts <span>(AMC)</span>',
                'subtitle' => 'Predictable operational expenditure and scheduled preventive checkups that minimize costly equipment breakdowns.',
                'badge' => 'PREVENTIVE CARE',
                'button_text' => 'Choose AMC Package',
                'button_url' => '#amc',
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'grid',
                'meta_data' => null,
            ],
            [
                'section_key' => 'how_it_works',
                'title' => 'How It Works',
                'subtitle' => 'Getting professional maintenance for your facility is straightforward, transparent, and hassle-free in just 4 simple steps.',
                'badge' => 'STREAMLINED PROCESS',
                'button_text' => 'Book Service Now',
                'button_url' => '/booking',
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'steps',
                'meta_data' => null,
            ],
            [
                'section_key' => 'video_portfolio',
                'title' => 'Watch FIX in Action: <span>Real Project Operations</span>',
                'subtitle' => 'Real facility interventions executed by our certified mechanical, electrical, and HVAC teams.',
                'badge' => 'PROJECT SHOWCASE',
                'button_text' => 'Request Site Inspection',
                'button_url' => '/booking',
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => 'https://www.youtube.com/embed/LXb3EKWsInQ?rel=0',
                'media_type' => 'embed',
                'meta_data' => [
                    'default_title' => 'Commercial Central HVAC & Chiller Overhaul',
                    'default_meta' => 'Standard Chartered Tower • Banani, Dhaka',
                ],
            ],
            [
                'section_key' => 'why_fix',
                'title' => 'Why Choose FIX?',
                'subtitle' => 'We combine skilled engineering, vetted vendor networks, and institutional-grade accountability to protect property value and enhance liveability.',
                'badge' => 'OUR VALUE PROPOSITION',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'grid',
                'meta_data' => null,
            ],
            [
                'section_key' => 'who_we_serve',
                'title' => 'Who We Serve',
                'subtitle' => 'Tailored engineering and facility maintenance protocols across diverse industries and property classes.',
                'badge' => 'DEDICATED CLIENTELE',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'grid',
                'meta_data' => null,
            ],
            [
                'section_key' => 'testimonials',
                'title' => 'What Our Clients Say',
                'subtitle' => 'Hear from building managers, facility heads, and property owners who trust FIX.',
                'badge' => 'CLIENT REVIEWS',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'slider',
                'meta_data' => null,
            ],
            [
                'section_key' => 'faq',
                'title' => 'Frequently Asked Questions',
                'subtitle' => 'Common questions regarding service booking, emergency dispatch, AMC contracts, and billing.',
                'badge' => 'HELP & SUPPORT',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'accordion',
                'meta_data' => null,
            ],
            [
                'section_key' => 'contact',
                'title' => 'Get in Touch with Our Operations Command',
                'subtitle' => 'Have an urgent facility breakdown or need an on-site engineering audit for your commercial property?',
                'badge' => 'OPERATIONS COMMAND',
                'button_text' => 'Send Message',
                'button_url' => '#contact',
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'form',
                'meta_data' => null,
            ],
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(['section_key' => $content['section_key']], $content);
        }
    }
}
