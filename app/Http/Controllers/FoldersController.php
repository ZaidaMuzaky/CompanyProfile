<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;

class FoldersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $folders = Folder::whereNull('parent_id')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get();

        return view('admin.folders.index', compact('folders'));
    }

    public function show(Request $request, $id)
    {
        $search = $request->input('search');

        $parentFolder = Folder::findOrFail($id);
        $subfolders = $parentFolder->subfolders()
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get();

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
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        // Update data folder
        $folder->nama = $request->nama;

        if ($request->hasFile('icon')) {
            // Hapus ikon lama jika ada di public
            if ($folder->icon_path) {
                $iconPath = public_path('storage/' . $folder->icon_path);
                if (file_exists($iconPath)) {
                    unlink($iconPath);
                }
                Storage::disk('public')->delete($folder->icon_path);
            }
            // Simpan ikon baru
            $folder->icon_path = $request->file('icon')->store('icons', 'public');
        }

        $folder->save();

        if ($folder->parent_id) {
            return redirect()->route('admin.folders.show', $folder->parent_id)->with('success', 'Unit updated successfully');
        } else {
            return redirect()->route('admin.folders.index')->with('success', 'Section updated successfully');
        }
    }

    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $parentId = $folder->parent_id;

        // Hapus ikon jika ada di public
        if ($folder->icon_path) {
            $iconPath = public_path('storage/' . $folder->icon_path);
            if (file_exists($iconPath)) {
                unlink($iconPath);
            }
            Storage::disk('public')->delete($folder->icon_path);
        }

        $folder->delete();

        if ($parentId) {
            return redirect()->route('admin.folders.show', $parentId)->with('success', 'Unit deleted successfully');
        } else {
            return redirect()->route('admin.folders.index')->with('success', 'Section deleted successfully');
        }
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

        if ($folder->parent_id) {
            return redirect()->route('admin.folders.show', $folder->parent_id)->with('success', 'Unit created successfully.');
        } else {
            return redirect()->route('admin.folders.index')->with('success', 'Section created successfully.');
        }
    }
}
