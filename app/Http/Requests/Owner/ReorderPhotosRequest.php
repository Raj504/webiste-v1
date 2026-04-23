<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class ReorderPhotosRequest extends FormRequest
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
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'string', 'uuid'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'ids.required' => 'Photo order is required.',
            'ids.*.uuid'   => 'Each photo ID must be a valid UUID.',
        ];
    }
}
