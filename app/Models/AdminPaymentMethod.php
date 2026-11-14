<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPaymentMethod extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'method_type',
        'account_number',
        'account_name',
        'number_type',
        'bank_name',
        'branch_name',
        'routing_number',
        'instructions',
        'is_active',
        'is_default',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    /**
     * Scope active payment methods.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope default payment method.
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * Format payment method for display.
     */
    public function getFormattedMethodAttribute(): string
    {
        if ($this->method_type === 'offline') {
            $name = $this->account_name ?: 'FIX Accounts Cash Desk';

            return "Cash / Offline Handover ({$name})";
        }

        if (in_array($this->method_type, ['bkash', 'nagad', 'rocket', 'upay'])) {
            $type = ucfirst($this->number_type ?: 'Merchant');
            $label = strtoupper($this->method_type)." ({$type}) • {$this->account_number}";
            if ($this->account_name) {
                $label .= " ({$this->account_name})";
            }

            return $label;
        }

        if ($this->method_type === 'bank') {
            $bank = $this->bank_name ?: 'Bank Transfer';
            $acc = $this->account_number;
            $name = $this->account_name ?: 'FIX Technologies Ltd.';
            $branch = $this->branch_name ? ", Branch: {$this->branch_name}" : '';

            return "{$bank} - A/C: {$acc} ({$name}){$branch}";
        }

        return ucfirst($this->method_type).' • '.($this->account_number ?: 'N/A');
    }
}
