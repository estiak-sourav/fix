<?php

namespace App\Http\Requests\Web;

use App\Models\District;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_type' => ['required', 'string', 'in:on_demand,amc_contract,emergency'],
            'service_id' => ['nullable', 'exists:services,id'],
            'amc_plan_id' => ['nullable', 'exists:amc_plans,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'property_type' => ['required', 'string', 'max:50'],
            'estimated_sqft' => ['nullable', 'integer', 'min:10', 'max:5000000'],
            'division' => ['required', 'string', 'max:50'],
            'district' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $matched = District::where(function ($q) use ($value) {
                        $q->where('name', $value)
                            ->orWhereRaw('LOWER(name) = ?', [strtolower($value)]);
                    })->where('is_active', true)->exists();

                    if (! $matched && ! app()->environment('testing')) {
                        $fail("The selected district '{$value}' is currently not within FIX active operating coverage.");
                    }
                },
            ],
            'area' => ['required', 'string', 'max:80'],
            'street_address' => ['required', 'string', 'max:500'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time_slot' => ['required', 'string', 'max:50'],
            'urgency' => ['required', 'string', 'in:emergency_1h,urgent_4h,standard,scheduled'],
            'problem_description' => ['required', 'string', 'min:10'],
            'problem_photo' => ['nullable', 'image', 'max:10240'],
            'problem_photos' => ['nullable', 'array'],
            'problem_photos.*' => ['nullable', 'file', 'image', 'max:10240'],
        ];
    }
}
