<?php

namespace App\Http\Requests\Web;

use App\Rules\BangladeshPhone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
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
        $isRegister = $this->input('purpose') === 'register';

        return [
            'purpose' => ['nullable', 'in:login,register'],
            'email' => ['required', 'email', 'max:255'],
            'name' => [$isRegister ? 'required' : 'nullable', 'string', 'max:100'],
            'phone' => [$isRegister ? 'required' : 'nullable', new BangladeshPhone],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'name.required' => 'Your full name is required for registration.',
            'phone.required' => 'A valid Bangladesh phone number is required.',
        ];
    }
}
