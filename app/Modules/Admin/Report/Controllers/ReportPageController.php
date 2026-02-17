<?php

namespace App\Modules\Admin\Report\Controllers;

use App\Http\Controllers\Controller;

class ReportPageController extends Controller
{
    public function monthlyRecap()
    {
        return view('admin.report.monthly-recap');
    }

    public function exportExcel()
    {
        return view('admin.report.export-excel');
    }
}
