<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGymPlanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'price'      => ['sometimes', 'integer', 'min:1', 'max:999999'],
            'is_enabled' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.min' => 'Price must be at least ₹1.',
        ];
    }
}
