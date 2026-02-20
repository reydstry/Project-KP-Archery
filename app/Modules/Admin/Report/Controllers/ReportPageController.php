<?php

namespace App\Modules\Admin\Report\Controllers;

use App\Http\Controllers\Controller;

class ReportPageController extends Controller
{

    public function exportExcel()
    {
        return redirect()->route('admin.reports.monthly');
    }
}
