<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/owner/gym/amenities/custom
// Add a brand new amenity to the global list + auto-select for this gym
// ─────────────────────────────────────────────────────────────────────────────
class AddCustomAmenityRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:amenities,name'],
            'icon' => ['nullable', 'string', 'max:10'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'name.required' => 'Amenity name is required.',
            'name.max'      => 'Amenity name cannot exceed 50 characters.',
            'name.unique'   => 'This amenity already exists in the list.',
            'icon.max'      => 'Icon must be a single emoji.',
        ];
    }
}
