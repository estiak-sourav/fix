<?php

namespace App\Actions\Cms;

use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UpdateServiceAction
{
    /**
     * Update service entity and handle thumbnail & gallery uploads.
     *
     * @param  array<string, mixed>  $validated
     * @param  array<UploadedFile>  $galleryFiles
     */
    public function execute(Service $service, array $validated, ?UploadedFile $thumbnail = null, array $galleryFiles = []): Service
    {
        $data = [
            'category_id' => $validated['category_id'] ?? $service->category_id,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? $service->subtitle,
            'description' => $validated['description'],
            'price_starting' => ! empty($validated['price_starting']) ? $validated['price_starting'] : $service->price_starting,
            'pricing_unit' => $validated['pricing_unit'] ?? ($service->pricing_unit ?: 'Negotiated over phone'),
            'sla_hours' => $validated['sla_hours'] ?? ($service->sla_hours ?: 4),
        ];

        if (array_key_exists('is_emergency', $validated)) {
            $data['is_emergency'] = (bool) $validated['is_emergency'];
        }

        if (array_key_exists('is_active', $validated)) {
            $data['is_active'] = (bool) $validated['is_active'];
        }

        if (! empty($validated['scope_included'])) {
            $data['scope_included'] = array_values(array_filter(array_map('trim', explode("\n", (string) $validated['scope_included']))));
        }

        if ($thumbnail) {
            $filename = 'service_'.time().'_'.Str::slug($service->title).'.'.$thumbnail->getClientOriginalExtension();
            $thumbnail->move(public_path('uploads/services'), $filename);
            $data['thumbnail_path'] = 'uploads/services/'.$filename;
        }

        // Handle up to 5 gallery photos
        $currentGallery = $service->gallery_images ?? [];
        if (! empty($validated['remove_photos'])) {
            $toRemove = (array) $validated['remove_photos'];
            $currentGallery = array_values(array_filter($currentGallery, fn ($img) => ! in_array($img, $toRemove)));
        }

        if (! empty($galleryFiles)) {
            foreach (array_slice($galleryFiles, 0, 5) as $photo) {
                if ($photo instanceof UploadedFile) {
                    $photoName = 'gallery_'.time().'_'.Str::random(6).'.'.$photo->getClientOriginalExtension();
                    $photo->move(public_path('uploads/services'), $photoName);
                    $currentGallery[] = 'uploads/services/'.$photoName;
                }
            }
        }
        $data['gallery_images'] = array_slice($currentGallery, 0, 5);

        $service->update($data);

        return $service;
    }
}
