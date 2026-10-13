<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmcPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'badge',
        'tier',
        'target_audience',
        'price',
        'price_period',
        'sla_response_time',
        'is_popular',
        'features',
        'button_text',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'order' => 'integer',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
