<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FnbMenu;
use Illuminate\Http\Request;

class FnbMenuController extends Controller
{
    public function index()
    {
        $menus = FnbMenu::all();
        return view('admin.fnb_menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.fnb_menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Unavailable',
        ]);

        FnbMenu::create($validated);

        return redirect()->route('admin.fnb-menus.index')->with('success', 'Menu FnB berhasil ditambahkan.');
    }

    public function edit(FnbMenu $fnbMenu)
    {
        return view('admin.fnb_menus.edit', compact('fnbMenu'));
    }

    public function update(Request $request, FnbMenu $fnbMenu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Unavailable',
        ]);

        $fnbMenu->update($validated);

        return redirect()->route('admin.fnb-menus.index')->with('success', 'Menu FnB berhasil diupdate.');
    }

    public function destroy(FnbMenu $fnbMenu)
    {
        $fnbMenu->delete();
        return redirect()->route('admin.fnb-menus.index')->with('success', 'Menu FnB berhasil dihapus.');
    }
}
