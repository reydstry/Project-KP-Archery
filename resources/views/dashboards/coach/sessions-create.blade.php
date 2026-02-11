@extends('layouts.coach')

@section('title', 'Create Training Session')
@section('subtitle', 'Create a day session with one or more slots')

@section('content')
<div class="min-h-screen bg-white px-2 py-2 sm:p-8">
    <div class="flex items-center justify-end mb-2 sm:mb-6 card-animate" style="animation-delay: 0.1s">
        <a href="{{ route('coach.sessions.index') }}" class="w-full sm:w-auto shrink-0 px-3 py-1.5 sm:px-5 sm:py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium border border-slate-200 transition-all duration-200 text-center">Back</a>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-200/60 p-2 sm:p-4 card-animate" style="animation-delay: 0.15s">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 sm:gap-4 mb-2 sm:mb-4">
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Date</label>
                <input type="date" id="sessionDate" class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <p class="text-xs text-slate-500 mt-1">Minimal hari ini (sesuai validasi backend).</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Slots</label>
                <p class="text-xs sm:text-sm text-slate-600">Pilih slot yang aktif dan isi kuota per slot.</p>
            </div>
        </div>

        <div class="overflow-x-auto -mx-2 sm:mx-0">
            <table class="w-full min-w-[480px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Use</th>
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsTable">
                    <!-- filled by JS -->
                </tbody>
            </table>
        </div>

        <div class="mt-2 sm:mt-4 flex items-center justify-end gap-2">
            <button type="button" onclick="submitCreate()" id="createBtn" class="w-full sm:w-auto px-3 py-1.5 sm:px-5 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">Create</button>
        </div>
    </div>
</div>


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
                <td colspan="4" class="px-2 sm:px-3 py-6 text-center text-xs sm:text-sm text-slate-600">No active session times found.</td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = SESSION_TIMES.map(st => {
        return `
            <tr>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5">
                    <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" data-session-time-id="${st.id}" checked>
                </td>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5">
                    <p class="text-xs sm:text-sm font-semibold text-slate-900">${st.name}</p>
                </td>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5 text-xs text-slate-600">${st.start_time}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time}</td>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5">
                    <input type="number" min="1" max="50" value="10" class="w-14 sm:w-24 px-2 py-1 sm:px-2.5 sm:py-1.5 bg-slate-50 border border-slate-200 rounded text-xs sm:text-sm focus:ring-2 focus:ring-blue-500" data-max-input-for="${st.id}">
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
