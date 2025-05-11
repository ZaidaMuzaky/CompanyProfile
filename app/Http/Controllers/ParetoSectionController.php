<?php

namespace App\Http\Controllers;

use App\Models\MainMenu;
use App\Models\MenuSection;
use Illuminate\Http\Request;

class ParetoSectionController extends Controller
{
    // Menampilkan daftar menu section
    public function index(MainMenu $mainMenu, Request $request)
    {
        $search = $request->query('search');
    
        $sections = $mainMenu->menuSections()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get();
    
        return view('admin.pareto.show', compact('sections', 'mainMenu', 'search'));
    }


    // Menyimpan menu section baru
    public function store(Request $request)
    {
        $request->validate([
            'main_menu_id' => 'required|exists:main_menus,id',
            'nama' => 'required|string|max:255',
        ]);

        MenuSection::create($request->all());

        return redirect()->route('admin.pareto.show', $request->main_menu_id)
                         ->with('success', 'Menu Section added successfully!');
    }

    // Mengupdate menu section
    public function update(Request $request, $id)
    {
        $section = MenuSection::findOrFail($id);
        $section->update($request->only('nama'));

        return redirect()->route('admin.pareto.show', $section->main_menu_id)
                         ->with('success', 'Menu Section updated successfully!');
    }

    // Menghapus menu section
    public function destroy($id)
    {
        $section = MenuSection::findOrFail($id);
        $mainMenuId = $section->main_menu_id;
        $section->delete();

        return redirect()->route('admin.pareto.show', $mainMenuId)
                         ->with('success', 'Menu Section deleted successfully!');
    }

    // Menampilkan detail menu section
    public function show($id)
    {
        $section = MenuSection::with('brands')->findOrFail($id);
        $brands = $section->brands; // ambil relasi brand-nya
    
        return view('admin.pareto.brand', compact('section', 'brands'));
    }
    

    



}
