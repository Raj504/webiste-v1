<?php
// app/Http/Requests/NearbyGymRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NearbyGymRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lat'    => 'required|numeric|between:-90,90',
            'lng'    => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'lat.required'    => 'Latitude is required.',
            'lat.between'     => 'Latitude must be between -90 and 90.',
            'lng.required'    => 'Longitude is required.',
            'lng.between'     => 'Longitude must be between -180 and 180.',
            'radius.max'      => 'Radius cannot exceed 50km.',
        ];
    }
}