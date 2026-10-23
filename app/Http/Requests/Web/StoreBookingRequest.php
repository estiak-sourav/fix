<?php

namespace App\Http\Requests\Web;

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
            'district' => ['required', 'string', 'max:50'],
            'area' => ['required', 'string', 'max:80'],
            'street_address' => ['required', 'string', 'max:500'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time_slot' => ['required', 'string', 'max:50'],
            'urgency' => ['required', 'string', 'in:emergency_1h,urgent_4h,standard,scheduled'],
            'problem_description' => ['required', 'string', 'min:10'],
            'problem_photo' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
