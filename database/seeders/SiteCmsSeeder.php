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

        // 2. Site Contents (Page Sections)
        $contents = [
            [
                'section_key' => 'hero',
                'title' => 'Your Trusted Partner for <span>Every Building</span> Service',
                'subtitle' => null,
                'badge' => null,
                'button_text' => 'Book a Service',
                'button_url' => '/booking',
                'secondary_button_text' => 'Explore Services',
                'secondary_button_url' => '#services',
                'media_path' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1000&q=80',
                'media_type' => 'image',
                'meta_data' => [
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
                'section_key' => 'video_portfolio',
                'title' => 'Watch FIX in Action: <span>Real Project Operations</span>',
                'subtitle' => null, // Kept removed per user request
                'badge' => null,
                'button_text' => 'Request Site Inspection',
                'button_url' => '/booking',
                'media_path' => 'https://www.youtube.com/embed/LXb3EKWsInQ?rel=0',
                'media_type' => 'embed',
                'meta_data' => [
                    'default_title' => 'Commercial Central HVAC & Chiller Overhaul',
                    'default_meta' => 'Standard Chartered Tower • Banani, Dhaka',
                    'playlist' => [
                        [
                            'video_id' => 'LXb3EKWsInQ',
                            'title' => 'Commercial Central HVAC & Chillers',
                            'meta' => 'Cooling Tower AMC • Dhaka Metro',
                            'thumb' => 'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=300&q=80',
                        ],
                        [
                            'video_id' => 'LXb3EKWsInQ',
                            'title' => 'High-Rise Facade Cleaning & Rope Access',
                            'meta' => 'Glass & ACP Wash • Gulshan Avenue',
                            'thumb' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?auto=format&fit=crop&w=300&q=80',
                        ],
                        [
                            'video_id' => 'LXb3EKWsInQ',
                            'title' => 'Industrial Substation & ATS Switchgear',
                            'meta' => 'Preventive Audit • Gazipur EPZ',
                            'thumb' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=300&q=80',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(['section_key' => $content['section_key']], $content);
        }
    }
}
