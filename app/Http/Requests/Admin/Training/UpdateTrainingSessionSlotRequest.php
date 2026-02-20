<?php

namespace App\Http\Requests\Admin\Training;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingSessionSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_time_id' => ['sometimes', 'integer', 'exists:session_times,id'],
            'max_participants' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'coach_ids' => ['sometimes', 'array', 'min:1'],
            'coach_ids.*' => ['required_with:coach_ids', 'integer', 'exists:coaches,id'],
        ];
    }
}
