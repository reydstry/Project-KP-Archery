<?php

namespace App\Modules\Admin\WhatsApp\Controllers;

use App\Http\Controllers\Controller;

class WhatsAppPageController extends Controller
{
    public function waBlast()
    {
        return view('admin.whatsapp.wa-blast');
    }

    public function waLogs()
    {
        return view('admin.whatsapp.wa-logs');
    }

    public function waApiSettings()
    {
        return view('dashboards.admin.whatsapp.wa-api-settings');
    }

    public function reminderSettings()
    {
        return view('dashboards.admin.whatsapp.reminder-settings');
    }
}
