<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * @deprecated Web dashboards.admin pages telah dipindahkan ke App\Modules\Admin\*\Controllers\*PageController.
 *             Kelas ini dipertahankan sementara untuk backward compatibility transisi.
 */
class AdminUiController extends Controller
{
    public function sessionsIndex()
    {
        return view('dashboards.admin.training.training-sessions');
    }

    public function sessionsCreate()
    {
        return view('dashboards.admin.training.training-sessions-create');
    }

    public function sessionsEdit(int $id)
    {
        return view('dashboards.admin.training.training-sessions-edit-meta', compact('id'));
    }

    public function slotCoachAssignment()
    {
        return view('dashboards.admin.training.slots-coach-assignment');
    }

    public function attendanceManagement()
    {
        return view('dashboards.admin.attendance.attendance-management');
    }

    public function waBlast()
    {
        return view('dashboards.admin.whatsapp.wa-blast');
    }

    public function waLogs()
    {
        return view('dashboards.admin.whatsapp.wa-logs');
    }

    public function exportExcel()
    {
        return redirect()->route('admin.reports.monthly');
    }

    public function waApiSettings()
    {
        return view('dashboards.dashboards.admin.whatsapp.wa-api-settings');
    }

    public function reminderSettings()
    {
        return view('dashboards.dashboards.admin.whatsapp.reminder-settings');
    }
}
