@extends('layouts.coach')

@section('content')
<div class="min-h-screen bg-white p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.1s">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Create Training Session</h1>
            <p class="text-slate-600 text-base sm:text-lg">Create a day session with one or more slots</p>
        </div>
        <a href="{{ route('coach.sessions.index') }}" class="w-full sm:w-auto shrink-0 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-center">Back</a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sm:p-6 card-animate" style="animation-delay: 0.15s">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Date</label>
                <input type="date" id="sessionDate" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <p class="text-xs text-slate-500 mt-2">Minimal hari ini (sesuai validasi backend).</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Slots</label>
                <p class="text-sm text-slate-600">Pilih slot yang aktif dan isi kuota per slot.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Use</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max Participants</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsTable">
                    <!-- filled by JS -->
                </tbody>
            </table>
        </div>

        <div class="mt-4 sm:mt-6 flex items-center justify-end gap-3">
            <button type="button" onclick="submitCreate()" id="createBtn" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">Create</button>
        </div>
    </div>
</div>

@php
    /** @var \Illuminate\Support\Collection $sessionTimes */
@endphp

<script>
const SESSION_TIMES = @json($sessionTimes ?? []);

document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('sessionDate');
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    dateInput.min = todayStr;
    dateInput.value = todayStr;

    renderSlotsTable();
});

function renderSlotsTable() {
    const tbody = document.getElementById('slotsTable');

    if (!Array.isArray(SESSION_TIMES) || SESSION_TIMES.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-slate-600">No active session times found.</td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = SESSION_TIMES.map(st => {
        return `
            <tr>
                <td class="px-6 py-4">
                    <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" data-session-time-id="${st.id}" checked>
                </td>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-900">${st.name}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${st.start_time}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time}</td>
                <td class="px-6 py-4">
                    <input type="number" min="1" max="50" value="10" class="w-28 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" data-max-input-for="${st.id}">
                </td>
            </tr>
        `;
    }).join('');
}

async function submitCreate() {
    const btn = document.getElementById('createBtn');
    const date = document.getElementById('sessionDate').value;

    if (!date) {
        window.showToast('Date is required', 'error');
        return;
    }

    const selected = Array.from(document.querySelectorAll('#slotsTable input[type="checkbox"][data-session-time-id]'))
        .filter(cb => cb.checked)
        .map(cb => {
            const sessionTimeId = Number(cb.getAttribute('data-session-time-id'));
            const maxInput = document.querySelector(`#slotsTable input[data-max-input-for="${sessionTimeId}"]`);
            const maxParticipants = Number(maxInput?.value || 0);
            return { session_time_id: sessionTimeId, max_participants: maxParticipants };
        });

    if (selected.length === 0) {
        window.showToast('Select at least one slot', 'error');
        return;
    }

    const invalid = selected.find(s => !Number.isInteger(s.max_participants) || s.max_participants < 1 || s.max_participants > 50);
    if (invalid) {
        window.showToast('Max participants must be 1-50', 'error');
        return;
    }

    btn.disabled = true;
    const original = btn.textContent;
    btn.textContent = 'Creating...';

    try {
        await window.API.post('/coach/training-sessions', {
            date,
            slots: selected,
        });

        window.showToast('Training session created', 'success');
        window.location.href = '{{ route('coach.sessions.index') }}';
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to create session', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = original;
    }
}
</script>
@endsection
