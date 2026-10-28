<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteContent extends Model
{
    protected $fillable = [
        'section_key',
        'is_active',
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
            'is_active' => 'boolean',
            'meta_data' => 'array',
        ];
    }

    public function isActive(): bool
    {
        return (bool) ($this->is_active ?? true);
    }

    public static function forSection(string $sectionKey, bool $mustBeActive = false): ?static
    {
        $cached = Cache::get("site_content:{$sectionKey}");
        if ($cached instanceof static) {
            if ($mustBeActive && ! $cached->isActive()) {
                return null;
            }

            return $cached;
        }

        Cache::forget("site_content:{$sectionKey}");

        $content = static::where('section_key', $sectionKey)->first();
        if ($content) {
            Cache::put("site_content:{$sectionKey}", $content, 3600);
        }

        if ($content && $mustBeActive && ! $content->isActive()) {
            return null;
        }

        return $content;
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
