<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'email',
        'otp_code_hash',
        'purpose',
        'payload',
        'expires_at',
        'attempts',
        'is_consumed',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'expires_at' => 'datetime',
            'is_consumed' => 'boolean',
            'attempts' => 'integer',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return ! $this->is_consumed && ! $this->isExpired() && $this->attempts < 5;
    }
}
