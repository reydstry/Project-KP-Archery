<?php

namespace App\Services;

use App\Contracts\WhatsAppGatewayInterface;
use App\Models\MemberPackage;
use App\Models\WaLog;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function __construct(
        private readonly WhatsAppGatewayInterface $gateway,
    ) {
    }

    public function sendMessage(string $phone, string $message): array
    {
        $response = $this->gateway->sendMessage($this->normalizePhone($phone), $message);
        $status = ($response['success'] ?? false) ? 'success' : 'failed';

        $this->logResult($phone, $response, $status);

        return $response;
    }

    public function sendBulk(array $phones, string $message): array
    {
        $normalizedPhones = array_values(array_filter(array_map(fn (string $phone) => $this->normalizePhone($phone), $phones)));

        $responses = $this->gateway->sendBulk($normalizedPhones, $message);

        foreach ($responses as $response) {
            $status = ($response['success'] ?? false) ? 'success' : 'failed';
            $this->logResult((string) ($response['phone'] ?? ''), $response, $status);
        }

        return $responses;
    }

    public function scheduleMessage(array $phones, string $message, DateTimeInterface $datetime): array
    {
        $normalizedPhones = array_values(array_filter(array_map(fn (string $phone) => $this->normalizePhone($phone), $phones)));

        $responses = $this->gateway->scheduleMessage($normalizedPhones, $message, $datetime);

        foreach ($responses as $response) {
            $status = ($response['success'] ?? false) ? 'scheduled' : 'failed';
            $this->logResult((string) ($response['phone'] ?? ''), $response, $status);
        }

        return $responses;
    }

    public function logResult(string $phone, mixed $response, string $status): WaLog
    {
        $message = is_array($response)
            ? (string) ($response['message'] ?? data_get($response, 'body.message', ''))
            : '';

        $log = WaLog::query()->create([
            'phone' => $phone,
            'message' => $message,
            'response' => is_string($response) ? $response : json_encode($response),
            'status' => $status,
            'sent_at' => now(),
        ]);

        Log::channel(config('whatsapp.log_channel', config('logging.default')))
            ->info('whatsapp.dispatch', [
                'phone' => $log->phone,
                'status' => $log->status,
                'sent_at' => $log->sent_at?->toIso8601String(),
            ]);

        return $log;
    }

    public function sendExpiryReminder(int $daysBeforeExpiry = 7): array
    {
        $targetDate = CarbonImmutable::today()->addDays($daysBeforeExpiry)->toDateString();

        $memberPackages = MemberPackage::query()
            ->active()
            ->with(['member:id,name,phone', 'package:id,name'])
            ->whereDate('end_date', $targetDate)
            ->get(['id', 'member_id', 'package_id', 'end_date']);

        $payloads = $memberPackages
            ->filter(fn (MemberPackage $memberPackage) => !empty($memberPackage->member?->phone))
            ->map(function (MemberPackage $memberPackage) {
                $memberName = $memberPackage->member?->name ?? 'Member';
                $packageName = $memberPackage->package?->name ?? 'Paket';
                $expiryDate = $memberPackage->end_date?->format('d-m-Y') ?? '-';

                return [
                    'phone' => (string) $memberPackage->member?->phone,
                    'message' => "Halo {$memberName}, paket {$packageName} Anda akan berakhir pada {$expiryDate}. Silakan lakukan perpanjangan agar sesi latihan tetap aktif.",
                ];
            })
            ->values();

        $results = [];
        foreach ($payloads as $payload) {
            $results[] = $this->sendMessage($payload['phone'], $payload['message']);
        }

        return [
            'target_date' => $targetDate,
            'total_candidate' => $memberPackages->count(),
            'total_sent' => count($results),
            'results' => $results,
        ];
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (!str_starts_with($digits, '62')) {
            return '62' . $digits;
        }

        return $digits;
    }
}
