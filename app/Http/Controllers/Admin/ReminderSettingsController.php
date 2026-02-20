<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppSettingsService;
use Illuminate\Http\Request;

class ReminderSettingsController extends Controller
{
    public function __construct(
        private readonly WhatsAppSettingsService $settingsService,
    ) {
    }

    public function show()
    {
        return response()->json([
            'data' => $this->settingsService->getReminderSettings(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'days_before_expired' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $settings = $this->settingsService->saveReminderSettings($validated);

        return response()->json([
            'message' => 'Reminder settings updated.',
            'data' => $settings,
        ]);
    }
}
