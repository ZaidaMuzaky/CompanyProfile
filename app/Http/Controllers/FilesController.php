<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;

class FilesController extends Controller
{
    public function index()
    {
        $folders = Folder::all(); // Ambil semua folder
        $files = []; // Inisialisasi $files sebagai array kosong
        return view('user.files.index', compact('folders', 'files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
            'id_folder' => 'required|exists:folders,id_folder',
        ]);

        $file = new File();
        $file->id_folder = $request->id_folder;
        $file->nama_file = $request->file('file')->getClientOriginalName();
        if ($request->file('file')) {
            $filePath = $request->file('file')->store('files', 'public');
            $file->path = $filePath;
        }
        $file->save();

        return redirect()->route('user.files')->with('success', 'File uploaded successfully.');
    }

    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);

        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
            'id_folder' => 'required|exists:folders,id_folder',
        ]);

        $file->id_folder = $request->id_folder;
        $file->nama_file = $request->file('file')->getClientOriginalName();
        if ($request->file('file')) {
            $filePath = $request->file('file')->store('files', 'public');
            $file->path = $filePath;
        }
        $file->save();

        return redirect()->route('user.files')->with('success', 'File updated successfully.');
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return redirect()->route('user.files')->with('success', 'File deleted successfully.');
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        return response()->download(storage_path("app/public/{$file->path}"), $file->nama_file);
    }
}
