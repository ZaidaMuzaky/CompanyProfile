<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Submenu;
use App\Models\SubmenuImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuViewController extends Controller
{
    public function show($menuId, $submenuId)
{
    $menu = Menu::findOrFail($menuId);
    $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);

    // Ambil semua file dari storage
    $files = Storage::files("public/submenu-images/{$submenu->id_submenu}");

    // Ambil data dari database
    $dbImages = SubmenuImage::where('submenu_id', $submenu->id_submenu)
    ->get()
    ->keyBy(function ($item) {
        return basename($item->image_path);
    });



    // Gabungkan path dan deskripsi
    $images = collect($files)->map(function ($filePath) use ($dbImages) {
        $filename = basename($filePath);
        return (object)[
            'path' => str_replace('public', 'storage', $filePath),
            'description' => optional($dbImages->get($filename))->description ?? 'Deskripsi belum tersedia'
        ];
    });
    
    return view('user.menu.show', compact('menu', 'submenu', 'images'));
}

    public function view($menuId)
    {
        $menu = Menu::with('submenus')->findOrFail($menuId); // Ambil menu beserta submenunya
        return view('user.menu.view', compact('menu')); // Kirim data ke view
    }
}
