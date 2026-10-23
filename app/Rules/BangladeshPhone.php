<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BangladeshPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * Validates Bangladesh mobile phone numbers:
     * - Standard operators: 013, 014, 015, 016, 017, 018, 019
     * - Allows formats: 01712345678, +8801712345678, 8801712345678, 01700-112233
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_numeric($value)) {
            $fail('The :attribute must be a valid Bangladesh phone number.');

            return;
        }

        $cleaned = preg_replace('/[\s\-\(\)\.]+/', '', (string) $value);

        if (! preg_match('/^(?:\+?880|880|0)?(1[3-9]\d{8})$/', $cleaned)) {
            $fail('The :attribute must be a valid Bangladesh mobile number (e.g. 017XXXXXXXX or +88017XXXXXXXX).');
        }
    }

    /**
     * Normalize Bangladesh phone number into standard international format (+8801XXXXXXXXX).
     */
    public static function normalize(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $cleaned = preg_replace('/[\s\-\(\)\.]+/', '', trim($phone));

        if (preg_match('/^(?:\+?880|880|0)?(1[3-9]\d{8})$/', $cleaned, $matches)) {
            return '+880'.$matches[1];
        }

        return $phone;
    }
}
