<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("site_setting:{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text', ?string $label = null): static
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'label' => $label ?? ucwords(str_replace('_', ' ', $key)),
            ]
        );

        Cache::forget("site_setting:{$key}");

        return $setting;
    }

    protected static function booted(): void
    {
        static::saved(function ($model) {
            Cache::forget("site_setting:{$model->key}");
        });

        static::deleted(function ($model) {
            Cache::forget("site_setting:{$model->key}");
        });
    }
}
