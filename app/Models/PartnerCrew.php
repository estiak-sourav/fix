<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerCrew extends Model
{
    protected $fillable = [
        'partner_id',
        'name',
        'phone',
        'trade_skill',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'assigned_partner_crew_id');
    }
}
