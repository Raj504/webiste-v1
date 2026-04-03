<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGymRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'address_text'=> 'sometimes|string|max:255',
            'city'        => 'sometimes|string|max:255',
            'area'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            // 'upi_id'      => 'nullable|string|max:255',
            'owner_name'   => 'sometimes|string|max:255',
            'phone'        => 'sometimes|string|max:15|unique:users,phone,' . auth()->id(),
        ];
    }
}
