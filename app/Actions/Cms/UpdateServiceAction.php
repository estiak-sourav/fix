<?php

namespace App\Actions\Cms;

use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UpdateServiceAction
{
    /**
     * Update service entity and handle thumbnail upload.
     *
     * @param  array<string, mixed>  $validated
     * @param  UploadedFile|null     $thumbnail
     */
    public function execute(Service $service, array $validated, ?UploadedFile $thumbnail = null): Service
    {
        $data = [
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'],
            'price_starting' => $validated['price_starting'] ?? null,
            'pricing_unit' => $validated['pricing_unit'] ?? 'per unit',
            'sla_hours' => $validated['sla_hours'],
            'is_emergency' => (bool) ($validated['is_emergency'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];

        if (!empty($validated['scope_included'])) {
            $data['scope_included'] = array_values(array_filter(array_map('trim', explode("\n", (string) $validated['scope_included']))));
        }

        if ($thumbnail) {
            $filename = 'service_' . time() . '_' . Str::slug($service->title) . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move(public_path('uploads/services'), $filename);
            $data['thumbnail_path'] = 'uploads/services/' . $filename;
        }

        $service->update($data);

        return $service;
    }
}
