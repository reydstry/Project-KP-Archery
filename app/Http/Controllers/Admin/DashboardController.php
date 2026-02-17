<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardService $adminDashboardService,
    ) {
    }

    public function index()
    {
        return response()->json(
            $this->adminDashboardService
                ->getDashboardData()
                ->toArray()
        );
    }
}
