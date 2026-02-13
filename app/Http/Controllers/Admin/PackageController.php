<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of packages.
     */
    public function index()
    {
        $packages = Package::latest()->get();

        return response()->json([
            'message' => 'Data packages berhasil diambil',
            'data' => $packages,
        ]);
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

        $data['is_active'] = true;

        $package = Package::create($data);

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
        return response()->json([
            'message' => 'Data package berhasil diambil',
            'data' => $package,
        ]);
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

        $package->update($data);

        return response()->json([
            'message' => 'Package berhasil diupdate',
            'data' => $package->fresh(),
        ]);
    }

    /**
     * Remove the specified package.
     */
    public function destroy(Package $package)
    {
        $package->update(['is_active' => false]);

        return response()->json([
            'message' => 'Package berhasil dihapus',
        ]);
    }

    public function restore(Package $package)
    {
        $package->update(['is_active' => true]);

        return response()->json([
            'message' => 'Package berhasil diaktifkan',
            'data' => $package->fresh(),
        ]);
    }
}
