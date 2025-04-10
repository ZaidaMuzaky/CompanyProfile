<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FilesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $folders = Folder::whereNull('parent_id')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get();
        return view('user.files.index', compact('folders'));
    }

    public function show($id_folder, Request $request)
    {
        $folder = Folder::findOrFail($id_folder);
        $search = $request->query('search');
        $subfolders = $folder->subfolders()
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get();
        $files = $folder->files;
        return view('user.files.show', compact('folder', 'subfolders', 'files'));
    }

    public function manage($id_folder, Request $request)
    {
        $folder = Folder::findOrFail($id_folder);
        $search = $request->query('search');
        $files = $folder->files()->when($search, function ($query, $search) {
            return $query->where('nama_file', 'like', '%' . $search . '%');
        })->get();
        return view('user.files.file', compact('folder', 'files'));
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
        $file->id_user_upload = Auth::id();
        if ($request->file('file')) {
            $filePath = $request->file('file')->store('files', 'public');
            $file->path = $filePath;
        }
        $file->save();

        return redirect()->route('user.files.manage', $request->id_folder)->with('success', 'File uploaded successfully.');
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
        $file->id_user_upload = Auth::id();
        if ($request->file('file')) {
            $filePath = $request->file('file')->store('files', 'public');
            $file->path = $filePath;
        }
        $file->save();

        return redirect()->route('user.files.manage', $request->id_folder)->with('success', 'File updated successfully.');
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return redirect()->route('user.files.manage', $file->id_folder)->with('success', 'File deleted successfully.');
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        return response()->download(storage_path("app/public/{$file->path}"), $file->nama_file);
    }
}
