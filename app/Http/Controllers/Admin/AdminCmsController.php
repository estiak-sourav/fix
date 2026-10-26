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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        return redirect()->back()->with('success', 'General site settings updated successfully.');
    }

    /**
     * Dedicated SMTP Gateway Setup view.
     */
    public function smtpSettings(): View
    {
        $mailConfig = [
            'mail_mailer' => SiteSetting::get('mail_mailer', config('mail.default', 'smtp')),
            'mail_host' => SiteSetting::get('mail_host', config('mail.mailers.smtp.host', 'smtp.gmail.com')),
            'mail_port' => SiteSetting::get('mail_port', config('mail.mailers.smtp.port', 587)),
            'mail_encryption' => SiteSetting::get('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'mail_username' => SiteSetting::get('mail_username', config('mail.mailers.smtp.username')),
            'mail_password' => SiteSetting::get('mail_password', config('mail.mailers.smtp.password')),
            'mail_from_address' => SiteSetting::get('mail_from_address', config('mail.from.address', 'noreply@fix.com.bd')),
            'mail_from_name' => SiteSetting::get('mail_from_name', config('mail.from.name', 'FIX Operations')),
        ];

        return view('admin.cms.smtp', compact('mailConfig'));
    }

    /**
     * Update SMTP Gateway settings.
     */
    public function updateSmtpSettings(UpdateSiteSettingsRequest $request, UpdateSiteSettingsAction $action): RedirectResponse
    {
        $action->execute($request->except(['_token', '_method']), $request);

        ActivityLogService::logSettings('settings.smtp_update', 'Updated SMTP gateway configuration.');

        return redirect()->back()->with('success', 'SMTP Gateway configuration updated successfully.');
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
    /**
     * Update service details via UpdateServiceAction.
     */
    public function updateService(Service $service, UpdateServiceRequest $request, UpdateServiceAction $action): RedirectResponse
    {
        $galleryFiles = $request->file('service_photos', []);
        $action->execute($service, $request->validated(), $request->file('thumbnail_file'), is_array($galleryFiles) ? $galleryFiles : []);

        ActivityLogService::logService('service.update', "Updated service '{$service->title}'.", $service);

        return redirect()->route('admin.web.services')->with('success', "Service '{$service->title}' updated successfully.");
    }

    /**
     * Toggle service active status (Publish / Unpublish).
     */
    public function toggleServiceStatus(Service $service): RedirectResponse
    {
        $service->update(['is_active' => ! $service->is_active]);
        $statusStr = $service->is_active ? 'published' : 'unpublished';
        ActivityLogService::logService('service.status', "Service '{$service->title}' was {$statusStr}.", $service);

        return redirect()->back()->with('success', "Service '{$service->title}' {$statusStr} successfully.");
    }

    /**
     * Delete service.
     */
    public function destroyService(Service $service): RedirectResponse
    {
        $title = $service->title;
        $service->delete();
        ActivityLogService::logService('service.delete', "Deleted service '{$title}'.");

        return redirect()->route('admin.web.services')->with('success', "Service '{$title}' deleted successfully.");
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
     * Store new AMC Plan.
     */
    public function storeAmcPlan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_period' => ['nullable', 'string', 'max:50'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'tier' => ['nullable', 'string', 'max:50'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_popular' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['price'] = 0;
        $validated['sla_response_time'] = 'Standard SLA';
        $validated['price_period'] = $validated['price_period'] ?? 'Negotiable';
        $validated['tier'] = $validated['tier'] ?? 'standard';
        $validated['button_text'] = $validated['button_text'] ?? 'Choose Plan';
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = AmcPlan::max('order') + 1;

        if (! empty($validated['features'])) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) $validated['features']))));
        }

        $plan = AmcPlan::create($validated);
        ActivityLogService::logService('amc.create', "Created AMC Plan '{$plan->name}'.", $plan);

        return redirect()->back()->with('success', "AMC Plan '{$plan->name}' created successfully.");
    }

    /**
     * Update AMC Plan.
     */
    public function updateAmcPlan(AmcPlan $plan, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_period' => ['nullable', 'string', 'max:50'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'tier' => ['nullable', 'string', 'max:50'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_popular' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');

        if (! empty($validated['features'])) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) $validated['features']))));
        }

        $plan->update($validated);
        ActivityLogService::logService('amc.update', "Updated AMC Plan '{$plan->name}'.", $plan);

        return redirect()->back()->with('success', "AMC Plan '{$plan->name}' updated successfully.");
    }

    /**
     * Toggle AMC Plan active status.
     */
    public function toggleAmcPlanStatus(AmcPlan $plan): RedirectResponse
    {
        $plan->update(['is_active' => ! $plan->is_active]);
        $statusStr = $plan->is_active ? 'activated' : 'deactivated';
        ActivityLogService::logService('amc.status', "AMC Plan '{$plan->name}' was {$statusStr}.", $plan);

        return redirect()->back()->with('success', "AMC Plan '{$plan->name}' {$statusStr} successfully.");
    }

    /**
     * Delete AMC Plan.
     */
    public function destroyAmcPlan(AmcPlan $plan): RedirectResponse
    {
        $name = $plan->name;
        $plan->delete();
        ActivityLogService::logService('amc.delete', "Deleted AMC Plan '{$name}'.");

        return redirect()->back()->with('success', "AMC Plan '{$name}' deleted successfully.");
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
     * Update Section Content & Upload Images (Zero external URLs).
     */
    public function updateContent(SiteContent $content, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'subtitle' => ['nullable', 'string'],
            'badge' => ['nullable', 'string', 'max:150'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'secondary_button_text' => ['nullable', 'string', 'max:100'],
            'secondary_button_url' => ['nullable', 'string', 'max:255'],
            'media_path' => ['nullable', 'string', 'max:500'],
            'hero_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'remove_slides' => ['nullable', 'array'],
            'section_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'floating_badge_title' => ['nullable', 'string', 'max:150'],
            'floating_badge_text' => ['nullable', 'string', 'max:255'],
            'trust_badges' => ['nullable', 'array'],
        ]);

        $meta = $content->meta_data ?? [];

        // 1. Multiple Hero Slides Upload & Remove
        if ($content->section_key === 'hero') {
            $slides = $meta['slides'] ?? [];

            // Remove selected slides
            if ($request->has('remove_slides')) {
                $toRemove = (array) $request->input('remove_slides', []);
                $slides = array_values(array_filter($slides, fn ($s) => ! in_array($s, $toRemove)));
            }

            // Upload new slide images
            if ($request->hasFile('hero_images')) {
                foreach ($request->file('hero_images') as $file) {
                    $filename = 'hero_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('uploads/hero'), $filename);
                    $slides[] = 'uploads/hero/'.$filename;
                }
            }

            $meta['slides'] = $slides;
            if (! empty($slides)) {
                $validated['media_path'] = $slides[0];
            }

            if ($request->has('floating_badge_title')) {
                $meta['floating_badge_title'] = $request->input('floating_badge_title');
            }
            if ($request->has('floating_badge_text')) {
                $meta['floating_badge_text'] = $request->input('floating_badge_text');
            }
            if ($request->has('trust_badges')) {
                $meta['trust_badges'] = array_values(array_filter($request->input('trust_badges', [])));
            }
        }

        // 2. Single Section Image File Upload
        if ($request->hasFile('section_image')) {
            $file = $request->file('section_image');
            $filename = $content->section_key.'_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/web-content'), $filename);
            $validated['media_path'] = 'uploads/web-content/'.$filename;
        }

        $content->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'secondary_button_text' => $validated['secondary_button_text'] ?? null,
            'secondary_button_url' => $validated['secondary_button_url'] ?? null,
            'media_path' => $validated['media_path'] ?? $content->media_path,
            'meta_data' => $meta,
        ]);
        Cache::forget("site_content:{$content->section_key}");

        // Record Activity Log
        ActivityLogService::logContent('web_content.update', "Updated website section '{$content->section_key}' ('{$content->title}').", $content);

        return redirect()->back()->with('success', "Section '{$content->section_key}' updated and activity logged successfully.");
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

        $faq = Faq::create($validated);
        ActivityLogService::logContent('faq.create', "Created FAQ '{$faq->question}'.", $faq);

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
        ActivityLogService::logContent('faq.update', "Updated FAQ '{$faq->question}'.", $faq);

        return redirect()->back()->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete FAQ.
     */
    public function deleteFaq(Faq $faq): RedirectResponse
    {
        $q = $faq->question;
        $faq->delete();
        ActivityLogService::logContent('faq.delete', "Deleted FAQ '{$q}'.");

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
     * Store Testimonial with image upload support.
     */
    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'quote' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = Testimonial::max('order') + 1;

        if ($request->hasFile('avatar')) {
            $validated['avatar_path'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial = Testimonial::create($validated);
        ActivityLogService::logContent('testimonial.create', "Created testimonial for '{$testimonial->client_name}'.", $testimonial);

        return redirect()->back()->with('success', 'Testimonial added successfully.');
    }

    /**
     * Update Testimonial with avatar replacement and removal.
     */
    public function updateTestimonial(Testimonial $testimonial, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_title' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'quote' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'remove_avatar' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->boolean('remove_avatar') && $testimonial->avatar_path) {
            if (! str_starts_with($testimonial->avatar_path, 'http://') && ! str_starts_with($testimonial->avatar_path, 'https://')) {
                Storage::disk('public')->delete($testimonial->avatar_path);
            }
            $validated['avatar_path'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar_path && ! str_starts_with($testimonial->avatar_path, 'http://') && ! str_starts_with($testimonial->avatar_path, 'https://')) {
                Storage::disk('public')->delete($testimonial->avatar_path);
            }
            $validated['avatar_path'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->update($validated);
        ActivityLogService::logContent('testimonial.update', "Updated testimonial for '{$testimonial->client_name}'.", $testimonial);

        return redirect()->back()->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Delete Testimonial and remove uploaded file.
     */
    public function deleteTestimonial(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->avatar_path && ! str_starts_with($testimonial->avatar_path, 'http://') && ! str_starts_with($testimonial->avatar_path, 'https://')) {
            Storage::disk('public')->delete($testimonial->avatar_path);
        }

        $name = $testimonial->client_name;
        $testimonial->delete();
        ActivityLogService::logContent('testimonial.delete', "Deleted testimonial for '{$name}'.");

        return redirect()->back()->with('success', 'Testimonial deleted successfully.');
    }
}
