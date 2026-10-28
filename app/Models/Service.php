<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'subtitle',
        'description',
        'icon',
        'thumbnail_path',
        'hero_image_path',
        'gallery_images',
        'price_starting',
        'pricing_unit',
        'sla_hours',
        'scope_included',
        'scope_excluded',
        'compliance_certs',
        'is_emergency',
        'is_featured',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'scope_included' => 'array',
            'scope_excluded' => 'array',
            'compliance_certs' => 'array',
            'gallery_images' => 'array',
            'price_starting' => 'decimal:2',
            'sla_hours' => 'integer',
            'is_emergency' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Booking::class)->whereNotNull('review_rating')->latest('reviewed_at');
    }

    public function averageRating(): float
    {
        return round((float) ($this->bookings()->whereNotNull('review_rating')->avg('review_rating') ?: 5.0), 1);
    }

    public function reviewsCount(): int
    {
        return $this->bookings()->whereNotNull('review_rating')->count();
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path && file_exists(public_path($this->thumbnail_path))) {
            return asset($this->thumbnail_path);
        }
        if ($this->thumbnail_path && str_starts_with($this->thumbnail_path, 'http')) {
            return $this->thumbnail_path;
        }

        return asset('assets/images/placeholder-service.jpg');
    }

    /**
     * @return array<int, string>
     */
    public function getGalleryUrlsAttribute(): array
    {
        $gallery = $this->gallery_images;
        if (empty($gallery) || ! is_array($gallery)) {
            return [];
        }

        $urls = [];
        foreach ($gallery as $item) {
            if (empty($item)) {
                continue;
            }
            if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                $urls[] = $item;
            } elseif (file_exists(public_path($item))) {
                $urls[] = asset($item);
            } else {
                $urls[] = asset($item);
            }
        }

        return $urls;
    }
}
