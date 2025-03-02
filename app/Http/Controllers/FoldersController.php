<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;

class FoldersController extends Controller
{
    public function index()
    {
        $folders = Folder::all(); // Ambil semua folder
        return view('admin.folders.index', compact('folders'));
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
        ]);

        // Update data folder
        $folder->nama = $request->nama;
        $folder->save();

        return redirect()->back()->with('success', 'Folder updated successfully');
    }

    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return redirect()->route('admin.folders')->with('success', 'Folder deleted successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $folder = new Folder();
        $folder->nama = $request->nama;
        $folder->save();

        return redirect()->route('admin.folders')->with('success', 'Folder created successfully.');
    }
}
