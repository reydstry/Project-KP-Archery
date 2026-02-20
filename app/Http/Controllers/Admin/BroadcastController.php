<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusMember;
use App\Http\Controllers\Controller;
use App\Jobs\SendBroadcastJob;
use App\Models\Broadcast;
use App\Models\BroadcastLog;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function create()
    {
        return view('dashboards.admin.whatsapp.broadcast');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'message' => ['required', 'string'],
        ]);

        $members = Member::query()
            ->select(['id', 'phone'])
            ->where('status', StatusMember::STATUS_ACTIVE->value)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderBy('id')
            ->get();

        $broadcast = Broadcast::query()->create([
            'title' => $validated['title'],
            'event_date' => $validated['event_date'],
            'message' => $validated['message'],
            'total_target' => $members->count(),
            'status' => 'pending',
            'created_by' => (int) auth()->id(),
        ]);

        if ($members->isNotEmpty()) {
            $now = now();
            $payloads = $members->map(fn (Member $member) => [
                'broadcast_id' => $broadcast->id,
                'member_id' => $member->id,
                'phone_number' => (string) $member->phone,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            foreach (array_chunk($payloads, 500) as $chunk) {
                BroadcastLog::query()->insert($chunk);
            }
        }

        SendBroadcastJob::dispatch($broadcast->id);

        return redirect()
            ->route('admin.whatsapp.logs.show', $broadcast)
            ->with('success', 'Broadcast event berhasil dibuat dan sedang diproses melalui queue.');
    }

    public function index()
    {
        $broadcasts = Broadcast::query()
            ->with('createdBy:id,name')
            ->latest()
            ->get();

        return view('dashboards.admin.whatsapp.logs.index', compact('broadcasts'));
    }

    public function show(Broadcast $broadcast)
    {
        $broadcast->load([
            'createdBy:id,name',
            'logs' => fn ($query) => $query
                ->with('member:id,name,phone')
                ->latest(),
        ]);

        return view('dashboards.admin.whatsapp.logs.show', compact('broadcast'));
    }
}
