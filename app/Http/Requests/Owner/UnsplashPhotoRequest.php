<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UnsplashPhotoRequest extends FormRequest
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
            'url'               => ['required', 'url', 'starts_with:https://images.unsplash.com'],
            'photographer_name' => ['required', 'string', 'max:100'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'url.required'               => 'Unsplash photo URL is required.',
            'url.starts_with'            => 'URL must be a valid Unsplash image URL.',
            'photographer_name.required' => 'Photographer name is required for attribution.',
        ];
    }
}
