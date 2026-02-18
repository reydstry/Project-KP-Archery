<?php

namespace App\Http\Requests\Admin\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class SyncAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sessionId = (int) $this->route('trainingSession')?->id;

        return [
            'session_id' => ['required', 'integer', 'in:' . $sessionId],
            'member_ids' => ['required', 'array'],
            'member_ids.*' => ['required', 'integer', 'distinct', 'exists:members,id'],
        ];
    }
}
