<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Submenu;
use App\Models\SubmenuImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuViewController extends Controller
{
    public function view($menuId)
    {
        $menu = Menu::findOrFail($menuId);

        return view('user.menu.view', compact('menu'));
    }

    // Menampilkan file dan deskripsi untuk submenu tertentu
    public function show($menuId, $submenuId)
    {
        $menu = Menu::findOrFail($menuId);
        $submenu = Submenu::where('menu_id', $menuId)->findOrFail($submenuId);

        // Ambil semua file dari storage
        $files = Storage::files("public/submenu-files/{$submenu->id_submenu}");

        // Ambil data deskripsi dari database
        $fileDescriptions = SubmenuImage::where('submenu_id', $submenu->id_submenu)
            ->get()
            ->keyBy(function ($item) {
                return basename($item->image_path);
            });

        // Gabungkan path dan deskripsi
        $filesData = collect($files)->map(function ($filePath) use ($fileDescriptions) {
            $filename = basename($filePath);
            return (object)[
                'path' => str_replace('public', 'storage', $filePath),
                'name' => $filename,
                'description' => optional($fileDescriptions->get($filename))->description ?? 'Deskripsi belum tersedia',
            ];
        });

        return view('user.menu.show', compact('menu', 'submenu', 'filesData'));
    }
    
}
