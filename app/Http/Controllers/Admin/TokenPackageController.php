<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TokenPackage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TokenPackageController extends Controller
{
    /**
     * Display a listing of token packages.
     */
    public function index()
    {
        $packages = TokenPackage::latest()->get();

        return Inertia::render('Admin/Packages/Index', [
            'packages' => $packages,
        ]);
    }

    /**
     * Store a newly created token package.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tokens' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        TokenPackage::create($validated);

        return redirect()->back()->with('success', 'Paket Token baru berhasil ditambahkan.');
    }

    /**
     * Update the specified token package.
     */
    public function update(Request $request, TokenPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tokens' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $package->update($validated);

        return redirect()->back()->with('success', 'Paket Token berhasil diperbarui.');
    }

    /**
     * Remove the specified token package.
     */
    public function destroy(TokenPackage $package)
    {
        $package->delete();

        return redirect()->back()->with('success', 'Paket Token berhasil dihapus.');
    }
}
