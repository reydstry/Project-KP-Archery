<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use App\Services\WhatsAppSettingsService;
use Illuminate\Console\Command;

class SendPackageExpiryReminderCommand extends Command
{
    protected $signature = 'reminder:package-expiry {--days= : Days before package expiry date}';

    protected $description = 'Send package expiry reminder through WhatsApp abstraction service';

    public function __construct(
        private readonly WhatsAppService $whatsAppService,
        private readonly WhatsAppSettingsService $settingsService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $settings = $this->settingsService->getReminderSettings();

        if (!($settings['enabled'] ?? true)) {
            $this->warn('Reminder package expiry is disabled from settings.');

            return self::SUCCESS;
        }

        $daysOption = $this->option('days');
        $days = $daysOption !== null
            ? max(1, (int) $daysOption)
            : max(1, (int) ($settings['days_before_expired'] ?? 7));

        $result = $this->whatsAppService->sendExpiryReminder($days);

        $this->info('Package expiry reminder executed.');
        $this->table(
            ['Target Date', 'Candidate', 'Sent'],
            [[
                $result['target_date'] ?? '-',
                $result['total_candidate'] ?? 0,
                $result['total_sent'] ?? 0,
            ]]
        );

        return self::SUCCESS;
    }
}
