<?php

namespace App\Actions\Cms;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpdateSiteSettingsAction
{
    /**
     * Update settings and persist any uploaded assets.
     *
     * @param  array<string, mixed>  $inputs
     */
    public function execute(array $inputs, Request $request): void
    {
        foreach ($inputs as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/branding'), $filename);
                $value = 'uploads/branding/'.$filename;
            }

            SiteSetting::set($key, $value);
        }
    }
}
