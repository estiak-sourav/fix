<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteContent extends Model
{
    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'badge',
        'button_text',
        'button_url',
        'secondary_button_text',
        'secondary_button_url',
        'media_path',
        'media_type',
        'meta_data',
    ];

    protected function casts(): array
    {
        return [
            'meta_data' => 'array',
        ];
    }

    public static function forSection(string $sectionKey): ?static
    {
        return Cache::remember("site_content:{$sectionKey}", 3600, function () use ($sectionKey) {
            return static::where('section_key', $sectionKey)->first();
        });
    }

    public function getMediaUrlAttribute(): ?string
    {
        if (! $this->media_path) {
            return null;
        }
        if (str_starts_with($this->media_path, 'http')) {
            return $this->media_path;
        }

        return asset($this->media_path);
    }

    protected static function booted(): void
    {
        static::saved(function ($model) {
            Cache::forget("site_content:{$model->section_key}");
        });

        static::deleted(function ($model) {
            Cache::forget("site_content:{$model->section_key}");
        });
    }
}
