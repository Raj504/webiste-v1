<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'temp_token'   => ['required', 'string', 'size:64'],
            'owner_name'   => ['required', 'string', 'min:2', 'max:100'],
            'gym_name'     => ['required', 'string', 'min:2', 'max:150'],
            'city'         => ['required', 'string', 'max:100'],
            'area'         => ['required', 'string', 'max:150'],
            'monthly_rate' => ['required', 'integer', 'min:200', 'max:100000'],
            'upi_id'       => ['nullable', 'string', 'max:100'],
            'terms'        => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'temp_token.required'   => 'Verification token is missing. Please restart the signup flow.',
            'temp_token.size'       => 'Verification token is invalid.',
            'owner_name.required'   => 'Owner name is required.',
            'gym_name.required'     => 'Gym name is required.',
            'city.required'         => 'City is required.',
            'area.required'         => 'Area or locality is required.',
            'monthly_rate.required' => 'Monthly membership rate is required.',
            'monthly_rate.min'      => 'Monthly rate must be at least ₹200.',
            'monthly_rate.max'      => 'Monthly rate cannot exceed ₹1,00,000.',
            'terms.accepted'        => 'You must accept the Terms of Service to continue.',
        ];
    }
}