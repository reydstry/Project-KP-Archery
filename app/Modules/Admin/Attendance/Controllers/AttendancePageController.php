<?php

namespace App\Modules\Admin\Attendance\Controllers;

use App\Http\Controllers\Controller;

class AttendancePageController extends Controller
{
    public function attendanceManagement()
    {
        return view('dashboards.admin.attendance.attendance-management');
    }

    public function sessionAttendanceInput(int $id)
    {
        return view('dashboards.admin.attendance.sessions-attendance-input', [
            'sessionId' => $id,
        ]);
    }
}
