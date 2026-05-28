<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGymOperatingHoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'hours' => ['required', 'array'],

            'hours.*.day' => ['required', 'string'],

            'hours.*.open' => ['nullable', 'date_format:H:i'],

            'hours.*.close' => ['nullable', 'date_format:H:i'],

            'hours.*.closed' => ['required', 'boolean'],
        ];
    }
}
