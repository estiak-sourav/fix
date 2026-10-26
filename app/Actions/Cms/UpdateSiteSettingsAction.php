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
        $uploadDir = public_path('uploads/branding');
        if (! file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($inputs as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $value = 'uploads/branding/'.$filename;
            }

            SiteSetting::set($key, $value);
        }
    }
}
