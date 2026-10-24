<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'category_id' => ['nullable'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price_starting' => ['nullable'],
            'pricing_unit' => ['nullable', 'string', 'max:100'],
            'sla_hours' => ['nullable', 'integer', 'min:1'],
            'scope_included' => ['nullable', 'string'],
            'thumbnail_file' => ['nullable', 'image', 'max:10240'],
            'service_photos' => ['nullable', 'array', 'max:5'],
            'service_photos.*' => ['nullable', 'image', 'max:10240'],
            'is_emergency' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
