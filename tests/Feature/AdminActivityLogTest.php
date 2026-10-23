<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminActivityLog;
use App\Models\Booking;
use App\Models\SupportTicket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
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
     * 1. Admin login generates an authentication activity log.
     */
    public function test_login_creates_activity_log(): void
    {
        $this->post(route('admin.login'), [
            'email' => $this->superAdmin->email,
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $this->superAdmin->id,
            'category' => 'auth',
            'action' => 'auth.login',
        ]);
    }

    /**
     * 2. Staff management action creates a staff activity log with metadata.
     */
    public function test_staff_creation_creates_activity_log(): void
    {
        $payload = [
            'name' => 'Auditor Karim',
            'email' => 'karim@fix.com.bd',
            'phone' => '+8801811223344',
            'role' => 'admin',
            'password' => 'password123',
            'must_change_password' => '1',
            'permissions' => ['bookings.view'],
        ];

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.staff.store'), $payload);

        $response->assertRedirect(route('admin.staff.index'));

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $this->superAdmin->id,
            'category' => 'staff',
            'action' => 'staff.create',
        ]);

        $log = AdminActivityLog::where('action', 'staff.create')->latest()->first();
        $this->assertNotNull($log);
        $this->assertStringContainsString('Auditor Karim', $log->description);
        $this->assertEquals('admin', $log->properties['role'] ?? null);
    }

    /**
     * 3. Booking status update creates a work order activity log.
     */
    public function test_booking_status_update_creates_activity_log(): void
    {
        /** @var Booking $booking */
        $booking = Booking::first();

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.bookings.update-status', $booking), [
                'status' => 'accepted',
                'assigned_team' => 'Rapid Response Team A',
                'comment' => 'Confirmed with facility manager',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $this->superAdmin->id,
            'category' => 'bookings',
            'action' => 'booking.status_update',
            'subject_id' => (string) $booking->id,
        ]);
    }

    /**
     * 4. Support ticket reply creates a tickets activity log.
     */
    public function test_ticket_reply_creates_activity_log(): void
    {
        $ticket = SupportTicket::create([
            'ticket_number' => 'TCK-2026-TEST',
            'customer_name' => 'Test Client',
            'customer_email' => 'client@test.com',
            'subject' => 'AC Not Cooling',
            'priority' => 'medium',
            'status' => 'open',
            'created_by' => 'customer',
            'access_token' => 'test-token-12345',
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.tickets.reply', $ticket), [
                'message' => 'Technician dispatched to your premises.',
                'status' => 'in_progress',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $this->superAdmin->id,
            'category' => 'tickets',
            'action' => 'ticket.reply',
            'subject_id' => (string) $ticket->id,
        ]);
    }

    /**
     * 5. Client status toggle creates a user activity log.
     */
    public function test_user_toggle_status_creates_activity_log(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.users.toggle-status', $user));

        $response->assertRedirect();

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $this->superAdmin->id,
            'category' => 'users',
            'action' => 'user.status_toggle',
            'subject_id' => (string) $user->id,
        ]);
    }

    /**
     * 6. Permitted admin can view activity logs index.
     */
    public function test_permitted_admin_can_view_activity_logs(): void
    {
        // Super admin has unrestricted access
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.activity-logs.index'));

        $response->assertOk();
        $response->assertSee('Administrative Activity Logs');
    }

    /**
     * 7. Staff without activity_logs.view permission is blocked with 403 Forbidden.
     */
    public function test_unpermitted_staff_cannot_view_activity_logs(): void
    {
        $staff = Admin::create([
            'name' => 'Restricted Staff',
            'email' => 'restricted@fix.com.bd',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
            'permissions' => ['dashboard.view'],
        ]);

        $response = $this->actingAs($staff, 'admin')
            ->get(route('admin.activity-logs.index'));

        $response->assertForbidden();
    }

    /**
     * 8. Filter by category filters the activity logs properly.
     */
    public function test_activity_logs_can_be_filtered_by_category(): void
    {
        AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'admin_email' => $this->superAdmin->email,
            'category' => 'staff',
            'action' => 'staff.create',
            'description' => 'Created staff account for test filtering',
        ]);

        AdminActivityLog::create([
            'admin_id' => $this->superAdmin->id,
            'admin_name' => $this->superAdmin->name,
            'admin_email' => $this->superAdmin->email,
            'category' => 'bookings',
            'action' => 'booking.status_update',
            'description' => 'Updated booking status for test filtering',
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.activity-logs.index', ['category' => 'staff']));

        $response->assertOk();
        $response->assertSee('Created staff account for test filtering');
        $response->assertDontSee('Updated booking status for test filtering');
    }
}
