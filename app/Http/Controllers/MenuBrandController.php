<?php

namespace App\Http\Controllers;

use App\Models\MenuBrand;
use App\Models\MenuSection;
use Illuminate\Http\Request;

class MenuBrandController extends Controller
{
        public function index(Request $request, $id)
    {
        $section = MenuSection::findOrFail($id);

        $search = $request->query('search');

        $brandsQuery = MenuBrand::where('menu_section_id', $id);

        if ($search) {
            $brandsQuery->where('nama', 'like', '%' . $search . '%');
        }

        $brands = $brandsQuery->get();

        return view('admin.pareto.brand', compact('section', 'brands', 'search'));
    }
    public function main($id)
{
    // Ambil brand beserta file-file terkait dan section yang terkait
    $brand = MenuBrand::with('files', 'section')->findOrFail($id);

    // Ambil file yang berhubungan dengan brand tersebut
    $files = $brand->files;

    // Ambil section terkait dengan brand
    $section = $brand->section;  // Pastikan ada relasi section di model MenuBrand

    // Kembalikan data ke view
    return view('admin.pareto.main', compact('brand', 'files', 'section'));
}


        
    
        // Menyimpan Brand baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'menu_section_id' => 'required|exists:menu_sections,id'
        ]);

        MenuBrand::create([
            'nama' => $request->nama,
            'menu_section_id' => $request->menu_section_id
        ]);

        return redirect()->route('admin.menuBrands.index', $request->menu_section_id)
                        ->with('success', 'Brand added successfully');
    }

    // Update data Brand
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $brand = MenuBrand::findOrFail($id);
        $brand->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('admin.menuBrands.index', $brand->menu_section_id)
                        ->with('success', 'Brand updated successfully');
    }

    // Hapus Brand
    public function destroy($id)
    {
        $brand = MenuBrand::findOrFail($id);
        $brand->delete();

        return redirect()->route('admin.menuBrands.index', $brand->menu_section_id)
                        ->with('success', 'Brand deleted successfully');
    }

}
