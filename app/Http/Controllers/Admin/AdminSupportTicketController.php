<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCreateSupportTicketRequest;
use App\Http\Requests\Admin\AdminReplySupportTicketRequest;
use App\Mail\SupportTicketAdminReplyMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminSupportTicketController extends Controller
{
    /**
     * Display listing of support tickets with status & search filtering.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $priority = $request->query('priority', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = SupportTicket::with(['user', 'admin', 'messages']);

        if ($status !== 'all' && in_array($status, ['open', 'in_progress', 'closed'])) {
            $query->where('status', $status);
        }

        if ($priority !== 'all' && in_array($priority, ['low', 'normal', 'high', 'urgent'])) {
            $query->where('priority', $priority);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderByDesc('updated_at')->paginate(15)->withQueryString();

        $metrics = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];

        $users = User::orderBy('name')->take(100)->get(['id', 'name', 'email', 'phone', 'company_name']);

        return view('admin.tickets.index', compact('tickets', 'metrics', 'status', 'priority', 'search', 'users'));
    }

    /**
     * Show dedicated ticket creation form in Admin panel.
     */
    public function create(Request $request): View
    {
        $userId = $request->query('user_id');
        $selectedUser = $userId ? User::find($userId) : null;
        $users = User::orderBy('name')->get(['id', 'name', 'email', 'phone', 'company_name']);

        return view('admin.tickets.create', compact('selectedUser', 'users'));
    }

    /**
     * Store new support ticket initiated from CMS for registered or non-registered users.
     */
    public function store(AdminCreateSupportTicketRequest $request): RedirectResponse
    {
        $admin = auth('admin')->user();

        if ($request->recipient_type === 'registered') {
            $user = User::findOrFail($request->user_id);
            $name = $user->name;
            $email = $user->email;
            $phone = $user->phone;
            $userId = $user->id;
        } else {
            $name = $request->customer_name;
            $email = $request->customer_email;
            $phone = $request->customer_phone;
            $existingUser = User::where('email', $email)->first();
            $userId = $existingUser?->id;
        }

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $userId,
            'admin_id' => $admin?->id,
            'customer_name' => $name,
            'customer_email' => $email,
            'customer_phone' => $phone,
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
            'created_by' => 'admin',
            'access_token' => Str::random(40),
            'last_reply_at' => now(),
        ]);

        // Initial message from Admin (Strictly text only)
        SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'admin',
            'sender_id' => $admin?->id,
            'sender_name' => $admin?->name ?? 'Support Operations Lead',
            'message' => $request->message,
        ]);

        ActivityLogService::logTicket('ticket.create', "Created Support Ticket #{$ticket->ticket_number} for '{$email}'.", $ticket, [
            'ticket_number' => $ticket->ticket_number,
            'recipient_email' => $email,
            'subject' => $ticket->subject,
            'priority' => $ticket->priority,
        ]);

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', "Support ticket #{$ticket->ticket_number} created successfully for {$email}.");
    }

    /**
     * Display ticket conversation and step-by-step chat flow in Admin Command Center.
     */
    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['messages', 'user', 'admin']);

        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Post admin reply to ticket (Strictly text only).
     */
    public function reply(AdminReplySupportTicketRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $admin = auth('admin')->user();

        $replyMessage = SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'admin',
            'sender_id' => $admin?->id,
            'sender_name' => $admin?->name ?? 'Support Operations Lead',
            'message' => $request->message,
        ]);

        $ticket->last_reply_at = now();

        if ($request->filled('status')) {
            $ticket->status = $request->status;
            if ($request->status === 'closed') {
                $ticket->closed_at = now();
            } else {
                $ticket->closed_at = null;
            }
        } elseif ($ticket->status === 'open') {
            $ticket->status = 'in_progress';
        }

        $ticket->save();

        ActivityLogService::logTicket('ticket.reply', "Posted administrative reply to Support Ticket #{$ticket->ticket_number}.", $ticket, [
            'ticket_number' => $ticket->ticket_number,
            'status' => $ticket->status,
        ]);

        // Notify customer by email when admin replies
        Mail::to($ticket->customer_email)
            ->send(new SupportTicketAdminReplyMail($ticket, $replyMessage, $ticket->access_token));

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Admin response sent and conversation flow updated.');
    }

    /**
     * Update ticket status (Close, Open, Re-open).
     */
    public function updateStatus(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:open,in_progress,closed'],
        ]);

        $newStatus = $request->status;
        $ticket->status = $newStatus;

        if ($newStatus === 'closed') {
            $ticket->closed_at = now();
            $actionLabel = 'closed';
        } elseif ($newStatus === 'open') {
            $ticket->closed_at = null;
            $actionLabel = 're-opened';
        } else {
            $ticket->closed_at = null;
            $actionLabel = 'marked in progress';
        }

        $ticket->save();

        ActivityLogService::logTicket('ticket.status_update', "Changed Support Ticket #{$ticket->ticket_number} status to '{$newStatus}'.", $ticket, [
            'ticket_number' => $ticket->ticket_number,
            'status' => $newStatus,
        ]);

        return back()->with('success', "Ticket #{$ticket->ticket_number} has been {$actionLabel}.");
    }

    /**
     * Delete a support ticket and its message history.
     */
    public function destroy(SupportTicket $ticket): RedirectResponse
    {
        $ticketNumber = $ticket->ticket_number;
        $ticket->delete();

        ActivityLogService::logTicket('ticket.delete', "Deleted Support Ticket #{$ticketNumber}.", null, [
            'ticket_number' => $ticketNumber,
        ]);

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', "Ticket #{$ticketNumber} has been permanently deleted.");
    }
}
