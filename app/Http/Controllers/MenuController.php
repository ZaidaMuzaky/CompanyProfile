<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $menus = Menu::when($search, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        })->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Menu::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function show($menuId, $submenuId)
    {
        $menu = Menu::findOrFail($menuId); // Ambil menu berdasarkan ID
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId); // Ambil submenu terkait
        $images = Storage::files("public/submenu-images/{$submenu->id_submenu}"); // Ambil gambar terkait submenu

        return view('admin.menus.show', compact('menu', 'submenu', 'images')); // Kirim data ke view
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus semua gambar terkait menu di storage
        Storage::deleteDirectory("public/menu-images/{$menu->id_menu}");

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    public function storeImage(Request $request, $menuId, $submenuId)
    {
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:51200', // Validasi file gambar
        ]);

        // Simpan gambar ke folder storage
        $path = $request->file('image')->store("submenu-images/{$submenu->id_submenu}", 'public');

        return redirect()->back()->with('success', 'Gambar berhasil ditambahkan.');
    }

    public function destroyImage($menuId, $submenuId, $imageName)
    {
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);

        // Path file di storage
        $filePath = "submenu-images/{$submenu->id_submenu}/{$imageName}";

        // Hapus file dari storage
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Hapus file dari public/storage (link simbolik)
        $publicPath = public_path("storage/{$filePath}");
        if (file_exists($publicPath)) {
            unlink($publicPath);
        }

        return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
    }

    public function updateImage(Request $request, $menuId, $submenuId, $imageName)
    {
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:51200', // Validasi file gambar
        ]);

        // Hapus gambar lama
        Storage::disk('public')->delete("submenu-images/{$submenu->id_submenu}/{$imageName}");

        // Simpan gambar baru
        $path = $request->file('image')->store("submenu-images/{$submenu->id_submenu}", 'public');

        return redirect()->back()->with('success', 'Gambar berhasil diperbarui.');
    }

    public function sub($menuId)
    {
        $menu = Menu::findOrFail($menuId); // Ambil menu berdasarkan ID
        $submenus = $menu->submenus; // Ambil semua submenu terkait dengan menu

        return view('admin.menus.sub', compact('menu', 'submenus')); // Kirim data ke view
    }

    public function storeSubmenu(Request $request, $menuId)
    {
        $menu = Menu::findOrFail($menuId); // Pastikan menu dengan ID yang diberikan ada

        $request->validate([
            'nama' => 'required|string|max:255', // Validasi nama submenu
        ]);

        $menu->submenus()->create([
            'nama' => $request->nama, // Simpan nama submenu
        ]);

        return redirect()->route('admin.menus.sub', $menuId)->with('success', 'Submenu berhasil ditambahkan.');
    }

    public function destroySubmenu($menuId, $submenuId)
    {
        $menu = Menu::findOrFail($menuId); // Pastikan menu dengan ID yang diberikan ada
        $submenu = $menu->submenus()->findOrFail($submenuId); // Cari submenu terkait dengan menu

        $submenu->delete(); // Hapus submenu

        return redirect()->route('admin.menus.sub', $menuId)->with('success', 'Submenu berhasil dihapus.');
    }

    public function updateSubmenu(Request $request, $menuId, $submenuId)
    {
        $menu = Menu::findOrFail($menuId); // Pastikan menu dengan ID yang diberikan ada
        $submenu = $menu->submenus()->findOrFail($submenuId); // Cari submenu terkait dengan menu

        $request->validate([
            'nama' => 'required|string|max:255', // Validasi nama submenu
        ]);

        $submenu->update([
            'nama' => $request->nama, // Perbarui nama submenu
        ]);

        return redirect()->route('admin.menus.sub', $menuId)->with('success', 'Submenu berhasil diperbarui.');
    }
}
