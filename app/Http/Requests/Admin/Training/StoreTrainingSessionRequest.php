<?php

namespace App\Http\Requests\Admin\Training;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:today', 'unique:training_sessions,date'],
            'status' => ['nullable', 'in:open,closed,canceled'],
            'slots' => ['sometimes', 'array', 'min:1'],
            'slots.*.session_time_id' => ['required_with:slots', 'exists:session_times,id'],
            'slots.*.max_participants' => ['required_with:slots', 'integer', 'min:1', 'max:50'],
            'slots.*.coach_ids' => ['required_with:slots', 'array', 'min:1'],
            'slots.*.coach_ids.*' => ['required_with:slots', 'exists:coaches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal sesi wajib diisi.',
            'date.after_or_equal' => 'Tanggal sesi tidak boleh sebelum hari ini.',
            'date.unique' => 'Sesi untuk tanggal ini sudah ada. Pilih tanggal lain.',
            'slots.required' => 'Minimal pilih satu slot sesi.',
            'slots.min' => 'Minimal pilih satu slot sesi.',
            'slots.*.session_time_id.required' => 'Waktu sesi wajib dipilih.',
            'slots.*.max_participants.required' => 'Kuota peserta wajib diisi.',
            'slots.*.max_participants.min' => 'Kuota minimal 1 peserta.',
            'slots.*.max_participants.max' => 'Kuota maksimal 50 peserta.',
            'slots.*.coach_ids.required' => 'Setiap slot wajib memiliki minimal 1 coach.',
            'slots.*.coach_ids.min' => 'Setiap slot wajib memiliki minimal 1 coach.',
        ];
    }
}
