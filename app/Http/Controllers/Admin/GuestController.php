<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::all();
        return view('admin.guests.index', compact('guests'));
    }

    public function create()
    {
        return view('admin.guests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'identity_number' => 'required|string|unique:guests,identity_number',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        Guest::create($validated);

        return redirect()->route('admin.guests.index')->with('success', 'Data tamu berhasil ditambahkan.');
    }

    public function edit(Guest $guest)
    {
        return view('admin.guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'identity_number' => 'required|string|unique:guests,identity_number,'.$guest->id,
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $guest->update($validated);

        return redirect()->route('admin.guests.index')->with('success', 'Data tamu berhasil diupdate.');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->route('admin.guests.index')->with('success', 'Data tamu berhasil dihapus.');
    }
}
