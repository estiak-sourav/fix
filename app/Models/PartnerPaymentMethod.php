<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerPaymentMethod extends Model
{
    protected $fillable = [
        'partner_id',
        'method_type',
        'account_number',
        'account_name',
        'number_type',
        'bank_name',
        'branch_name',
        'routing_number',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(PartnerPayout::class);
    }

    public function getFormattedMethodAttribute(): string
    {
        $type = strtoupper($this->method_type);
        if ($this->method_type === 'bank') {
            $bankName = $this->bank_name ?: 'Bank Account';
            $branch = $this->branch_name ? " ({$this->branch_name})" : '';

            return "{$bankName}{$branch} • Acc: {$this->account_number}".($this->account_name ? " ({$this->account_name})" : '');
        }

        $numType = $this->number_type ? ' ('.ucfirst($this->number_type).')' : '';

        return "{$type}{$numType} • {$this->account_number}".($this->account_name ? " ({$this->account_name})" : '');
    }
}
