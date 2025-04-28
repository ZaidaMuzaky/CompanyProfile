<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'files.*' => 'required|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation|max:102400', // Updated max size to 100 MB
            'id_folder' => 'required|exists:folders,id_folder',
        ]);

        foreach ($request->file('files') as $file) {
            $newFile = new File();
            $newFile->id_folder = $request->id_folder;
            $newFile->nama_file = $file->getClientOriginalName();
            $newFile->file_type = $file->getClientOriginalExtension();
            $newFile->id_user_upload = Auth::id();
            $filePath = $file->store('files', 'public');
            $newFile->path = $filePath;
            $newFile->save();
        }

        return redirect()->route('user.files.manage', $request->id_folder)->with('success', 'Files uploaded successfully.');
    }

    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);

        $request->validate([
            'files.*' => 'required|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation|max:102400', // Updated max size to 100 MB
            'id_folder' => 'required|exists:folders,id_folder',
        ]);

        $file->id_folder = $request->id_folder;
        $file->nama_file = $request->file('files')[0]->getClientOriginalName(); // Handle single file
        $file->file_type = $request->file('files')[0]->getClientOriginalExtension();
        $file->id_user_upload = Auth::id();

        if ($request->file('files')) {
            $filePath = $request->file('files')[0]->store('files', 'public'); // Store the file
            $file->path = $filePath;
        }

        $file->save();

        return redirect()->route('user.files.manage', $request->id_folder)->with('success', 'File updated successfully.');
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);

    // Delete the file from storage
    Storage::delete("public/{$file->path}");

    // Now delete the file record
    $file->delete();

    return redirect()->route('user.files.manage', $file->id_folder)->with('success', 'File deleted successfully.');
    }
    

    public function download($id)
    {
        $file = File::findOrFail($id);
        return response()->download(storage_path("app/public/{$file->path}"), $file->nama_file);
    }
}
