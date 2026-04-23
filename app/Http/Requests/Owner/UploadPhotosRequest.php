<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'photos'   => ['required', 'array', 'min:1', 'max:10'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'photos.required'   => 'Please select at least one photo.',
            'photos.max'        => 'You can upload a maximum of 10 photos at once.',
            'photos.*.image'    => 'Each file must be an image.',
            'photos.*.mimes'    => 'Accepted formats: JPG, PNG, WEBP.',
            'photos.*.max'      => 'Each photo must be under 5MB.',
        ];
    }
}
