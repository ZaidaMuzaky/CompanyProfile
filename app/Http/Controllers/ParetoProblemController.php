<?php

namespace App\Http\Controllers;

use App\Models\MainMenu;
use App\Models\MenuSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParetoProblemController extends Controller
{
    // Menampilkan semua main menu dengan optional search
    public function index(Request $request)
    {
        $query = MainMenu::query();

        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $mainMenus = $query->paginate(10);

        return view('admin.pareto.index', compact('mainMenus'));
    }

    // Tidak digunakan karena form ada di modal di halaman index
    public function create()
    {
        return redirect()->route('admin.pareto.index');
    }

    // Menyimpan main menu baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        MainMenu::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.pareto.index')->with('success', 'Main menu added successfully');
    }

    // Menampilkan data untuk modal edit (jika diperlukan pakai AJAX)
    public function edit($id)
    {
        $mainMenu = MainMenu::findOrFail($id);
        return response()->json($mainMenu);
    }

    // Memperbarui data main menu
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $mainMenu = MainMenu::findOrFail($id);
        $mainMenu->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.pareto.index')->with('success', 'Main menu updated successfully');
    }

    // Menghapus main menu
    public function destroy($id)
    {
        $mainMenu = MainMenu::findOrFail($id);
        $mainMenu->delete();

        return redirect()->route('admin.pareto.index')->with('success', 'Main menu deleted successfully');
    }
    
    // Menampilkan halaman show beserta data section
    public function show($id)
    {
        // Mengambil MainMenu dengan relasi menuSections
        $mainMenu = MainMenu::with('menuSections')->findOrFail($id);

        return view('admin.pareto.show', compact('mainMenu'));
    }


    // Menyimpan section baru
    public function storeSection(Request $request)
{
    // Validasi input
    $request->validate([
        'nama' => 'required|string|max:255',
        'main_menu_id' => 'required|exists:main_menus,id',
    ]);

    // Membuat section baru
    MenuSection::create([
        'nama' => $request->nama,
        'main_menu_id' => $request->main_menu_id,
    ]);

    // Kembali ke halaman show dengan pesan sukses
    return redirect()->route('admin.pareto.show', $request->main_menu_id)
                     ->with('success', 'Section added successfully');
}


    // Menampilkan form edit section
    public function editSection($id)
    {
        // Mengambil data section yang ingin diedit
        $section = MenuSection::findOrFail($id);
        return response()->json($section);
    }

    // Memperbarui section yang sudah ada
    public function updateSection(Request $request, $id)
    {
        // Mengambil data section yang ingin diupdate
        $section = MenuSection::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Update data section
        $section->update([
            'nama' => $request->nama,
        ]);

        // Kembali ke halaman show dengan pesan sukses
        return redirect()->route('admin.pareto.show', $section->main_menu_id)
                         ->with('success', 'Section updated successfully');
    }

    // Menghapus section
    public function destroySection($id)
    {
        // Mengambil data section yang ingin dihapus
        $section = MenuSection::findOrFail($id);

        // Menghapus section
        $section->delete();

        // Kembali ke halaman show dengan pesan sukses
        return redirect()->route('admin.pareto.show', $section->main_menu_id)
                         ->with('success', 'Section deleted successfully');
    }
}
