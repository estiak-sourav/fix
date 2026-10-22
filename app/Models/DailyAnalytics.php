<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyAnalytics extends Model
{
    protected $fillable = [
        'date',
        'total_visitors',
        'new_visitors',
        'service_visitors',
        'amc_visitors',
        'logins_count',
        'registrations_count',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total_visitors' => 'integer',
            'new_visitors' => 'integer',
            'service_visitors' => 'integer',
            'amc_visitors' => 'integer',
            'logins_count' => 'integer',
            'registrations_count' => 'integer',
        ];
    }

    /**
     * Get or create daily record for a specific date (defaults to today).
     */
    public static function forDate($date = null): self
    {
        $targetDate = \Carbon\Carbon::parse($date ?? today())->format('Y-m-d');

        $record = static::where('date', 'like', $targetDate . '%')->first();

        if ($record) {
            return $record;
        }

        try {
            return static::create([
                'date' => $targetDate,
                'total_visitors' => 0,
                'new_visitors' => 0,
                'service_visitors' => 0,
                'amc_visitors' => 0,
                'logins_count' => 0,
                'registrations_count' => 0,
            ]);
        } catch (\Throwable $e) {
            return static::where('date', 'like', $targetDate . '%')->first()
                ?? static::firstOrCreate(['date' => $targetDate]);
        }
    }

    /**
     * Increment website visitors and optional category flags.
     */
    public static function recordVisitor(bool $isService = false, bool $isAmc = false, bool $isNew = false): void
    {
        $record = static::forDate();
        $record->increment('total_visitors');

        if ($isNew) {
            $record->increment('new_visitors');
        }

        if ($isService) {
            $record->increment('service_visitors');
        }

        if ($isAmc) {
            $record->increment('amc_visitors');
        }
    }

    /**
     * Increment daily login count.
     */
    public static function recordLogin(): void
    {
        static::forDate()->increment('logins_count');
    }

    /**
     * Increment daily registration count.
     */
    public static function recordRegistration(): void
    {
        static::forDate()->increment('registrations_count');
    }
}
