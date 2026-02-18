<?php

namespace App\Http\Requests\Admin\Training;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrainingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sessionId = (int) $this->route('trainingSession')?->id;

        return [
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                Rule::unique('training_sessions', 'date')->ignore($sessionId),
            ],
            'status' => ['nullable', Rule::in(['open', 'closed', 'canceled'])],
        ];
    }
}
