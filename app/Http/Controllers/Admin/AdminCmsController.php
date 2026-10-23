<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Cms\UpdateServiceAction;
use App\Actions\Cms\UpdateSiteSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Mail\TestSmtpMail;
use App\Models\AmcPlan;
use App\Models\Faq;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Services\ActivityLogService;
use App\Services\MailConfigService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminCmsController extends Controller
{
    /**
     * Web Control Central Hub Overview.
     */
    public function webControlHub(): View
    {
        $stats = [
            'services_count' => Service::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'categories_count' => ServiceCategory::count(),
            'amc_plans_count' => AmcPlan::count(),
            'faqs_count' => Faq::count(),
            'testimonials_count' => Testimonial::count(),
            'contents_count' => SiteContent::count(),
            'mail_mailer' => SiteSetting::get('mail_mailer', config('mail.default', 'smtp')),
            'emergency_phone' => SiteSetting::get('emergency_phone', '+880 1700-000000'),
            'support_email' => SiteSetting::get('support_email', 'support@fix.com.bd'),
        ];

        return view('admin.web.index', compact('stats'));
    }

    /**
     * Overview of CMS sections & general settings.
     */
    public function settings(): View
    {
        $settings = SiteSetting::all()->groupBy('group');
        $siteContents = SiteContent::all();

        return view('admin.cms.settings', compact('settings', 'siteContents'));
    }

    /**
     * Update general site settings via UpdateSiteSettingsAction.
     */
    public function updateSettings(UpdateSiteSettingsRequest $request, UpdateSiteSettingsAction $action): RedirectResponse
    {
        $action->execute($request->except(['_token', '_method']), $request);

        ActivityLogService::logSettings('settings.update', 'Updated site settings & configuration.');

        return redirect()->back()->with('success', 'Site & SMTP settings updated successfully.');
    }

    /**
     * Send test email to verify SMTP gateway configuration.
     */
    public function testSmtp(Request $request): RedirectResponse
    {
        $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        $recipient = $request->input('test_email');

        MailConfigService::applyDynamicConfig();

        $host = SiteSetting::get('mail_host', config('mail.mailers.smtp.host'));
        $port = (int) SiteSetting::get('mail_port', config('mail.mailers.smtp.port', 587));

        try {
            Mail::to($recipient)->send(
                new TestSmtpMail($recipient, (string) $host, $port)
            );

            ActivityLogService::logSettings('settings.test_smtp', "Verified SMTP gateway connection by sending test email to '{$recipient}'.");

            return redirect()->back()->with('success', "SMTP Gateway connection verified! Test email successfully delivered to {$recipient}.");
        } catch (\Throwable $e) {
            Log::error('SMTP Test Failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'SMTP Connection Failed: '.$e->getMessage());
        }
    }

    /**
     * List all services for CMS management.
     */
    public function services(): View
    {
        $services = Service::with('category')->orderBy('order')->paginate(20);

        return view('admin.cms.services.index', compact('services'));
    }

    /**
     * Edit service form.
     */
    public function editService(Service $service): View
    {
        $categories = ServiceCategory::where('is_active', true)->orderBy('order')->get();

        return view('admin.cms.services.edit', compact('service', 'categories'));
    }

    /**
     * Update service details via UpdateServiceAction.
     */
    public function updateService(Service $service, UpdateServiceRequest $request, UpdateServiceAction $action): RedirectResponse
    {
        $action->execute($service, $request->validated(), $request->file('thumbnail_file'));

        ActivityLogService::logService('service.update', "Updated service '{$service->title}'.", $service);

        return redirect()->route('admin.web.services')->with('success', "Service '{$service->title}' updated successfully.");
    }

    /**
     * AMC Maintenance Packages List.
     */
    public function amcPlans(): View
    {
        $plans = AmcPlan::orderBy('order')->get();

        return view('admin.web.amc_plans', compact('plans'));
    }

    /**
     * Update AMC Plan.
     */
    public function updateAmcPlan(AmcPlan $plan, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_period' => ['required', 'string', 'max:50'],
            'sla_response_time' => ['required', 'string', 'max:100'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'is_popular' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');

        $plan->update($validated);

        ActivityLogService::logService('amc.update', "Updated AMC Plan '{$plan->name}'.", $plan);

        return redirect()->back()->with('success', "AMC Plan '{$plan->name}' updated successfully.");
    }

    /**
     * Section Contents Management.
     */
    public function contents(): View
    {
        $contents = SiteContent::orderBy('section_key')->get();

        return view('admin.web.contents', compact('contents'));
    }

    /**
     * Update Section Content.
     */
    public function updateContent(SiteContent $content, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'badge' => ['nullable', 'string', 'max:100'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'secondary_button_text' => ['nullable', 'string', 'max:100'],
            'secondary_button_url' => ['nullable', 'string', 'max:255'],
            'media_path' => ['nullable', 'string', 'max:255'],
        ]);

        $content->update($validated);
        Cache::forget("site_content:{$content->section_key}");

        return redirect()->back()->with('success', "Section '{$content->section_key}' updated successfully.");
    }

    /**
     * FAQs Knowledgebase List.
     */
    public function faqs(): View
    {
        $faqs = Faq::orderBy('order')->paginate(25);

        return view('admin.web.faqs', compact('faqs'));
    }

    /**
     * Store new FAQ.
     */
    public function storeFaq(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? (Faq::max('order') + 1);

        Faq::create($validated);

        return redirect()->back()->with('success', 'New FAQ added successfully.');
    }

    /**
     * Update FAQ.
     */
    public function updateFaq(Faq $faq, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $faq->update($validated);

        return redirect()->back()->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete FAQ.
     */
    public function deleteFaq(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->back()->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Client Testimonials List.
     */
    public function testimonials(): View
    {
        $testimonials = Testimonial::orderBy('order')->paginate(25);

        return view('admin.web.testimonials', compact('testimonials'));
    }

    /**
     * Store Testimonial.
     */
    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'quote' => ['required', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = Testimonial::max('order') + 1;

        Testimonial::create($validated);

        return redirect()->back()->with('success', 'Testimonial added successfully.');
    }

    /**
     * Update Testimonial.
     */
    public function updateTestimonial(Testimonial $testimonial, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'quote' => ['required', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $testimonial->update($validated);

        return redirect()->back()->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Delete Testimonial.
     */
    public function deleteTestimonial(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted successfully.');
    }
}
