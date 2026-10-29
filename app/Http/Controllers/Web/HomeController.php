<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\SupportTicketCreatedMail;
use App\Models\AdminNotification;
use App\Models\AmcPlan;
use App\Models\Faq;
use App\Models\Sector;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Render dynamic landing page with 100% database-driven content.
     */
    public function index(): View
    {
        $categories = ServiceCategory::with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('order')])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $services = Service::with('category')
            ->withCount(['reviews as reviews_count'])
            ->withAvg('reviews as avg_rating', 'review_rating')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $amcPlans = AmcPlan::where('is_active', true)
            ->orderBy('order')
            ->get();

        $sectors = Sector::where('is_active', true)
            ->orderBy('order')
            ->get();

        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('order')
            ->get();

        $siteContents = SiteContent::all()->keyBy('section_key');

        return view('web.pages.home', compact(
            'categories',
            'services',
            'amcPlans',
            'sectors',
            'faqs',
            'testimonials',
            'siteContents'
        ));
    }

    /**
     * Render the dedicated About Us company page.
     */
    public function about(): View
    {
        $heroContent = SiteContent::forSection('about_hero');
        $overviewContent = SiteContent::forSection('about_overview');
        $services = Service::with('category')->where('is_active', true)->orderBy('order')->take(6)->get();
        $clientsContent = SiteContent::forSection('clients');
        $sectors = Sector::where('is_active', true)->orderBy('order')->get();
        $siteSettings = SiteSetting::all()->pluck('value', 'key');

        return view('web.pages.about', compact('heroContent', 'overviewContent', 'services', 'clientsContent', 'sectors', 'siteSettings'));
    }

    /**
     * Handle incoming contact inquiries by creating a unified Support Ticket.
     */
    public function contact(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        $user = auth()->user();

        // If guest provided an email that belongs to a registered customer, link it
        if (! $user && ! empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
        }

        $subject = ! empty($validated['subject']) ? $validated['subject'] : 'General Facility & Service Inquiry';
        $customerEmail = ! empty($validated['email']) ? $validated['email'] : ($user?->email ?? ($validated['phone'].'@guest.fix.com.bd'));

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $user?->id,
            'customer_name' => $validated['name'],
            'customer_email' => $customerEmail,
            'customer_phone' => $validated['phone'],
            'subject' => $subject,
            'priority' => 'normal',
            'status' => 'open',
            'created_by' => 'customer',
            'access_token' => Str::random(40),
            'last_reply_at' => now(),
        ]);

        SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'customer',
            'sender_id' => $user?->id,
            'sender_name' => $validated['name'],
            'message' => $validated['message'],
        ]);

        AdminNotification::create([
            'type' => 'support_ticket',
            'title' => "New Inquiry/Ticket: #{$ticket->ticket_number}",
            'message' => "Client {$ticket->customer_name} ({$ticket->customer_phone}): {$ticket->subject}",
            'is_read' => false,
            'sound_played' => false,
        ]);

        // If a real email address is available, send confirmation email
        if (! empty($validated['email']) || ($user && $user->email)) {
            try {
                Mail::to($customerEmail)->send(new SupportTicketCreatedMail($ticket, $ticket->access_token));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $trackUrl = auth()->check()
            ? route('customer.tickets.show', $ticket->ticket_number)
            : route('support.show', ['ticketNumber' => $ticket->ticket_number, 'token' => $ticket->access_token]);

        $msg = "Thank you! Your inquiry has been registered as Ticket #{$ticket->ticket_number}. Our operations command will reach you shortly.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'ticket_number' => $ticket->ticket_number,
                'track_url' => $trackUrl,
                'message' => $msg,
            ]);
        }

        return redirect($trackUrl)->with('success', $msg);
    }
}
