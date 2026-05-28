<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/owner/gym/plans  — create a new custom plan
// ─────────────────────────────────────────────────────────────────────────────
class CreateGymPlanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'duration' => ['required', 'integer', 'min:1', 'max:365'],
            'unit'     => ['required', 'in:day,month'],
            'price'    => ['required', 'integer', 'min:1', 'max:999999'],
        ];
    }

    public function messages(): array
    {
        return [
            'duration.required' => 'Duration is required.',
            'duration.min'      => 'Duration must be at least 1.',
            'duration.max'      => 'Duration cannot exceed 365.',
            'unit.required'     => 'Unit is required.',
            'unit.in'           => 'Unit must be day or month.',
            'price.required'    => 'Price is required.',
            'price.min'         => 'Price must be at least ₹1.',
        ];
    }
}
