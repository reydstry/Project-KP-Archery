<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * @deprecated Web admin pages telah dipindahkan ke App\Modules\Admin\*\Controllers\*PageController.
 *             Kelas ini dipertahankan sementara untuk backward compatibility transisi.
 */
class AdminUiController extends Controller
{
    public function sessionsIndex()
    {
        return view('admin.training.training-sessions');
    }

    public function sessionsCreate()
    {
        return view('admin.training.training-sessions-create');
    }

    public function sessionsEdit(int $id)
    {
        return view('admin.training.training-sessions-edit-meta', compact('id'));
    }

    public function slotCoachAssignment()
    {
        return view('admin.training.slots-coach-assignment');
    }

    public function attendanceManagement()
    {
        return view('admin.attendance.attendance-management');
    }

    public function waBlast()
    {
        return view('admin.whatsapp.wa-blast');
    }

    public function waLogs()
    {
        return view('admin.whatsapp.wa-logs');
    }

    public function monthlyRecap()
    {
        return view('admin.report.monthly-recap');
    }

    public function exportExcel()
    {
        return view('admin.report.export-excel');
    }

    public function waApiSettings()
    {
        return view('admin.whatsapp.wa-api-settings');
    }

    public function reminderSettings()
    {
        return view('admin.whatsapp.reminder-settings');
    }
}
