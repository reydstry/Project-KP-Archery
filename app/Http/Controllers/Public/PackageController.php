<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('price')->get();

        return response()->json([
            'data' => $packages,
        ]);
    }
}