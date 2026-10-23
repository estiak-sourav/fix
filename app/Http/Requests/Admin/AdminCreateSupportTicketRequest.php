<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminCreateSupportTicketRequest extends FormRequest
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
            'recipient_type' => ['required', 'string', 'in:registered,non_registered'],
            'user_id' => ['required_if:recipient_type,registered', 'nullable', 'exists:users,id'],
            'customer_name' => ['required_if:recipient_type,non_registered', 'nullable', 'string', 'max:255'],
            'customer_email' => ['required_if:recipient_type,non_registered', 'nullable', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'in:low,normal,high,urgent'],
            'message' => ['required', 'string', 'min:5', 'max:10000'],
        ];
    }
}
