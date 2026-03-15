<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'digits:10'],
            'role'  => ['required', 'in:traveler,owner'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Mobile number is required.',
            'phone.digits'   => 'Mobile number must be exactly 10 digits.',
            'role.required'  => 'Role is required.',
            'role.in'        => 'Role must be either traveler or owner.',
        ];
    }
}