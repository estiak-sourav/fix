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
            ['key' => 'site_tagline', 'value' => 'Clean • Safe • Sustainable', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'site_description', 'value' => 'FIX is your trusted partner for complete building maintenance and facility management solutions in Bangladesh. We protect property value and enhance liveability through certified engineering.', 'group' => 'general', 'label' => 'Site Description'],
            ['key' => 'emergency_phone', 'value' => '+880 1700-000000', 'group' => 'contact', 'label' => 'Emergency Hotline'],
            ['key' => 'support_email', 'value' => 'support@fix.com.bd', 'group' => 'contact', 'label' => 'Support Email'],
            ['key' => 'head_office_address', 'value' => 'Plot 12, Road 11, Block D, Banani, Dhaka-1213, Bangladesh', 'group' => 'contact', 'label' => 'Head Office Address'],
            ['key' => 'operating_hours', 'value' => "General Support: 8:00 AM – 10:00 PM (Daily)\nEmergency Callouts: 24/7 Available", 'group' => 'contact', 'label' => 'Operating Hours'],
            ['key' => 'coverage_area', 'value' => 'Dhaka North, Dhaka South, Gazipur, Savar, EPZ & Major Industrial Zones', 'group' => 'contact', 'label' => 'Service Coverage Area'],
            ['key' => 'header_logo', 'value' => 'assets/images/logo-dark-crop.png', 'group' => 'branding', 'label' => 'Header Dark Logo'],
            ['key' => 'footer_logo', 'value' => 'assets/images/logo-white-crop.png', 'group' => 'branding', 'label' => 'Footer Light Logo'],
            ['key' => 'meta_title', 'value' => 'FIX - Facility Management & Maintenance Services in Bangladesh', 'group' => 'seo', 'label' => 'Default SEO Title'],
            ['key' => 'meta_description', 'value' => 'Professional facility management, HVAC chillers, electrical, plumbing, fire safety & AMC services in Dhaka, Bangladesh.', 'group' => 'seo', 'label' => 'Default Meta Description'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/fixfacility', 'group' => 'social', 'label' => 'Facebook Page URL'],
            ['key' => 'facebook_active', 'value' => '1', 'group' => 'social', 'label' => 'Facebook Active'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/company/fixfacility', 'group' => 'social', 'label' => 'LinkedIn Page URL'],
            ['key' => 'linkedin_active', 'value' => '1', 'group' => 'social', 'label' => 'LinkedIn Active'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/fixfacility', 'group' => 'social', 'label' => 'Instagram Profile URL'],
            ['key' => 'instagram_active', 'value' => '1', 'group' => 'social', 'label' => 'Instagram Active'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/@fixfacility', 'group' => 'social', 'label' => 'YouTube Channel URL'],
            ['key' => 'youtube_active', 'value' => '1', 'group' => 'social', 'label' => 'YouTube Active'],
            ['key' => 'whatsapp_number', 'value' => '+8801700000000', 'group' => 'social', 'label' => 'WhatsApp Number'],
            ['key' => 'whatsapp_active', 'value' => '1', 'group' => 'social', 'label' => 'WhatsApp Active'],
            ['key' => 'email_header_ticket_created', 'value' => 'Engineering Helpdesk Confirmation', 'group' => 'email', 'label' => 'Ticket Created Email Subtitle'],
            ['key' => 'email_header_ticket_reply', 'value' => 'Engineering Helpdesk — New Response', 'group' => 'email', 'label' => 'Ticket Admin Reply Email Subtitle'],
            ['key' => 'email_header_otp', 'value' => 'Clean • Safe • Sustainable Engineering', 'group' => 'email', 'label' => 'OTP Email Subtitle'],
            ['key' => 'email_header_test_smtp', 'value' => 'Admin Command Center', 'group' => 'email', 'label' => 'SMTP Test Email Subtitle'],
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
                'meta_data' => [
                    'partner_names' => [
                        'Standard Chartered Tower',
                        'Grameenphone GPHouse',
                        'Shanta Holdings Towers',
                        'Apex Footwear Complex',
                        'United Hospital Complex',
                        'Square Pharmaceuticals',
                        'Beximco Industrial Park',
                        'BRAC Centre & Campus',
                    ],
                    'clients' => [
                        ['name' => 'Standard Chartered Tower', 'logo' => 'uploads/clients/standard_chartered.svg'],
                        ['name' => 'Grameenphone GPHouse', 'logo' => 'uploads/clients/grameenphone.svg'],
                        ['name' => 'Shanta Holdings Towers', 'logo' => 'uploads/clients/shanta_holdings.svg'],
                        ['name' => 'Apex Footwear Complex', 'logo' => 'uploads/clients/apex_footwear.svg'],
                        ['name' => 'United Hospital Complex', 'logo' => 'uploads/clients/united_hospital.svg'],
                        ['name' => 'Square Pharmaceuticals', 'logo' => 'uploads/clients/square_pharma.svg'],
                        ['name' => 'Beximco Industrial Park', 'logo' => 'uploads/clients/beximco.svg'],
                        ['name' => 'BRAC Centre & Campus', 'logo' => 'uploads/clients/brac.svg'],
                    ],
                ],
            ],
            [
                'section_key' => 'services',
                'title' => 'Our Core Maintenance Capabilities',
                'subtitle' => null,
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
                'subtitle' => null,
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
                'subtitle' => null,
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
                'subtitle' => null,
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
                'subtitle' => null,
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
                'subtitle' => null,
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
                'subtitle' => null,
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
                'subtitle' => null,
                'badge' => 'OPERATIONS COMMAND',
                'button_text' => 'Send Message',
                'button_url' => '#contact',
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'form',
                'meta_data' => [
                    'card_title' => 'Operations Command & Technical Support',
                    'card_desc' => 'Have an urgent maintenance request or need facility engineering assistance? Contact our central operations center 24/7.',
                ],
            ],
            // 12. Become a Partner - Hero & Statistics
            [
                'section_key' => 'vendor_hero',
                'title' => 'Grow Your Facility Service Business with <span>Institutional Work Orders</span>',
                'subtitle' => "Partner with Bangladesh's unified facility operations network. Gain steady access to corporate towers, hospitals, factories, and commercial complexes with guaranteed bi-weekly payouts and zero customer acquisition cost.",
                'badge' => 'SERVICE PARTNER ENLISTMENT',
                'button_text' => 'Apply for Enlistment',
                'button_url' => '#vendorFormSection',
                'secondary_button_text' => 'Hotline Support',
                'secondary_button_url' => 'tel:+8801700000000',
                'media_path' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=800&q=80',
                'media_type' => 'image',
                'meta_data' => [
                    'stat_partners' => '500+',
                    'stat_partners_label' => 'Enlisted Vendor Partners',
                    'stat_payouts' => '৳ 2.5 Cr+',
                    'stat_payouts_label' => 'Partner Payouts Disbursed',
                    'stat_verification' => '24-48h',
                    'stat_verification_label' => 'Fast-Track Verification',
                    'floating_badge_top_title' => 'Guaranteed Corporate Payouts',
                    'floating_badge_top_text' => 'Direct Bank & bKash Bi-Weekly Settlement',
                    'floating_badge_bottom_title' => '4.9 Partner Rating',
                    'floating_badge_bottom_text' => '98% Contractor Retention Across Dhaka & EPZ',
                ],
            ],
            // 13. Become a Partner - Benefits & Perks
            [
                'section_key' => 'vendor_benefits',
                'title' => 'Why Top Technical Contractors Partner with FIX',
                'subtitle' => 'We manage corporate sales, compliance, client billing, and dispatch — so you can focus 100% on quality service execution.',
                'badge' => 'PARTNER BENEFITS',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'cards',
                'meta_data' => [
                    'perks' => [
                        [
                            'title' => 'Consistent Commercial Orders',
                            'description' => 'Gain direct access to corporate headquarters, hospitals, EPZ industrial facilities, and luxury residences without spending on marketing.',
                        ],
                        [
                            'title' => 'Guaranteed Timely Payments',
                            'description' => 'Transparent job completion verification with automatic bi-weekly or monthly disbursements directly to your company bank or bKash account.',
                        ],
                        [
                            'title' => 'Free Safety Gear & ID Kits',
                            'description' => 'Enlisted partner technicians receive standardized FIX professional uniform vests, high-altitude harnesses, safety kits, and verified photo ID badges.',
                        ],
                    ],
                ],
            ],
            // 14. Legal - Privacy Policy
            [
                'section_key' => 'legal_privacy',
                'title' => 'Privacy Policy',
                'subtitle' => 'Information collection, data security, cookie tracking, and data protection practices across our platform.',
                'badge' => null,
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'sections' => [
                        [
                            'heading' => '1. Information We Collect',
                            'body' => 'When you use FIX, request building facility dispatches, subscribe to Annual Maintenance Contracts (AMC), or communicate with our operations center, we may collect personal details such as your name, corporate organization, email address, phone number, physical site address, and equipment logs.',
                        ],
                        [
                            'heading' => '2. How We Use Your Data',
                            'body' => 'We utilize your data to dispatch certified engineering technicians, provide booking status notifications, generate invoices, perform scheduled preventive maintenance, and safeguard security across all operational interactions.',
                        ],
                        [
                            'heading' => '3. Cookies & Analytical Tracking',
                            'body' => 'Our platform utilizes cookies to remember session authentication, optimize navigation performance, and analyze operational trends. You may control cookie preferences through browser settings or our on-page cookie consent banner.',
                        ],
                        [
                            'heading' => '4. Contacting Data Protection',
                            'body' => 'For inquiries regarding privacy, data access requests, or policy clarification, please contact our administrative operations center at support@fix.com.bd or call our hotline.',
                        ],
                    ],
                ],
            ],
            // 15. Legal - Terms of Service
            [
                'section_key' => 'legal_terms',
                'title' => 'Terms of Service',
                'subtitle' => 'Standard commercial terms and conditions governing facility booking, subscriptions, and work orders.',
                'badge' => null,
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'sections' => [
                        [
                            'heading' => '1. Scope of Services',
                            'body' => 'FIX Facility Management Services provides professional facility engineering, electrical maintenance, commercial HVAC services, fire safety inspections, plumbing dispatches, and scheduled Annual Maintenance Contracts (AMC) across Bangladesh.',
                        ],
                        [
                            'heading' => '2. Service Booking & Dispatch Agreements',
                            'body' => 'Booking estimates provided via the online portal represent initial scope assessments. Final service costs are confirmed upon physical site diagnostic inspection by certified lead technicians. All work orders follow approved safety guidelines and industry SLA standards.',
                        ],
                        [
                            'heading' => '3. Annual Maintenance Contracts (AMC)',
                            'body' => 'AMC plan subscriptions are billed per tier terms (Monthly or Annual billing cycle). Subscribers enjoy scheduled preventive inspections, emergency priority dispatches, and dedicated customer support desk access.',
                        ],
                        [
                            'heading' => '4. Support & Resolution',
                            'body' => 'For support requests, service guarantees, or contract terms inquiries, reach out to our team at support@fix.com.bd.',
                        ],
                    ],
                ],
            ],
            // 16. Legal - SLA Policy
            [
                'section_key' => 'legal_sla',
                'title' => 'Service Level Agreement (SLA) & Guarantee',
                'subtitle' => 'Guaranteed emergency response dispatch times, routine maintenance schedules, and 30-day workmanship warranty.',
                'badge' => null,
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'sections' => [
                        [
                            'heading' => '1. Emergency Response Dispatch SLA',
                            'body' => 'For critical facility emergencies—including primary electrical tripping, severe plumbing burst flooding, central HVAC chiller shutoffs, and fire safety alarms—FIX guarantees lead technician dispatch within 60 minutes across metropolitan Dhaka and regional economic zones.',
                        ],
                        [
                            'heading' => '2. Scheduled Preventive Maintenance SLA',
                            'body' => 'All Annual Maintenance Contract (AMC) visits are scheduled 48 hours in advance. Preventative routine inspections follow standardized BNBC engineering checklists, and digital inspection logs are delivered to property managers within 24 hours of task completion.',
                        ],
                        [
                            'heading' => '3. 30-Day Certified Workmanship Guarantee',
                            'body' => 'All non-cosmetic electrical repairs, plumbing fittings, HVAC servicing, and mechanical installations performed by certified FIX field technicians include a 30-day quality and workmanship warranty. If a recurring defect arises within the guarantee window under standard operational parameters, our crew returns for rectification without additional diagnostic dispatch charges.',
                        ],
                        [
                            'heading' => '4. Verified Engineering & Safety Standards',
                            'body' => 'All dispatched field staff undergo police background verification, technical vetting, and OSHA safety protocol training. We maintain rigorous standards to ensure the highest safety and reliability for commercial facilities and residential properties.',
                        ],
                        [
                            'heading' => '5. Escalations & Support SLA',
                            'body' => 'For SLA inquiries, guarantee claims, or operations escalation, contact our 24/7 Operations Command Desk at support@fix.com.bd or call our emergency hotline.',
                        ],
                    ],
                ],
            ],
            // 17. Support Helpdesk
            [
                'section_key' => 'support_desk',
                'title' => 'Submit a Support Ticket',
                'subtitle' => 'Need facility maintenance or emergency engineering assistance? Submit your request below for rapid dispatch.',
                'badge' => '24/7 CUSTOMER HELPDESK',
                'button_text' => 'Track Existing Ticket',
                'button_url' => '/support/track',
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => null,
            ],
            // 18. About Us - Hero & Enterprise Stats
            [
                'section_key' => 'about_hero',
                'title' => 'Pioneering Modern Facility Engineering in Bangladesh',
                'subtitle' => 'FIX is Bangladesh\'s premier technology-driven facility management company, delivering MEP engineering, HVAC solutions, and compliant operations for commercial towers, factories, and residences.',
                'badge' => null,
                'button_text' => 'Explore Our Services',
                'button_url' => '/#services',
                'secondary_button_text' => 'Get in Touch',
                'secondary_button_url' => '/support',
                'media_path' => null,
                'media_type' => 'image',
                'meta_data' => [
                    'stat_experience' => '12+',
                    'stat_experience_label' => 'Years of Engineering Excellence',
                    'stat_clients' => '150+',
                    'stat_clients_label' => 'Enterprise Facilities Managed',
                    'stat_technicians' => '250+',
                    'stat_technicians_label' => 'Certified Engineers & Technicians',
                    'stat_satisfaction' => '99.4%',
                    'stat_satisfaction_label' => 'SLA Reliability & Compliance Rate',
                    'story_title' => 'Building Bangladesh\'s Most Trusted Facility Infrastructure',
                    'story_p1' => 'Founded with a clear vision to revolutionize property care, FIX provides end-to-end building operations. From certified electrical compliance and chiller overhauls to green building sustainability and round-the-clock emergency dispatch, we keep critical infrastructure running flawlessly.',
                    'story_p2' => 'Headquartered in Banani, Dhaka, our rapid dispatch technical squads cover Dhaka Metropolitan, Gazipur, Savar, Narayanganj, and major Export Processing Zones (EPZ).',
                ],
            ],
            // 19. About Us - Mission, Vision & Core Values
            [
                'section_key' => 'about_overview',
                'title' => 'Our Mission, Vision & Core Engineering Principles',
                'subtitle' => null,
                'badge' => null,
                'button_text' => 'Book a Facility Inspection',
                'button_url' => '/booking',
                'secondary_button_text' => 'Become a Service Partner',
                'secondary_button_url' => '/vendor',
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'mission_title' => 'Our Mission',
                    'mission_desc' => 'To elevate facility management standards across Bangladesh through certified technical expertise, 24/7 rapid response, and zero-compromise environmental and occupational safety.',
                    'vision_title' => 'Our Vision',
                    'vision_desc' => 'To be the most reliable, tech-enabled, and sustainable integrated facility management partner for world-class enterprises, export zones, and commercial properties.',
                    'values' => [
                        [
                            'title' => 'BNBC & NFPA Certified Safety',
                            'desc' => 'Strict adherence to national and international building codes, fire safety, and electrical hazard prevention.',
                        ],
                        [
                            'title' => '2-Hour Emergency SLA',
                            'desc' => 'Rapid technical squads stationed at regional hubs for immediate crisis mitigation and critical dispatch.',
                        ],
                        [
                            'title' => 'Transparent Digital Tracking',
                            'desc' => 'Every maintenance request is digitally documented with verified before-after logs and satisfaction tracking.',
                        ],
                        [
                            'title' => 'Eco-Friendly & Sustainable',
                            'desc' => 'Energy-efficient HVAC optimization, non-toxic commercial chemicals, and preventive asset lifecycle planning.',
                        ],
                    ],
                    'milestones' => [
                        [
                            'year' => '2014',
                            'title' => 'MEP Operations Founded',
                            'desc' => 'Launched specialized electrical and mechanical facility engineering services in Dhaka.',
                        ],
                        [
                            'year' => '2018',
                            'title' => 'Enterprise Expansion',
                            'desc' => 'Expanded to 50+ corporate headquarters and EPZ manufacturing facilities.',
                        ],
                        [
                            'year' => '2022',
                            'title' => '24/7 Command Center',
                            'desc' => 'Built our real-time centralized emergency dispatch control room.',
                        ],
                        [
                            'year' => '2026',
                            'title' => 'Unified Digital Platform',
                            'desc' => 'Integrated self-service customer booking, transparent AMC tracking, and digital vendor ecosystem.',
                        ],
                    ],
                ],
            ],
            // 20. Crew & Partner - Terms & Conditions
            [
                'section_key' => 'partner_terms',
                'title' => 'Crew & Partner Terms of Service',
                'subtitle' => 'Operational terms, dispatch acceptance standards, technician obligations, and platform partnership agreements.',
                'badge' => 'CREW CONTRACT',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'sections' => [
                        [
                            'heading' => '1. Contractor Onboarding & Identity Verification',
                            'body' => 'All field crew and partner technicians must submit valid National ID (NID) or Passport, verified trade licenses, technical certifications, and pass mandatory police background clearance prior to account activation and work order dispatch.',
                        ],
                        [
                            'heading' => '2. Work Order Acceptance & Arrival SLAs',
                            'body' => 'Once an assigned or open work order is accepted through the Partner Portal, the technician is expected to arrive at the designated customer facility within the agreed arrival window. Delays exceeding 15 minutes must be communicated immediately through the dispatch desk.',
                        ],
                        [
                            'heading' => '3. Standard Tooling, PPE & Identification',
                            'body' => 'Crew members must arrive equipped with certified multi-meters, insulated hand tools, high-visibility FIX uniform vests, safety footwear, and an active digital or physical FIX photo ID badge prominently displayed at all times.',
                        ],
                        [
                            'heading' => '4. Transparent Quotations & Authentic Spare Parts',
                            'body' => 'Only genuine replacement components approved by FIX and accepted by the client may be installed. Unreported surcharges, off-platform cash transactions, or deceptive scope expansion are strictly prohibited and will result in immediate contract cancellation.',
                        ],
                        [
                            'heading' => '5. Non-Circumvention & Client Exclusivity',
                            'body' => 'Field crew members and subcontracted partners agree not to solicit or accept direct off-platform service engagements from FIX residential or corporate clients introduced through the platform for a period of 12 months following assignment.',
                        ],
                    ],
                ],
            ],
            // 21. Crew & Partner - Work Policy & Safety SOP
            [
                'section_key' => 'partner_work_policy',
                'title' => 'Crew Work Policy & Safety SOP',
                'subtitle' => 'Standard operating procedures, BNBC electrical and HVAC safety compliance, customer etiquette, and site conduct.',
                'badge' => 'SAFETY & CONDUCT',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'sections' => [
                        [
                            'heading' => '1. Uniform Code & Professional Appearance',
                            'body' => 'All technicians must present a neat, professional appearance wearing the company-issued FIX uniform vest, clean safety shoes, and photo badge. Personal Protective Equipment (PPE) including helmets, goggles, and cut-resistant gloves must be worn according to the job hazards.',
                        ],
                        [
                            'heading' => '2. Electrical & Mechanical Lockout/Tagout (LOTO)',
                            'body' => 'Before servicing high-voltage distribution panels, industrial chillers, or pressurized hydraulic lines, technicians must enforce strict Lockout/Tagout procedures. De-energized circuits must be tested with calibrated voltage detectors before physical contact.',
                        ],
                        [
                            'heading' => '3. Site Cleanliness & Property Protection',
                            'body' => 'Protective drop cloths must be laid beneath equipment before servicing ceiling AC units, conduits, or plumbing fixtures. Following service completion, the work area must be vacuumed, degreased, and left cleaner than originally found.',
                        ],
                        [
                            'heading' => '4. Customer Etiquette & Code of Conduct',
                            'body' => 'Communicate with facility managers and homeowners courteously and professionally. Always explain diagnostic findings and receive client approval before starting repairs. Smoking, chewing betel leaf/tobacco, resting on client furniture, or using residential facilities is strictly prohibited.',
                        ],
                        [
                            'heading' => '5. Zero-Tolerance Safety Violations',
                            'body' => 'Reporting to work under the influence of alcohol or narcotics, engaging in verbal or physical confrontation, theft, or deliberate safety bypass results in instantaneous contract termination, security blacklisting, and referral to law enforcement.',
                        ],
                    ],
                ],
            ],
            // 22. Crew & Partner - Privacy & Confidentiality Policy
            [
                'section_key' => 'partner_privacy',
                'title' => 'Crew Privacy & Data Protection Policy',
                'subtitle' => 'Guidelines governing technician personal data, dispatch location telemetry, and strict client confidentiality standards.',
                'badge' => 'PRIVACY & SECURITY',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'sections' => [
                        [
                            'heading' => '1. Technician Personal Data Protection',
                            'body' => 'FIX securely stores contractor identity verification documents, bank account details, payout histories, and background check records in encrypted storage. This information is processed exclusively for payroll, compliance, and regulatory audits.',
                        ],
                        [
                            'heading' => '2. Real-Time Dispatch GPS Telemetry',
                            'body' => 'Location telemetry is collected through mobile devices solely while a technician has an active work order in "En Route" or "In Progress" status. This data is used to provide accurate client arrival times and ensure lone-worker safety during emergency dispatches.',
                        ],
                        [
                            'heading' => '3. Strict Client Non-Disclosure & Privacy',
                            'body' => 'Technicians are granted temporary access to client addresses, contact numbers, and building entry instructions solely for job fulfillment. Crew members are strictly forbidden from photographing private residential spaces, recording conversations, or disclosing client details to third parties.',
                        ],
                        [
                            'heading' => '4. Financial & Payout Record Confidentiality',
                            'body' => 'Individual partner earnings, commission rates, incentive bonuses, and payout requests are treated as confidential financial records and will never be disclosed to other partners or unauthorized staff.',
                        ],
                    ],
                ],
            ],
            // 23. Crew & Partner - Payout & Earnings Settlement Policy
            [
                'section_key' => 'partner_payout_policy',
                'title' => 'Crew Payout & Earnings Settlement Policy',
                'subtitle' => 'Rules governing job earnings, commission splits, payout withdrawal thresholds, processing turnaround, and warranty reserves.',
                'badge' => 'PAYOUT & EARNINGS',
                'button_text' => null,
                'button_url' => null,
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'media_path' => null,
                'media_type' => 'text',
                'meta_data' => [
                    'sections' => [
                        [
                            'heading' => '1. Earning Accrual & Transparent Breakdown',
                            'body' => 'Work order earnings are credited to the partner\'s available balance immediately upon digital customer sign-off and administrative job closure. The payout ledger reflects net earnings after agreed platform revenue shares.',
                        ],
                        [
                            'heading' => '2. Minimum Payout Threshold & Request Cycles',
                            'body' => 'Partners may request withdrawal of accumulated earnings anytime the available balance reaches or exceeds ৳500. Payout requests submitted on business days before 4:00 PM are processed within 24 to 48 hours.',
                        ],
                        [
                            'heading' => '3. Supported Receiving Methods',
                            'body' => 'Disbursements can be routed to verified Mobile Financial Services (bKash, Nagad, Rocket, Upay) or direct Electronic Fund Transfer (BEFTN/NPSB) to registered Bangladeshi commercial bank accounts. Technicians may set a preferred primary payout method in Settings.',
                        ],
                        [
                            'heading' => '4. 30-Day Workmanship Warranty Recalls & Deductions',
                            'body' => 'Under our 30-day workmanship guarantee, if a client experiences a recurring service failure caused by technician assembly or installation defect, the original crew is dispatched for complimentary rectification. Negligent failures or unfulfilled recalls may result in adjustment against pending payouts.',
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
