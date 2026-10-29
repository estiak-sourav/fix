<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRoleAndPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        /** @var Admin $superAdmin */
        $superAdmin = Admin::where('role', 'super_admin')->first();
        $this->superAdmin = $superAdmin;
    }

    /**
     * 1. Super Admin can view staff list and create a new staff account with default password & permissions.
     */
    public function test_super_admin_can_create_staff_with_default_password_and_permissions(): void
    {
        $payload = [
            'name' => 'Support Agent Raihan',
            'email' => 'raihan@fix.com.bd',
            'phone' => '+8801711223344',
            'role' => 'admin',
            'password' => 'password123',
            'must_change_password' => '1',
            'permissions' => [
                'dashboard.view',
                'bookings.view',
                'tickets.view',
                'tickets.edit',
            ],
        ];

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.staff.store'), $payload);

        $response->assertRedirect(route('admin.staff.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admins', [
            'email' => 'raihan@fix.com.bd',
            'name' => 'Support Agent Raihan',
            'role' => 'admin',
            'must_change_password' => true,
            'is_active' => true,
        ]);

        $created = Admin::where('email', 'raihan@fix.com.bd')->first();
        $this->assertNotNull($created);
        $this->assertTrue(Hash::check('password123', $created->password));
        $this->assertTrue($created->hasPermission('bookings.view'));
        $this->assertFalse($created->hasPermission('bookings.edit'));
        $this->assertTrue($created->hasPermission('tickets.edit'));
        $this->assertTrue($created->hasPermission('tickets.view')); // Implicit grant via edit
        $this->assertFalse($created->hasPermission('users.view'));
    }

    /**
     * 2. New staff with must_change_password=true is redirected to password change on login.
     */
    public function test_staff_with_must_change_password_is_redirected_to_change_password_after_login(): void
    {
        $staff = Admin::create([
            'name' => 'New Dispatcher',
            'email' => 'dispatcher@fix.com.bd',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => true,
            'permissions' => ['dashboard.view', 'bookings.view'],
        ]);

        $loginResponse = $this->post(route('admin.login'), [
            'email' => 'dispatcher@fix.com.bd',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect(route('admin.password.change'));
        $loginResponse->assertSessionMissing('warning');

        $this->assertAuthenticatedAs($staff, 'admin');
    }

    /**
     * 3. Staff with must_change_password=true cannot access dashboard or other routes before updating password.
     */
    public function test_staff_cannot_bypass_password_change_middleware(): void
    {
        $staff = Admin::create([
            'name' => 'Blocked Staff',
            'email' => 'blocked@fix.com.bd',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => true,
            'permissions' => ['dashboard.view', 'bookings.view'],
        ]);

        // Attempting to visit dashboard
        $response = $this->actingAs($staff, 'admin')->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.password.change'));

        // Attempting to visit bookings
        $bookingsResponse = $this->actingAs($staff, 'admin')->get(route('admin.bookings.index'));
        $bookingsResponse->assertRedirect(route('admin.password.change'));

        // Viewing password change form is allowed
        $formResponse = $this->actingAs($staff, 'admin')->get(route('admin.password.change'));
        $formResponse->assertOk();
    }

    /**
     * 4. Staff can update default password, which clears must_change_password flag and grants full access.
     */
    public function test_staff_can_successfully_update_password_and_access_permitted_routes(): void
    {
        $staff = Admin::create([
            'name' => 'Active Staff',
            'email' => 'active.staff@fix.com.bd',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => true,
            'permissions' => ['dashboard.view', 'bookings.view'],
        ]);

        // Failed attempt: New password matches current password
        $failResponse = $this->actingAs($staff, 'admin')->post(route('admin.password.update'), [
            'current_password' => 'password123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $failResponse->assertSessionHasErrors(['password']);
        $this->assertEquals('The new password must be different from your current password.', session('errors')->first('password'));

        // Failed attempt: Incorrect current password
        $wrongCurrResponse = $this->actingAs($staff, 'admin')->post(route('admin.password.update'), [
            'current_password' => 'wrong_password',
            'password' => 'SecureNewPassword2026!',
            'password_confirmation' => 'SecureNewPassword2026!',
        ]);
        $wrongCurrResponse->assertSessionHasErrors(['current_password']);
        $this->assertEquals('The current password you entered is incorrect.', session('errors')->first('current_password'));

        // Successful password update
        $successResponse = $this->actingAs($staff, 'admin')->post(route('admin.password.update'), [
            'current_password' => 'password123',
            'password' => 'SecureNewPassword2026!',
            'password_confirmation' => 'SecureNewPassword2026!',
        ]);

        $successResponse->assertRedirect(route('admin.dashboard'));
        $successResponse->assertSessionHas('success');

        $staff->refresh();
        $this->assertFalse($staff->must_change_password);
        $this->assertTrue(Hash::check('SecureNewPassword2026!', $staff->password));

        // Now visiting password change as an active admin shows clean "Change Password" form without temporary password text
        $changeAgainResponse = $this->actingAs($staff, 'admin')->get(route('admin.password.change'));
        $changeAgainResponse->assertOk();
        $changeAgainResponse->assertSee('Change Password');
        $changeAgainResponse->assertSee('Current Password');
        $changeAgainResponse->assertDontSee('First-Login Password Reset Required');

        // Now dashboard is accessible
        $dashResponse = $this->actingAs($staff, 'admin')->get(route('admin.dashboard'));
        $dashResponse->assertOk();
    }

    /**
     * 5. View-only staff can view bookings but cannot submit edit/update actions.
     */
    public function test_view_only_permission_allows_viewing_but_blocks_modifications(): void
    {
        $staff = Admin::create([
            'name' => 'View Only Auditor',
            'email' => 'auditor@fix.com.bd',
            'password' => Hash::make('securePassword!'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
            'permissions' => ['bookings.view'],
        ]);

        /** @var Booking $booking */
        $booking = Booking::first();

        // View bookings list: 200 OK
        $listResponse = $this->actingAs($staff, 'admin')->get(route('admin.bookings.index'));
        $listResponse->assertOk();

        // View booking details: 200 OK
        $showResponse = $this->actingAs($staff, 'admin')->get(route('admin.bookings.show', $booking));
        $showResponse->assertOk();
        $showResponse->assertSee('Read-Only Access');

        // Attempting to update status without bookings.edit permission: 403 Forbidden
        $editResponse = $this->actingAs($staff, 'admin')->post(route('admin.bookings.update-status', $booking), [
            'status' => 'completed',
        ]);
        $editResponse->assertForbidden();
    }

    /**
     * 6. Admin without permission gets 403 Forbidden.
     */
    public function test_user_without_permission_is_forbidden(): void
    {
        $staff = Admin::create([
            'name' => 'Junior Clerk',
            'email' => 'clerk@fix.com.bd',
            'password' => Hash::make('securePassword!'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
            'permissions' => ['tickets.view'],
        ]);

        // Attempting to access client users list without users.view permission
        $response = $this->actingAs($staff, 'admin')->get(route('admin.users.index'));
        $response->assertForbidden();

        // Attempting to access staff management
        $staffResponse = $this->actingAs($staff, 'admin')->get(route('admin.staff.index'));
        $staffResponse->assertForbidden();
    }

    /**
     * 7. Super Admin has unrestricted bypass across all modules.
     */
    public function test_super_admin_bypasses_all_permission_checks(): void
    {
        $this->assertTrue($this->superAdmin->isSuperAdmin());
        $this->assertTrue($this->superAdmin->hasPermission('dashboard.view'));
        $this->assertTrue($this->superAdmin->hasPermission('bookings.edit'));
        $this->assertTrue($this->superAdmin->hasPermission('staff_management.edit'));

        $response = $this->actingAs($this->superAdmin, 'admin')->get(route('admin.staff.index'));
        $response->assertOk();
    }

    /**
     * 8. Quick reset password sets default password and requires change on next login.
     */
    public function test_quick_reset_password_resets_credentials_and_enforces_change(): void
    {
        $staff = Admin::create([
            'name' => 'Staff To Reset',
            'email' => 'toreset@fix.com.bd',
            'password' => Hash::make('oldComplicatedPassword!'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
            'permissions' => ['dashboard.view'],
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.staff.reset-password', $staff));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $staff->refresh();
        $this->assertTrue($staff->must_change_password);
        $this->assertTrue(Hash::check('password123', $staff->password));
    }

    /**
     * 9. View-only staff user does not see edit or management buttons in CMS and Services.
     */
    public function test_view_only_staff_does_not_see_edit_buttons(): void
    {
        $viewerStaff = Admin::create([
            'name' => 'Read Only Officer',
            'email' => 'viewer@fix.com.bd',
            'password' => Hash::make('password123!'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
            'permissions' => [
                'dashboard.view',
                'web_content.view',
                'services.view',
                'amc_plans.view',
                'settings.view',
            ],
        ]);

        // 1. Web contents page: Should see content but NOT 'Edit Section'
        $contentsRes = $this->actingAs($viewerStaff, 'admin')->get(route('admin.web.contents'));
        $contentsRes->assertOk();
        $contentsRes->assertDontSee('Edit Section');
        $contentsRes->assertDontSee('Hide Section');

        // 2. FAQs page: Should NOT see edit modal trigger button
        $faqsRes = $this->actingAs($viewerStaff, 'admin')->get(route('admin.web.faqs'));
        $faqsRes->assertOk();
        $faqsRes->assertDontSee('onclick="openEditFaqModal');
        $faqsRes->assertSee('View Only');

        // 3. Testimonials page: Should NOT see edit modal trigger button
        $testimonialsRes = $this->actingAs($viewerStaff, 'admin')->get(route('admin.web.testimonials'));
        $testimonialsRes->assertOk();
        $testimonialsRes->assertDontSee('onclick="openEditTestimonialModal');
        $testimonialsRes->assertSee('View Only');

        // 4. Services list: Should NOT see 'Add New Service'
        $servicesRes = $this->actingAs($viewerStaff, 'admin')->get(route('admin.web.services'));
        $servicesRes->assertOk();
        $servicesRes->assertDontSee('Add New Service');
        $servicesRes->assertSee('View Only');

        // 5. AMC Plans page: Should NOT see '+ Add New Package'
        $amcRes = $this->actingAs($viewerStaff, 'admin')->get(route('admin.web.amc-plans'));
        $amcRes->assertOk();
        $amcRes->assertDontSee('+ Add New Package');

        // 6. Settings page: Should NOT see 'Save Site Settings' submit button
        $settingsRes = $this->actingAs($viewerStaff, 'admin')->get(route('admin.web.settings'));
        $settingsRes->assertOk();
        $settingsRes->assertDontSee('Save Site Settings');
    }

    /**
     * 10. Staff with edit permissions or Super Admin sees all edit buttons, My Profile link, and modal markup.
     */
    public function test_editor_staff_and_super_admin_sees_edit_buttons_and_profile(): void
    {
        $editorStaff = Admin::create([
            'name' => 'Editor Officer',
            'email' => 'editor@fix.com.bd',
            'password' => Hash::make('password123!'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
            'permissions' => [
                'web_content.edit',
                'services.edit',
                'amc_plans.edit',
                'settings.edit',
            ],
        ]);

        $contentsRes = $this->actingAs($editorStaff, 'admin')->get(route('admin.web.contents'));
        $contentsRes->assertOk();
        $contentsRes->assertSee('Edit Section');

        $faqsRes = $this->actingAs($editorStaff, 'admin')->get(route('admin.web.faqs'));
        $faqsRes->assertOk();
        $faqsRes->assertSee('onclick="openEditFaqModal', false);

        $testimonialsRes = $this->actingAs($editorStaff, 'admin')->get(route('admin.web.testimonials'));
        $testimonialsRes->assertOk();
        $testimonialsRes->assertSee('onclick="openEditTestimonialModal', false);

        $servicesRes = $this->actingAs($editorStaff, 'admin')->get(route('admin.web.services'));
        $servicesRes->assertOk();
        $servicesRes->assertSee('Add New Service');

        $amcRes = $this->actingAs($editorStaff, 'admin')->get(route('admin.web.amc-plans'));
        $amcRes->assertOk();
        $amcRes->assertSee('+ Add New Package');

        // Check Profile Page is accessible and layout has confirmation modal & burger toggle
        $profileRes = $this->actingAs($editorStaff, 'admin')->get(route('admin.profile'));
        $profileRes->assertOk();
        $profileRes->assertSee('My Profile');
        $profileRes->assertSee('Update DP');
        $profileRes->assertSee('adminConfirmModal');
        $profileRes->assertSee('btn-topbar-toggle');
        $profileRes->assertSee('btn-sidebar-collapse');
    }
}
