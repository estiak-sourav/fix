<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:service_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price_starting' => ['nullable', 'numeric', 'min:0'],
            'pricing_unit' => ['nullable', 'string', 'max:100'],
            'sla_hours' => ['required', 'integer', 'min:1'],
            'scope_included' => ['nullable', 'string'],
            'thumbnail_file' => ['nullable', 'image', 'max:4096'],
            'is_emergency' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
