<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmcNote extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'admin_id',
        'partner_id',
        'sender_type',
        'sender_name',
        'note',
        'is_customer_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_customer_visible' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
