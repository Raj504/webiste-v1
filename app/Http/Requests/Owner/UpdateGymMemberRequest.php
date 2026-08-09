<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

// ─────────────────────────────────────────────────────────────────────────────
// PUT /api/owner/gym/members/{memberId} — edit details, or renew (start_date + duration_type)
// ─────────────────────────────────────────────────────────────────────────────
class UpdateGymMemberRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'string', 'max:255'],
            'phone'         => ['sometimes', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:255'],
            'start_date'    => ['sometimes', 'date'],
            'duration_type' => ['sometimes', 'in:1_month,3_months,6_months,12_months,custom'],
            'custom_days'   => ['required_if:duration_type,custom', 'integer', 'min:1', 'max:730'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email'              => 'Enter a valid email address.',
            'start_date.date'          => 'Start date must be a valid date.',
            'duration_type.in'         => 'Invalid membership duration.',
            'custom_days.required_if'  => 'Enter the number of days for a custom duration.',
        ];
    }
}
