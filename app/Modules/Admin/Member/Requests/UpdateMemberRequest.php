<?php

namespace App\Modules\Admin\Member\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_self'   => ['boolean'],
            // 'is_active' can be toggled (deactivate/restore); 'status' is computed, never accepted from input.
            'is_active' => ['boolean'],
        ];
    }
}
