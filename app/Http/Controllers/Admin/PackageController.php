<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $packages,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'session_count' => ['required', 'integer', 'min:1'],
        ]);

        $package = Package::create($data);

        return response()->json([
            'message' => 'Paket berhasil dibuat',
            'data' => $package,
        ], 201);
    }

    public function show(Package $package)
    {
        return response()->json([
            'data' => $package,
        ]);
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'duration_days' => ['sometimes', 'integer', 'min:1'],
            'session_count' => ['sometimes', 'integer', 'min:1'],
        ]);

        $package->update($data);

        return response()->json([
            'message' => 'Paket berhasil diupdate',
            'data' => $package,
        ]);
    }

    public function destroy(Package $package)
    {
        if ($package->memberPackages()->exists()) {
            return response()->json([
                'message' => 'Paket tidak dapat dihapus karena sedang digunakan',
            ], 400);
        }

        $package->delete();

        return response()->json([
            'message' => 'Paket berhasil dihapus',
        ]);
    }
}