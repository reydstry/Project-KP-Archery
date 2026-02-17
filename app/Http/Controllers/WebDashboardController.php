<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use Illuminate\Http\Request;

class WebDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            UserRoles::ADMIN => view('admin.dashboard.dashboard', compact('user')),
            UserRoles::COACH => view('components.dashboards.coach.dashboard', compact('user')),
            default => view('components.dashboards.member.dashboard', compact('user')),
        };
    }
}
