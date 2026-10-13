<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'short_desc',
        'key_metrics',
        'bullet_points',
        'banner_image',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'bullet_points' => 'array',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }
}
