<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FilesController extends Controller
{
    public function index()
    {
        $folders = Folder::all(); // Ambil semua folder
        return view('user.files.index', compact('folders'));
    }

    public function show($id_folder, Request $request)
    {
        $folder = Folder::findOrFail($id_folder);
        $query = File::with('user')->where('id_folder', $id_folder);

        if ($request->has('search')) {
            $query->where('nama_file', 'like', '%' . $request->search . '%');
        }

        $files = $query->get();
        return view('user.files.show', compact('folder', 'files'));
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
        $file->id_user_upload = Auth::id(); // Simpan id_user_upload dengan ID pengguna yang sedang login
        if ($request->file('file')) {
            $filePath = $request->file('file')->store('files', 'public');
            $file->path = $filePath;
        }
        $file->save();

        return redirect()->route('user.files.show', $request->id_folder)->with('success', 'File uploaded successfully.');
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
        $file->id_user_upload = Auth::id(); // Simpan id_user_upload dengan ID pengguna yang sedang login
        if ($request->file('file')) {
            $filePath = $request->file('file')->store('files', 'public');
            $file->path = $filePath;
        }
        $file->save();

        return redirect()->route('user.files.show', $request->id_folder)->with('success', 'File updated successfully.');
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return redirect()->route('user.files.show', $file->id_folder)->with('success', 'File deleted successfully.');
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        return response()->download(storage_path("app/public/{$file->path}"), $file->nama_file);
    }
}
