<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MenuViewController extends Controller
{
    public function show($menuId, $submenuId)
    {
        $menu = Menu::findOrFail($menuId); // Ambil menu berdasarkan ID
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId); // Ambil submenu terkait
        $images = Storage::files("public/submenu-images/{$submenu->id_submenu}"); // Ambil gambar terkait submenu

        return view('user.menu.show', compact('menu', 'submenu', 'images')); // Kirim data ke view
    }

    public function view($menuId)
    {
        $menu = Menu::with('submenus')->findOrFail($menuId); // Ambil menu beserta submenunya
        return view('user.menu.view', compact('menu')); // Kirim data ke view
    }
}
