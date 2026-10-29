<?php

namespace App\Services;

class AdminPermissionService
{
    /**
     * Complete registry of all CMS modules and their granular permissions.
     *
     * @var array<string, array{name: string, description: string, permissions: array<string, string>}>
     */
    public const MODULES = [
        'dashboard' => [
            'name' => 'Dashboard & KPIs',
            'description' => 'View operational overview, charts, sales analytics, and quick status metrics.',
            'permissions' => [
                'dashboard.view' => 'View Dashboard & Metrics',
            ],
        ],
        'bookings' => [
            'name' => 'Work Orders, AMC Contracts & Outreach',
            'description' => 'Customer work orders, field technician dispatching, AMC contract activation, urgency SLA, and billing.',
            'permissions' => [
                'bookings.view' => 'View Work Orders & Details',
                'bookings.edit' => 'Update Status, Activate AMC, Assign Staff & Add Logs',
            ],
        ],
        'tickets' => [
            'name' => 'Support Tickets Desk',
            'description' => 'Customer technical and service tickets, real-time messaging, status lifecycle.',
            'permissions' => [
                'tickets.view' => 'View Support Tickets & Chats',
                'tickets.edit' => 'Create Tickets, Send Replies, Open/Close/Reopen',
            ],
        ],
        'users' => [
            'name' => 'Client Users & Partner Crews',
            'description' => 'Registered customer accounts, partner applications, crew approvals, and access toggles.',
            'permissions' => [
                'users.view' => 'View Client Profiles & Partner Crews',
                'users.edit' => 'Edit Clients, Approve Partners & Manage Access',
            ],
        ],
        'services' => [
            'name' => 'Services Catalog',
            'description' => 'Facility maintenance services, base pricing, emergency SLA hours, photo uploads.',
            'permissions' => [
                'services.view' => 'View Services & Categories',
                'services.edit' => 'Create, Edit, Update Pricing, SLA & Photos',
            ],
        ],
        'amc_plans' => [
            'name' => 'AMC Maintenance Packages',
            'description' => 'Annual maintenance contracts, tiers, pricing periods, and featured highlights.',
            'permissions' => [
                'amc_plans.view' => 'View AMC Packages',
                'amc_plans.edit' => 'Create & Edit AMC Packages, Rates & Features',
            ],
        ],
        'web_content' => [
            'name' => 'Web Content (FAQs & Testimonials)',
            'description' => 'Homepage marketing sections, FAQs knowledgebase, and verified client testimonials.',
            'permissions' => [
                'web_content.view' => 'View Web Content & Knowledgebase',
                'web_content.edit' => 'Create, Edit & Delete Sections, FAQs & Reviews',
            ],
        ],
        'settings' => [
            'name' => 'Site Settings & SMTP Gateway',
            'description' => 'Branding assets, emergency hotlines, live email gateway setup and test dispatch.',
            'permissions' => [
                'settings.view' => 'View Site Settings & SMTP Config',
                'settings.edit' => 'Update Branding, SMTP Credentials & Send Tests',
            ],
        ],
        'analytics' => [
            'name' => 'Regional Area Analytics',
            'description' => 'National division distribution, district demand hotspots, and service volume metrics.',
            'permissions' => [
                'analytics.view' => 'View Regional Hotspots & Statistics',
            ],
        ],
        'staff_management' => [
            'name' => 'Admin Staff & Role Permissions',
            'description' => 'Create admin staff, assign module access permissions, reset credentials.',
            'permissions' => [
                'staff_management.view' => 'View Admin Staff Directory',
                'staff_management.edit' => 'Create & Edit Staff Accounts & Permissions',
            ],
        ],
        'activity_logs' => [
            'name' => 'Activity Logs & Audit Trail',
            'description' => 'Complete chronological security history of all administrative events, logins, and data updates.',
            'permissions' => [
                'activity_logs.view' => 'View Activity & Audit Logs',
            ],
        ],
    ];

    /**
     * Get all modules definition.
     *
     * @return array<string, array{name: string, description: string, permissions: array<string, string>}>
     */
    public static function allModules(): array
    {
        return self::MODULES;
    }

    /**
     * Get all available permission keys flat array.
     *
     * @return list<string>
     */
    public static function allPermissionKeys(): array
    {
        $keys = [];
        foreach (self::MODULES as $module) {
            foreach (array_keys($module['permissions']) as $k) {
                $keys[] = $k;
            }
        }

        return $keys;
    }

    /**
     * Get all view-only permission keys flat array.
     *
     * @return list<string>
     */
    public static function allViewOnlyKeys(): array
    {
        return array_values(array_filter(self::allPermissionKeys(), function (string $key) {
            return str_ends_with($key, '.view');
        }));
    }
}
