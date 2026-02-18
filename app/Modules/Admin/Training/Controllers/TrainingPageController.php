<?php

namespace App\Modules\Admin\Training\Controllers;

use App\Http\Controllers\Controller;

class TrainingPageController extends Controller
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
}
