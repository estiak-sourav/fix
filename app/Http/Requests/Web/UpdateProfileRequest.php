<?php

namespace App\Http\Requests\Web;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'property_type' => ['nullable', 'string', 'max:50'],
            'default_address' => ['required', 'string', 'max:500'],
            'division' => ['required', 'string', 'max:50'],
            'district' => ['required', 'string', 'max:50'],
            'area' => ['required', 'string', 'max:80'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string', 'required_with:password'],
        ];
    }
}
