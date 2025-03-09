<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;

class FoldersController extends Controller
{
    public function index()
    {
        $folders = Folder::whereNull('parent_id')->get(); // Ambil semua folder parent
        return view('admin.folders.index', compact('folders'));
    }

    public function show($id)
    {
        $parentFolder = Folder::findOrFail($id);
        $subfolders = $parentFolder->subfolders; // Ambil subfolder dari folder parent
        return view('admin.folders.show', compact('parentFolder', 'subfolders'));
    }

    public function edit($id)
    {
        $folder = Folder::findOrFail($id);
        return view('admin.folders.edit', compact('folder'));
    }

    public function update(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update data folder
        $folder->nama = $request->nama;

        if ($request->hasFile('icon')) {
            // Hapus ikon lama jika ada
            if ($folder->icon_path) {
                Storage::disk('public')->delete($folder->icon_path);
            }
            // Simpan ikon baru
            $folder->icon_path = $request->file('icon')->store('icons', 'public');
        }

        $folder->save();

        return redirect()->route('admin.folders.index')->with('success', 'Folder updated successfully');
    }

    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return redirect()->route('admin.folders.index')->with('success', 'Folder deleted successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id_folder',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $folder = new Folder();
        $folder->nama = $request->nama;
        $folder->parent_id = $request->parent_id;

        if ($request->hasFile('icon')) {
            $folder->icon_path = $request->file('icon')->store('icons', 'public');
        }

        $folder->save();

        return redirect()->route('admin.folders.index')->with('success', 'Folder created successfully.');
    }
}
