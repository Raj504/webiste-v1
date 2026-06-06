<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class SyncAmenitiesRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
    public function rules(): array
    {
        return [
            'amenity_ids'   => ['required', 'array'],
            'amenity_ids.*' => ['integer', 'exists:amenities,id'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'amenity_ids.required'   => 'Please select at least one amenity.',
            'amenity_ids.*.exists'   => 'One or more selected amenities do not exist.',
        ];
    }
}
