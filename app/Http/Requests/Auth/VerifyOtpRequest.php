<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'digits:10'],
            'otp'   => ['required', 'digits:4'],
            'role'  => ['required', 'in:traveler,owner'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Mobile number is required.',
            'phone.digits'   => 'Mobile number must be exactly 10 digits.',
            'otp.required'   => 'OTP is required.',
            'otp.digits'     => 'OTP must be exactly 4 digits.',
            'role.required'  => 'Role is required.',
            'role.in'        => 'Role must be either traveler or owner.',
        ];
    }
}