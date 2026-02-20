<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusMember;
use App\Exports\WaLogsExport;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\WaLog;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WhatsAppController extends Controller
{
    public function __construct(
        private readonly WhatsAppService $whatsAppService,
    ) {
    }

    public function recipientsCount(Request $request)
    {
        $target = $request->query('target', 'active');

        $count = Member::query()
            ->whereNotNull('phone')
            ->when($target === 'active', function ($query) {
                $query->where('is_active', true)
                    ->where('status', StatusMember::STATUS_ACTIVE->value);
            })
            ->count();

        return response()->json([
            'target' => $target,
            'count' => $count,
        ]);
    }

    public function blast(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'target' => ['required', 'in:all,active'],
            'schedule_at' => ['nullable', 'date'],
        ]);

        $phones = Member::query()
            ->whereNotNull('phone')
            ->when($validated['target'] === 'active', function ($query) {
                $query->where('is_active', true)
                    ->where('status', StatusMember::STATUS_ACTIVE->value);
            })
            ->pluck('phone')
            ->map(fn ($phone) => (string) $phone)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($validated['schedule_at'])) {
            $responses = $this->whatsAppService->scheduleMessage(
                phones: $phones,
                message: $validated['message'],
                datetime: Carbon::parse($validated['schedule_at']),
            );

            return response()->json([
                'message' => 'Blast message scheduled.',
                'target' => $validated['target'],
                'total_recipients' => count($phones),
                'results' => $responses,
            ]);
        }

        $responses = $this->whatsAppService->sendBulk($phones, $validated['message']);

        $successCount = collect($responses)->where('success', true)->count();

        return response()->json([
            'message' => 'Blast message processed.',
            'target' => $validated['target'],
            'total_recipients' => count($phones),
            'success' => $successCount,
            'failed' => count($phones) - $successCount,
            'results' => $responses,
        ]);
    }

    public function logs(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'status' => ['nullable', 'in:success,failed,scheduled'],
            'search' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $logs = WaLog::query()
            ->when(!empty($validated['from']), fn ($query) => $query->whereDate('sent_at', '>=', $validated['from']))
            ->when(!empty($validated['to']), fn ($query) => $query->whereDate('sent_at', '<=', $validated['to']))
            ->when(!empty($validated['month']), fn ($query) => $query->whereMonth('sent_at', $validated['month']))
            ->when(!empty($validated['year']), fn ($query) => $query->whereYear('sent_at', $validated['year']))
            ->when(!empty($validated['status']), fn ($query) => $query->where('status', $validated['status']))
            ->when(!empty($validated['search']), function ($query) use ($validated) {
                $term = '%' . $validated['search'] . '%';
                $query->where(function ($nested) use ($term) {
                    $nested->where('phone', 'like', $term)
                        ->orWhere('message', 'like', $term);
                });
            })
            ->orderByDesc('sent_at')
            ->paginate((int) ($validated['per_page'] ?? 20));

        $namesByPhone = Member::query()
            ->whereIn('phone', $logs->getCollection()->pluck('phone')->filter()->unique()->values())
            ->pluck('name', 'phone');

        $logs->setCollection(
            $logs->getCollection()->map(function (WaLog $log) use ($namesByPhone) {
                $payload = $log->toArray();
                $payload['name'] = $namesByPhone->get($log->phone);

                return $payload;
            })
        );

        return response()->json($logs);
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $fileName = sprintf('wa-logs-%04d-%02d.xlsx', $validated['year'], $validated['month']);

        return Excel::download(new WaLogsExport((int) $validated['month'], (int) $validated['year']), $fileName);
    }
}
