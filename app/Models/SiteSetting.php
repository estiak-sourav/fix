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
        $existing = static::where('key', $key)->first();

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $existing ? $existing->group : $group,
                'type' => $existing ? $existing->type : $type,
                'label' => $existing ? $existing->label : ($label ?? ucwords(str_replace('_', ' ', $key))),
            ]
        );

        Cache::forget("site_setting:{$key}");

        return $setting;
    }

    /**
     * Resolve the canonical public user website URL, stripping any admin subdomain.
     */
    public static function mainSiteUrl(string $path = ''): string
    {
        $scheme = request()->getScheme() ?: 'http';
        $port = request()->getPort();
        $host = request()->getHost();

        if (str_starts_with($host, 'admin.')) {
            $cleanHost = substr($host, 6);
            if ($cleanHost === 'localhost') {
                $cleanHost = '127.0.0.1';
            }
        } else {
            $cleanHost = $host ?: '127.0.0.1';
        }

        $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $baseUrl = "{$scheme}://{$cleanHost}{$portStr}";

        $cleanPath = ltrim($path, '/');
        if ($cleanPath === '') {
            return $baseUrl.'/';
        }

        if (str_starts_with($cleanPath, '#')) {
            return $baseUrl.'/'.$cleanPath;
        }

        return $baseUrl.'/'.$cleanPath;
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
