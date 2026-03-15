<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTravelerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'temp_token' => ['required', 'string', 'size:64'],
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'last_name'  => ['required', 'string', 'min:1', 'max:50'],
            'home_city'  => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'temp_token.required' => 'Verification token is missing. Please restart the signup flow.',
            'temp_token.size'     => 'Verification token is invalid.',
            'first_name.required' => 'First name is required.',
            'first_name.min'      => 'First name must be at least 2 characters.',
            'last_name.required'  => 'Last name is required.',
        ];
    }
}