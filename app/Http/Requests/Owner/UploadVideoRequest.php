<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UploadVideoRequest extends FormRequest
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
            'video' => ['required', 'file', 'mimes:mp4,mov,avi,mkv', 'max:102400'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'video.required' => 'Please select a video file.',
            'video.mimes'    => 'Accepted formats: MP4, MOV, AVI, MKV.',
            'video.max'      => 'Video must be under 100MB.',
        ];
    }
}
