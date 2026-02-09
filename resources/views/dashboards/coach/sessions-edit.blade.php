@extends('layouts.coach')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Edit Training Session</h1>
            <p class="text-slate-600 text-lg">Update slot quotas for this day</p>
        </div>
        <a href="{{ route('coach.sessions.index') }}" class="px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200">Back</a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-900" id="sessionHeader">Loading...</h2>
            <p class="text-sm text-slate-600" id="sessionSubheader">Please wait</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max Participants</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsBody">
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-600">Loading slots...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const SESSION_ID = @json($id ?? null);

document.addEventListener('DOMContentLoaded', async () => {
    if (!SESSION_ID) {
        window.showToast('Missing session id', 'error');
        return;
    }

    try {
        const session = await window.API.get(`/coach/training-sessions/${SESSION_ID}`);
        const dateStr = (session.date || '').toString().slice(0, 10);
        document.getElementById('sessionHeader').textContent = `Training Session - ${dateStr}`;
        document.getElementById('sessionSubheader').textContent = `Status: ${(session.status || '').toString()}`;

        renderSlots(session.slots || []);
    } catch (e) {
        console.error(e);
        document.getElementById('slotsBody').innerHTML = `
            <tr><td colspan="4" class="px-6 py-10 text-center text-slate-600">Failed to load session: ${escapeHtml(e?.message || 'Unknown error')}</td></tr>
        `;
    }
});

function renderSlots(slots) {
    const tbody = document.getElementById('slotsBody');

    if (!Array.isArray(slots) || slots.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-slate-600">No slots found for this session.</td></tr>`;
        return;
    }

    tbody.innerHTML = slots.map(slot => {
        const st = slot.session_time || slot.sessionTime || {};
        return `
            <tr>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-900">${escapeHtml(st.name || 'Session')}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${escapeHtml(st.start_time || '')}${st.start_time && st.end_time ? ' - ' : ''}${escapeHtml(st.end_time || '')}</td>
                <td class="px-6 py-4">
                    <input type="number" min="1" max="50" value="${slot.max_participants ?? 1}" class="w-28 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" id="quota-${slot.id}">
                </td>
                <td class="px-6 py-4">
                    <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 text-sm" onclick="updateQuota(${slot.id})">Update</button>
                </td>
            </tr>
        `;
    }).join('');
}

async function updateQuota(slotId) {
    const input = document.getElementById(`quota-${slotId}`);
    const max = Number(input?.value || 0);
    if (!Number.isInteger(max) || max < 1 || max > 50) {
        window.showToast('Max participants must be 1-50', 'error');
        return;
    }

    try {
        await window.API.patch(`/coach/training-sessions/${SESSION_ID}/quota`, {
            slot_id: slotId,
            max_participants: max,
        });
        window.showToast('Quota updated', 'success');
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to update quota', 'error');
    }
}

function escapeHtml(str) {
    return (str || '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
</script>
@endsection
