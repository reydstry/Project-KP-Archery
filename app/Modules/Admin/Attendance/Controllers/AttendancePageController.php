<?php

namespace App\Modules\Admin\Attendance\Controllers;

use App\Http\Controllers\Controller;

class AttendancePageController extends Controller
{
    public function attendanceManagement()
    {
        return view('admin.attendance.attendance-management');
    }
}
