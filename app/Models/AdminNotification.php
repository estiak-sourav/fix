<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'booking_id',
        'action_url',
        'is_read',
        'sound_played',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'sound_played' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
