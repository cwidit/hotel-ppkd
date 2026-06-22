<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaundryService;
use Illuminate\Http\Request;

class LaundryServiceController extends Controller
{
    public function index()
    {
        $services = LaundryService::all();
        return view('admin.laundry_services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.laundry_services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        LaundryService::create($validated);

        return redirect()->route('admin.laundry-services.index')->with('success', 'Layanan Laundry berhasil ditambahkan.');
    }

    public function edit(LaundryService $laundryService)
    {
        return view('admin.laundry_services.edit', compact('laundryService'));
    }

    public function update(Request $request, LaundryService $laundryService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $laundryService->update($validated);

        return redirect()->route('admin.laundry-services.index')->with('success', 'Layanan Laundry berhasil diupdate.');
    }

    public function destroy(LaundryService $laundryService)
    {
        $laundryService->delete();
        return redirect()->route('admin.laundry-services.index')->with('success', 'Layanan Laundry berhasil dihapus.');
    }
}
