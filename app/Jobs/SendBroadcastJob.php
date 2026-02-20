<?php

namespace App\Jobs;

use App\Models\Broadcast;
use App\Models\BroadcastLog;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendBroadcastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $broadcastId,
    ) {
    }

    public function handle(WhatsAppService $whatsAppService): void
    {
        $broadcast = Broadcast::query()
            ->with(['logs' => fn ($query) => $query->where('status', 'pending')->orderBy('id')])
            ->find($this->broadcastId);

        if (!$broadcast) {
            return;
        }

        $broadcast->update([
            'status' => 'processing',
            'total_success' => 0,
            'total_failed' => 0,
        ]);

        $success = 0;
        $failed = 0;

        foreach ($broadcast->logs as $log) {
            try {
                $response = $whatsAppService->sendMessage($log->phone_number, $broadcast->message);
                $isSuccess = (bool) ($response['success'] ?? false);

                if ($isSuccess) {
                    $log->update([
                        'status' => 'success',
                        'response' => is_string($response['body'] ?? null)
                            ? $response['body']
                            : json_encode($response['body'] ?? $response),
                        'sent_at' => now(),
                    ]);
                    $success++;
                } else {
                    $log->update([
                        'status' => 'failed',
                        'response' => is_string($response['body'] ?? null)
                            ? $response['body']
                            : json_encode($response['body'] ?? $response),
                    ]);
                    $failed++;
                }
            } catch (Throwable $exception) {
                $log->update([
                    'status' => 'failed',
                    'response' => $exception->getMessage(),
                ]);

                $failed++;
            }

            sleep(1);
        }

        $broadcast->update([
            'total_success' => $success,
            'total_failed' => $failed,
            'status' => 'completed',
        ]);
    }
}
