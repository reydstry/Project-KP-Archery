<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use App\Services\WhatsAppSettingsService;
use Illuminate\Http\Request;

class WhatsAppSettingsController extends Controller
{
    public function __construct(
        private readonly WhatsAppSettingsService $settingsService,
        private readonly WhatsAppService $whatsAppService,
    ) {
    }

    public function show()
    {
        return response()->json([
            'data' => $this->settingsService->getWhatsAppSettings(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'driver' => ['required', 'in:wablas,dummy'],
            'base_url' => ['nullable', 'url'],
            'token' => ['nullable', 'string', 'max:512'],
            'secret_key' => ['nullable', 'string', 'max:512'],
            'timeout' => ['nullable', 'integer', 'min:3', 'max:120'],
            'sandbox' => ['nullable', 'boolean'],
        ]);

        $settings = $this->settingsService->saveWhatsAppSettings($validated);

        return response()->json([
            'message' => 'WhatsApp settings updated.',
            'data' => $settings,
        ]);
    }

    public function testConnection(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $response = $this->whatsAppService->sendMessage(
            phone: $validated['phone'],
            message: $validated['message'] ?? 'Test koneksi WhatsApp dari sistem admin.',
        );

        return response()->json([
            'message' => 'Test connection executed.',
            'data' => $response,
        ]);
    }
}
