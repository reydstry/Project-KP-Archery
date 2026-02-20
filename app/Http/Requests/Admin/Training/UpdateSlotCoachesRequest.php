<?php

namespace App\Http\Requests\Admin\Training;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlotCoachesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coach_ids' => ['required', 'array', 'min:1'],
            'coach_ids.*' => ['required', 'exists:coaches,id'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
