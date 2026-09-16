<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'string', 'max:255'],
            'email'     => ['sometimes', 'nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'phone'     => ['sometimes', 'digits:10', Rule::unique('users', 'phone')->ignore($this->user()->id)],
            'home_city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'avatar'    => ['sometimes', 'nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'   => 'This email is already in use.',
            'phone.digits'   => 'Mobile number must be exactly 10 digits.',
            'phone.unique'   => 'This mobile number is already in use.',
            'avatar.image'   => 'Profile photo must be an image.',
            'avatar.max'     => 'Profile photo must be under 2MB.',
        ];
    }
}
