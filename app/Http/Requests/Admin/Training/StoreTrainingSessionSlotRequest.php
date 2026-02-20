<?php

namespace App\Http\Requests\Admin\Training;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingSessionSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_time_id' => ['required', 'integer', 'exists:session_times,id'],
            'max_participants' => ['required', 'integer', 'min:1', 'max:50'],
            'coach_ids' => ['required', 'array', 'min:1'],
            'coach_ids.*' => ['required', 'integer', 'exists:coaches,id'],
        ];
    }
}
