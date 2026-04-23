<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class SaveVideoUrlRequest extends FormRequest
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
            'url'    => ['required', 'url', 'max:500'],
            'source' => ['required', 'in:youtube,instagram'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'url.required'    => 'Video URL is required.',
            'url.url'         => 'Please enter a valid URL.',
            'source.required' => 'Video source is required.',
            'source.in'       => 'Source must be youtube or instagram.',
        ];
    }
}
