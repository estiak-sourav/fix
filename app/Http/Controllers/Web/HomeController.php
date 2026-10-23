<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AmcPlan;
use App\Models\Faq;
use App\Models\Sector;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SiteContent;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Handle incoming contact inquiries and dispatch admin alert.
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

        $subject = $validated['subject'] ?? 'General Facility Inquiry';

        AdminNotification::create([
            'title' => 'New Facility Inquiry: '.$subject,
            'message' => $validated['name'].' ('.$validated['phone'].'): '.Str::limit($validated['message'], 120),
            'type' => 'inquiry',
            'sound_played' => false,
            'is_read' => false,
        ]);

        $msg = 'Thank you! Your inquiry has been sent to FIX Operations Command. A coordinator will reach you within 30 minutes.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }
}
