<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Submenu;
use App\Models\SubmenuImage;
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
        $menu = Menu::findOrFail($menuId);
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);
        
        // Ambil gambar dari database, bukan dari storage langsung
        $images = $submenu->images; // relasi hasMany dari submenu ke submenu_images
    
        return view('admin.menus.show', compact('menu', 'submenu', 'images'));
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

        // Hapus semua file di public/storage/menu-images
        $publicPath = public_path("storage/menu-images/{$menu->id_menu}");
        if (is_dir($publicPath)) {
            array_map('unlink', glob("{$publicPath}/*.*"));
            rmdir($publicPath);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    public function storeImage(Request $request, $menuId, $submenuId)
    {
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);
    
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:51200',
            'description' => 'nullable|string',
        ]);
    
        $path = $request->file('image')->store("submenu-images/{$submenu->id_submenu}", 'public');
    
        // Simpan ke database
        SubmenuImage::create([
            'submenu_id' => $submenu->id_submenu,
            'image_path' => $path,
            'description' => $request->input('description'),
        ]);
    
        return redirect()->back()->with('success', 'Gambar berhasil ditambahkan.');
    }
    

    public function destroyImage($menuId, $submenuId, $imageId)
{
    $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);

    $image = SubmenuImage::where('submenu_id', $submenu->id_submenu)->findOrFail($imageId);

    // Hapus file dari storage
    if (Storage::disk('public')->exists($image->image_path)) {
        Storage::disk('public')->delete($image->image_path);
    }

    // Hapus dari database
    $image->delete();

    return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
}


public function updateImage(Request $request, $menuId, $submenuId, $imageId)
{
    $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);

    $image = SubmenuImage::where('submenu_id', $submenu->id_submenu)->findOrFail($imageId);

    $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:51200',
        'description' => 'nullable|string',
    ]);

    // Jika user upload gambar baru
    if ($request->hasFile('image')) {
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $newPath = $request->file('image')->store("submenu-images/{$submenu->id_submenu}", 'public');
        $image->image_path = $newPath;
    }

    // Update deskripsi
    $image->description = $request->input('description');
    $image->save();

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
