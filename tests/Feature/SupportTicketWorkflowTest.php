<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SupportTicket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * 1. Test guest user (without login) can generate a support ticket with email.
     */
    public function test_guest_can_create_ticket_without_logging_in(): void
    {
        $payload = [
            'name' => 'Monirul Islam',
            'email' => 'monirul.guest@example.com',
            'phone' => '01712345678',
            'subject' => 'AC Unit not cooling in 3rd floor meeting room',
            'priority' => 'high',
            'message' => 'Our meeting room AC stopped blowing cold air this morning. Need urgent technician visit.',
        ];

        $response = $this->post(route('support.store'), $payload);

        $this->assertDatabaseHas('support_tickets', [
            'customer_name' => 'Monirul Islam',
            'customer_email' => 'monirul.guest@example.com',
            'subject' => 'AC Unit not cooling in 3rd floor meeting room',
            'status' => 'open',
            'created_by' => 'customer',
        ]);

        $ticket = SupportTicket::where('customer_email', 'monirul.guest@example.com')->first();
        $this->assertNotNull($ticket);
        $this->assertNotNull($ticket->access_token);
        $this->assertNull($ticket->user_id);

        // Initial customer message created
        $this->assertDatabaseHas('support_ticket_messages', [
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'customer',
            'sender_name' => 'Monirul Islam',
            'message' => 'Our meeting room AC stopped blowing cold air this morning. Need urgent technician visit.',
        ]);

        // Admin notification created
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'support_ticket',
        ]);

        // Redirected to public viewer with token
        $response->assertRedirect(route('support.show', [
            'ticketNumber' => $ticket->ticket_number,
            'token' => $ticket->access_token,
        ]));

        // Guest can view ticket with token and see step-by-step indicator
        $viewRes = $this->get(route('support.show', [
            'ticketNumber' => $ticket->ticket_number,
            'token' => $ticket->access_token,
        ]));
        $viewRes->assertStatus(200);
        $viewRes->assertSee($ticket->ticket_number);
        $viewRes->assertSee('Step 1 ➔ Client Message');
        $viewRes->assertSee('Resolution Progress Pipeline (Step-by-Step Flow)');
    }

    /**
     * 2. Test guest can track ticket via ticket number and email.
     */
    public function test_guest_can_track_ticket_with_ticket_number_and_email(): void
    {
        $ticket = SupportTicket::factory()->create([
            'customer_name' => 'Ayesha Siddiqua',
            'customer_email' => 'ayesha@example.com',
            'subject' => 'Water pressure low in washrooms',
        ]);

        // 1. Visit track page
        $res = $this->get(route('support.track'));
        $res->assertStatus(200);
        $res->assertSee('Track Support Ticket');

        // 2. Submit track search with hash prefix and mixed case
        $searchRes = $this->post(route('support.track'), [
            'ticket_number' => '#'.strtolower($ticket->ticket_number),
            'email' => 'AYESHA@example.com',
        ]);

        $searchRes->assertRedirect(route('support.show', [
            'ticketNumber' => $ticket->ticket_number,
            'token' => $ticket->access_token,
        ]));
    }

    public function test_logged_in_user_sees_their_tickets_on_track_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Sourav Ahmed',
            'email' => 'sourav@example.com',
        ]);

        $ticket = SupportTicket::factory()->create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'subject' => 'AC cooling issues in office',
        ]);

        $res = $this->actingAs($user, 'web')->get(route('support.track'));
        $res->assertStatus(200);
        $res->assertSee('My Support Tickets');
        $res->assertSee('#'.$ticket->ticket_number);
        $res->assertSee('AC cooling issues in office');
        $res->assertSee(route('customer.tickets.show', $ticket->ticket_number));
    }

    /**
     * 3. Test authenticated user creates ticket and views it in user web portal.
     */
    public function test_authenticated_user_can_create_and_view_ticket_in_portal(): void
    {
        $user = User::factory()->create([
            'name' => 'Kamal Hossain',
            'email' => 'kamal@corporatedubai.com',
        ]);

        // Create ticket while logged in
        $res = $this->actingAs($user, 'web')->post(route('support.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '01711223344',
            'subject' => 'Elevator lift motor unusual vibration',
            'priority' => 'urgent',
            'message' => 'The passenger lift motor is producing grinding sounds on floors 4 to 7.',
        ]);

        $ticket = SupportTicket::where('subject', 'Elevator lift motor unusual vibration')->first();
        $this->assertNotNull($ticket);
        $this->assertEquals($user->id, $ticket->user_id);

        $res->assertRedirect(route('customer.tickets.show', $ticket->ticket_number));

        // Visit customer tickets index
        $indexRes = $this->actingAs($user, 'web')->get(route('customer.tickets.index'));
        $indexRes->assertStatus(200);
        $indexRes->assertSee($ticket->ticket_number);
        $indexRes->assertSee('Elevator lift motor unusual vibration');

        // Visit customer ticket details
        $detailRes = $this->actingAs($user, 'web')->get(route('customer.tickets.show', $ticket->ticket_number));
        $detailRes->assertStatus(200);
        $detailRes->assertSee('Support Conversation');
        $detailRes->assertSee('Elevator lift motor unusual vibration');
    }

    /**
     * 4. Test customer can reply to ticket and re-open if closed.
     */
    public function test_customer_reply_adds_message_and_reopens_closed_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = SupportTicket::factory()->closed()->forUser($user)->create([
            'subject' => 'Generator fuel calibration',
        ]);

        $ticket->messages()->create([
            'sender_type' => 'customer',
            'sender_id' => $user->id,
            'sender_name' => $user->name,
            'message' => 'Original request regarding generator fuel sensors.',
        ]);

        $this->assertEquals('closed', $ticket->status);

        // Customer posts reply
        $replyRes = $this->actingAs($user, 'web')->post(route('support.reply', $ticket->ticket_number), [
            'message' => 'The issue occurred again today during testing. Please inspect relay circuit.',
        ]);

        $replyRes->assertRedirect(route('customer.tickets.show', $ticket->ticket_number));

        // Ticket is now re-opened automatically
        $ticket->refresh();
        $this->assertEquals('open', $ticket->status);
        $this->assertNull($ticket->closed_at);
        $this->assertCount(2, $ticket->messages);

        // Check messages are displayed
        $detailRes = $this->actingAs($user, 'web')->get(route('customer.tickets.show', $ticket->ticket_number));
        $detailRes->assertSee('Original request regarding generator fuel sensors.');
        $detailRes->assertSee('The issue occurred again today during testing. Please inspect relay circuit.');
    }

    /**
     * 5. Test CMS admin can view tickets and filter them.
     */
    public function test_admin_can_view_ticket_command_center(): void
    {
        $admin = Admin::first();

        $ticket1 = SupportTicket::factory()->create(['status' => 'open', 'subject' => 'Chiller maintenance']);
        $ticket2 = SupportTicket::factory()->closed()->create(['subject' => 'CCTV inspection']);

        $res = $this->actingAs($admin, 'admin')->get(route('admin.tickets.index'));
        $res->assertStatus(200);
        $res->assertSee('Support Tickets Command Center');
        $res->assertSee('Chiller maintenance');
        $res->assertSee('CCTV inspection');
        $res->assertSee('Total Inquiries');

        // Filter open
        $openRes = $this->actingAs($admin, 'admin')->get(route('admin.tickets.index', ['status' => 'open']));
        $openRes->assertStatus(200);
        $openRes->assertSee('Chiller maintenance');
        $openRes->assertDontSee('CCTV inspection');
    }

    /**
     * 6. Test CMS admin can create a support ticket for a registered user.
     */
    public function test_admin_can_create_ticket_for_registered_user(): void
    {
        $admin = Admin::first();
        $user = User::factory()->create([
            'name' => 'Sabbir Ahmed',
            'email' => 'sabbir@holdings.com',
        ]);

        $payload = [
            'recipient_type' => 'registered',
            'user_id' => $user->id,
            'priority' => 'high',
            'subject' => 'Upcoming Substation Electrical Safety Audit',
            'message' => 'Dear Sabbir, our engineering team has scheduled safety inspections for your building substation next Tuesday.',
        ];

        $res = $this->actingAs($admin, 'admin')->post(route('admin.tickets.store'), $payload);

        $ticket = SupportTicket::where('subject', 'Upcoming Substation Electrical Safety Audit')->first();
        $this->assertNotNull($ticket);
        $this->assertEquals($user->id, $ticket->user_id);
        $this->assertEquals('sabbir@holdings.com', $ticket->customer_email);
        $this->assertEquals('admin', $ticket->created_by);

        $res->assertRedirect(route('admin.tickets.show', $ticket));

        // Initial message from admin
        $this->assertDatabaseHas('support_ticket_messages', [
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'admin',
        ]);
    }

    /**
     * 7. Test CMS admin can create a support ticket for a non-registered user email.
     */
    public function test_admin_can_create_ticket_for_non_registered_user_email(): void
    {
        $admin = Admin::first();

        $payload = [
            'recipient_type' => 'non_registered',
            'customer_name' => 'Fahim Morshed',
            'customer_email' => 'fahim.newlead@facility.com',
            'customer_phone' => '01987654321',
            'priority' => 'normal',
            'subject' => 'Facility Energy Audit Consultation Findings',
            'message' => 'Hello Fahim, we have finalized the preliminary HVAC and energy loss report for your commercial complex.',
        ];

        $res = $this->actingAs($admin, 'admin')->post(route('admin.tickets.store'), $payload);

        $ticket = SupportTicket::where('customer_email', 'fahim.newlead@facility.com')->first();
        $this->assertNotNull($ticket);
        $this->assertNull($ticket->user_id);
        $this->assertEquals('Fahim Morshed', $ticket->customer_name);

        $res->assertRedirect(route('admin.tickets.show', $ticket));
    }

    /**
     * 8. Test CMS admin can reply, close, and re-open support tickets.
     */
    public function test_admin_can_reply_close_and_reopen_ticket(): void
    {
        $admin = Admin::first();
        $ticket = SupportTicket::factory()->create([
            'status' => 'open',
            'subject' => 'Fire alarm sensor continuous beep',
        ]);

        $ticket->messages()->create([
            'sender_type' => 'customer',
            'sender_name' => $ticket->customer_name,
            'message' => 'Zone 4 panel showing loop break fault.',
        ]);

        // 1. Admin replies with status update to in_progress
        $replyRes = $this->actingAs($admin, 'admin')->post(route('admin.tickets.reply', $ticket), [
            'message' => 'Assigned safety engineer Farhan to inspect panel batteries and replace faulty optic sensor.',
            'status' => 'in_progress',
        ]);

        $replyRes->assertRedirect(route('admin.tickets.show', $ticket));
        $ticket->refresh();
        $this->assertEquals('in_progress', $ticket->status);
        $this->assertCount(2, $ticket->messages);

        // 2. Admin closes ticket
        $closeRes = $this->actingAs($admin, 'admin')->post(route('admin.tickets.update-status', $ticket), [
            'status' => 'closed',
        ]);
        $closeRes->assertRedirect();
        $ticket->refresh();
        $this->assertEquals('closed', $ticket->status);
        $this->assertNotNull($ticket->closed_at);

        // 3. Admin re-opens ticket
        $reopenRes = $this->actingAs($admin, 'admin')->post(route('admin.tickets.update-status', $ticket), [
            'status' => 'open',
        ]);
        $reopenRes->assertRedirect();
        $ticket->refresh();
        $this->assertEquals('open', $ticket->status);
        $this->assertNull($ticket->closed_at);
    }

    /**
     * 9. Test authenticated customer submitting contact form automatically links ticket to account.
     */
    public function test_contact_submission_by_authenticated_customer_links_ticket_to_account(): void
    {
        $user = User::factory()->create([
            'name' => 'Kaiser Ahmed',
            'email' => 'kaiser@square.com',
            'phone' => '01811223344',
        ]);

        $response = $this->actingAs($user)->postJson(route('contact.send'), [
            'name' => 'Kaiser Ahmed',
            'phone' => '01811223344',
            'email' => 'kaiser@square.com',
            'subject' => 'Cleanroom HVAC Balancing Request',
            'message' => 'Need differential pressure calibration on Cleanroom Suite B.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $ticket = SupportTicket::where('subject', 'Cleanroom HVAC Balancing Request')->first();
        $this->assertNotNull($ticket);
        $this->assertEquals($user->id, $ticket->user_id);
        $this->assertEquals('kaiser@square.com', $ticket->customer_email);

        $response->assertJson([
            'ticket_number' => $ticket->ticket_number,
            'track_url' => route('customer.tickets.show', $ticket->ticket_number),
        ]);
    }

    /**
     * 10. Test guest submitting contact form via standard POST redirects to public ticket view.
     */
    public function test_contact_submission_by_guest_via_form_redirects_to_ticket_show_page(): void
    {
        $response = $this->post(route('contact.send'), [
            'name' => 'Jannatul Ferdous',
            'phone' => '01999887766',
            'email' => 'jannat@dhakagroup.com',
            'subject' => 'Elevator Sensor Check',
            'message' => 'Elevator door obstruction detector needs maintenance.',
        ]);

        $ticket = SupportTicket::where('customer_email', 'jannat@dhakagroup.com')->first();
        $this->assertNotNull($ticket);
        $this->assertNull($ticket->user_id);

        $expectedUrl = route('support.show', [
            'ticketNumber' => $ticket->ticket_number,
            'token' => $ticket->access_token,
        ]);

        $response->assertRedirect($expectedUrl);
        $response->assertSessionHas('success');
    }

    /**
     * 11. Test GET /contact smoothly redirects to home anchor.
     */
    public function test_get_contact_redirects_to_home_contact_section(): void
    {
        $response = $this->get('/contact');
        $response->assertRedirect('/#contact');
    }
}
