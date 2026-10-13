<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Cms\UpdateServiceAction;
use App\Actions\Cms\UpdateSiteSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminCmsController extends Controller
{
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

        return redirect()->back()->with('success', 'Site settings updated successfully.');
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

        return redirect()->route('admin.cms.services')->with('success', "Service '{$service->title}' updated successfully.");
    }
}
