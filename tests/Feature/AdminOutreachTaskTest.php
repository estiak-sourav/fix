<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\OutreachTask;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOutreachTaskTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = Admin::first();
    }

    protected function createTestBooking(array $attributes = []): Booking
    {
        $user = User::first() ?? User::factory()->create();

        return Booking::create(array_merge([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'service_type' => 'on_demand',
            'customer_name' => 'Default Customer',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '01711223344',
            'property_type' => 'residential',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'area' => 'Banani',
            'street_address' => 'House 10, Road 5, Banani',
            'preferred_date' => now()->toDateString(),
            'preferred_time_slot' => 'Morning',
            'urgency' => 'standard',
            'problem_description' => 'Electrical wiring inspection and troubleshooting.',
            'status' => 'pending_review',
        ], $attributes));
    }

    /**
     * Test unreachable call log automatically creates a high priority outreach task.
     */
    public function test_logging_unreachable_call_creates_automated_outreach_task(): void
    {
        $booking = $this->createTestBooking([
            'status' => 'pending_review',
            'customer_name' => 'Rahim Ahmed',
            'customer_phone' => '01711000001',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.bookings.log-call', $booking),
            [
                'call_status' => 'unreachable',
                'notes' => 'Customer phone was switched off during confirmation call.',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('outreach_tasks', [
            'booking_id' => $booking->id,
            'task_type' => 'call_unreachable',
            'customer_name' => 'Rahim Ahmed',
            'customer_phone' => '01711000001',
            'priority' => 'high',
            'status' => 'pending',
            'is_automated' => true,
        ]);
    }

    /**
     * Test callback requested call log automatically creates a scheduled outreach task.
     */
    public function test_logging_callback_requested_creates_automated_outreach_task_with_due_time(): void
    {
        $booking = $this->createTestBooking([
            'status' => 'pending_review',
            'customer_name' => 'Karim Hasan',
            'customer_phone' => '01811000002',
        ]);

        $followUp = now()->addHours(3)->format('Y-m-d\TH:i');

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.bookings.log-call', $booking),
            [
                'call_status' => 'call_back_later',
                'follow_up_time' => $followUp,
                'notes' => 'Client is in a meeting, requested call at 4 PM.',
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('outreach_tasks', [
            'booking_id' => $booking->id,
            'task_type' => 'call_back_later',
            'customer_name' => 'Karim Hasan',
            'customer_phone' => '01811000002',
            'priority' => 'high',
            'status' => 'pending',
            'is_automated' => true,
        ]);
    }

    /**
     * Test logging call as connected completes pending outreach tasks.
     */
    public function test_logging_connected_call_auto_completes_pending_tasks(): void
    {
        $booking = $this->createTestBooking([
            'status' => 'pending_review',
            'customer_name' => 'Tanvir Alam',
            'customer_phone' => '01911000003',
        ]);

        $task = OutreachTask::create([
            'booking_id' => $booking->id,
            'title' => 'Follow up booking #'.$booking->booking_number,
            'task_type' => 'call_unreachable',
            'customer_name' => $booking->customer_name,
            'customer_phone' => $booking->customer_phone,
            'priority' => 'high',
            'status' => 'pending',
            'is_automated' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.bookings.log-call', $booking),
            [
                'call_status' => 'connected',
                'notes' => 'Successfully reached customer and verified details.',
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('outreach_tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Test confirming/accepting a booking completes pending outreach tasks.
     */
    public function test_accepting_booking_auto_completes_pending_tasks(): void
    {
        $booking = $this->createTestBooking([
            'status' => 'pending_review',
            'customer_name' => 'Jamal Hossain',
            'customer_phone' => '01611000004',
        ]);

        $task = OutreachTask::create([
            'booking_id' => $booking->id,
            'title' => 'Follow up booking #'.$booking->booking_number,
            'task_type' => 'call_unreachable',
            'customer_name' => $booking->customer_name,
            'customer_phone' => $booking->customer_phone,
            'priority' => 'high',
            'status' => 'pending',
            'is_automated' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.bookings.accept', $booking),
            ['pricing_mode' => 'later']
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('outreach_tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Test admin can manually create an outreach task.
     */
    public function test_admin_can_manually_create_outreach_task(): void
    {
        $booking = $this->createTestBooking([
            'status' => 'pending_review',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.outreach-tasks.store'),
            [
                'booking_id' => $booking->id,
                'title' => 'Collect alternative contact number',
                'task_type' => 'manual_followup',
                'customer_name' => 'Fahim Rahman',
                'customer_phone' => '01511000005',
                'priority' => 'medium',
                'notes' => 'Ask for office manager number.',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('outreach_tasks', [
            'booking_id' => $booking->id,
            'title' => 'Collect alternative contact number',
            'task_type' => 'manual_followup',
            'customer_name' => 'Fahim Rahman',
            'priority' => 'normal',
            'status' => 'pending',
            'is_automated' => false,
            'created_by_admin_id' => $this->admin->id,
        ]);
    }

    /**
     * Test admin can mark an outreach task as completed.
     */
    public function test_admin_can_mark_outreach_task_as_completed(): void
    {
        $task = OutreachTask::create([
            'title' => 'Manual Follow-up Call',
            'task_type' => 'manual_followup',
            'customer_name' => 'Saif Khan',
            'customer_phone' => '01700998877',
            'priority' => 'medium',
            'status' => 'pending',
            'is_automated' => false,
            'created_by_admin_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.outreach-tasks.complete', $task)
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('outreach_tasks', [
            'id' => $task->id,
            'status' => 'completed',
            'completed_by_admin_id' => $this->admin->id,
        ]);
    }

    /**
     * Test admin can delete an outreach task.
     */
    public function test_admin_can_delete_outreach_task(): void
    {
        $task = OutreachTask::create([
            'title' => 'Test Task to Delete',
            'task_type' => 'manual_followup',
            'customer_name' => 'Test User',
            'customer_phone' => '01700998877',
            'priority' => 'low',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->delete(
            route('admin.outreach-tasks.destroy', $task)
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('outreach_tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test admin can create a simple outreach task with just a title.
     */
    public function test_admin_can_create_simple_outreach_task_with_just_title(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.outreach-tasks.store'),
            [
                'title' => 'Urgent procurement of filter pads',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('outreach_tasks', [
            'title' => 'Urgent procurement of filter pads',
            'booking_id' => null,
            'user_id' => null,
            'customer_name' => null,
            'customer_phone' => null,
            'priority' => 'normal',
            'status' => 'pending',
            'is_automated' => false,
            'created_by_admin_id' => $this->admin->id,
        ]);
    }

    /**
     * Test admin can create an outreach task linked to a registered user.
     */
    public function test_admin_can_create_task_linked_to_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Kashif Alam',
            'phone' => '01999887766',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(
            route('admin.outreach-tasks.store'),
            [
                'title' => 'Follow up on corporate service contract',
                'user_id' => $user->id,
                'priority' => 'high',
                'notes' => 'Discuss discounted commercial HVAC servicing.',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('outreach_tasks', [
            'title' => 'Follow up on corporate service contract',
            'user_id' => $user->id,
            'customer_name' => 'Kashif Alam',
            'customer_phone' => '01999887766',
            'priority' => 'high',
            'notes' => 'Discuss discounted commercial HVAC servicing.',
        ]);
    }

    /**
     * Test admin can update an existing outreach task.
     */
    public function test_admin_can_update_outreach_task(): void
    {
        $task = OutreachTask::create([
            'title' => 'Initial Task Title',
            'task_type' => 'general',
            'priority' => 'normal',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->put(
            route('admin.outreach-tasks.update', $task),
            [
                'title' => 'Updated Task Title With Urgent Due Date',
                'priority' => 'urgent',
                'status' => 'completed',
                'notes' => 'Action successfully resolved via phone call.',
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('outreach_tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title With Urgent Due Date',
            'priority' => 'urgent',
            'status' => 'completed',
            'notes' => 'Action successfully resolved via phone call.',
            'completed_by_admin_id' => $this->admin->id,
        ]);
    }

    /**
     * Test dashboard renders outreach tasks section and dynamic tasks.
     */
    public function test_dashboard_renders_outreach_tasks(): void
    {
        $task = OutreachTask::create([
            'title' => 'Verify Customer Site Address',
            'task_type' => 'location_verification',
            'priority' => 'urgent',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Outreach Tasks');
        $response->assertSee('Verify Customer Site Address');
        $response->assertSee('Create Task');
        $response->assertSee('View All Tasks');
        $response->assertSee('taskDetailsModal');
        $response->assertDontSee('View All Pending Bookings');
    }

    /**
     * Test admin can access dedicated outreach tasks index page.
     */
    public function test_admin_can_view_outreach_tasks_index_page(): void
    {
        $task = OutreachTask::create([
            'title' => 'Annual HVAC Maintenance Call',
            'task_type' => 'general',
            'priority' => 'high',
            'status' => 'pending',
            'notes' => 'Reach client to propose preventative maintenance.',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.outreach-tasks.index'));
        $response->assertStatus(200);
        $response->assertSee('Outreach Tasks');
        $response->assertSee('Annual HVAC Maintenance Call');
        $response->assertSee('Pending Tasks');
        $response->assertSee('Completed');
        $response->assertSee('Urgent & High', false);
        $response->assertSee('taskDetailsModal');
    }

    /**
     * Test admin can filter tasks on outreach tasks index page.
     */
    public function test_admin_can_filter_outreach_tasks_on_index_page(): void
    {
        OutreachTask::create([
            'title' => 'Pending Filter Task',
            'priority' => 'urgent',
            'status' => 'pending',
        ]);

        OutreachTask::create([
            'title' => 'Finished Filter Task',
            'priority' => 'normal',
            'status' => 'completed',
        ]);

        $responsePending = $this->actingAs($this->admin, 'admin')->get(route('admin.outreach-tasks.index', ['status' => 'pending']));
        $responsePending->assertStatus(200);
        $responsePending->assertSee('Pending Filter Task');
        $responsePending->assertDontSee('Finished Filter Task');

        $responseCompleted = $this->actingAs($this->admin, 'admin')->get(route('admin.outreach-tasks.index', ['status' => 'completed']));
        $responseCompleted->assertStatus(200);
        $responseCompleted->assertSee('Finished Filter Task');
        $responseCompleted->assertDontSee('Pending Filter Task');
    }
}
