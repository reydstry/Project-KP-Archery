<?php

namespace App\Modules\Admin\Member\Controllers;

use App\Http\Controllers\Controller;

class MemberPageController extends Controller
{
    public function index()
    {
        return view('admin.member.members');
    }

    public function packages()
    {
        return view('admin.member.member-packages');
    }
}
