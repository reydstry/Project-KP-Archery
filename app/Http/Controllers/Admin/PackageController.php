<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Services\Admin\PackageManagementService;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct(
        private readonly PackageManagementService $packageManagementService,
    ) {
    }

    /**
     * Display a listing of packages.
     */
    public function index()
    {
        return response()->json($this->packageManagementService->listPackages());
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'session_count' => ['required', 'integer', 'min:1'],
        ]);

        $package = $this->packageManagementService->createPackage($data);

        return response()->json([
            'message' => 'Package berhasil dibuat',
            'data' => $package,
        ], 201);
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package)
    {
        return response()->json($this->packageManagementService->showPackage($package));
    }

    /**
     * Update the specified package.
     */
    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'session_count' => ['required', 'integer', 'min:1'],
        ]);

        return response()->json($this->packageManagementService->updatePackage($package, $data));
    }

    /**
     * Remove the specified package.
     */
    public function destroy(Package $package)
    {
        return response()->json($this->packageManagementService->deactivatePackage($package));
    }

    public function restore(Package $package)
    {
        return response()->json($this->packageManagementService->restorePackage($package));
    }
}
