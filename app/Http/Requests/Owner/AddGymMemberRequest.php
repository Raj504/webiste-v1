<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/owner/gym/members — add a member, or renew an existing one (by phone)
// ─────────────────────────────────────────────────────────────────────────────
class AddGymMemberRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:255'],
            'start_date'    => ['required', 'date'],
            'duration_type' => ['required', 'in:1_month,3_months,6_months,12_months,custom'],
            'custom_days'   => ['required_if:duration_type,custom', 'integer', 'min:1', 'max:730'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Member name is required.',
            'phone.required'         => 'Phone number is required.',
            'email.email'            => 'Enter a valid email address.',
            'start_date.required'    => 'Start date is required.',
            'start_date.date'        => 'Start date must be a valid date.',
            'duration_type.required' => 'Select a membership duration.',
            'duration_type.in'       => 'Invalid membership duration.',
            'custom_days.required_if' => 'Enter the number of days for a custom duration.',
        ];
    }
}
