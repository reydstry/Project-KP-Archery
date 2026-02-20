<?php

namespace App\Modules\Admin\Member\Requests;

use App\Enums\StatusMember;
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_self' => ['boolean'],
            'is_active' => ['boolean'],
            'status' => ['sometimes', 'string', 'in:' . implode(',', [
                StatusMember::STATUS_PENDING->value,
                StatusMember::STATUS_ACTIVE->value,
                StatusMember::STATUS_INACTIVE->value,
            ])],
        ];
    }
}
