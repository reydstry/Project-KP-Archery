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
            UserRoles::ADMIN => view('dashboards.admin.dashboard', compact('user')),
            UserRoles::COACH => view('dashboards.coach.dashboard', compact('user')),
            default => view('dashboards.member', compact('user')),
        };
    }
}
