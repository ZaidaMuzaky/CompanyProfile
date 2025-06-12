<?php

namespace App\Http\Controllers;

use App\Models\CnUnit;
use App\Models\CnUnitFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminGisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $cnUnits = CnUnit::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->latest()->get();

        return view('admin.gis.index', compact('cnUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CnUnit::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'CN Unit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unit = CnUnit::findOrFail($id);
        $unit->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'CN Unit berhasil diperbarui.');
    }

    public function updateFile(Request $request, $id)
{
    $request->validate([
        'description' => 'nullable|string|max:255',
    ]);

    $file = CnUnitFile::findOrFail($id);
    $file->update([
        'description' => $request->description,
    ]);

    return redirect()->route('admin.cn-units.addFile', $file->cn_unit_id)
        ->with('success', 'Deskripsi file berhasil diperbarui.');
}


    public function destroy($id)
    {
        $unit = CnUnit::findOrFail($id);
        $unit->delete();

        return redirect()->back()->with('success', 'CN Unit berhasil dihapus.');
    }

    public function addFile($id)
    {
        $unit = CnUnit::findOrFail($id);
        $files = $unit->files()->latest()->get();

        return view('admin.gis.links', compact('unit', 'files'));
    }

    public function storeFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,xlsx,xls',
            'description' => 'nullable|string',
        ]);

        $unit = CnUnit::findOrFail($id);
        $uploadedFile = $request->file('file');

        // ✅ Simpan ke storage/app/public/uploads/cn_files
        $path = $uploadedFile->store('uploads/cn_files', 'public');

        $unit->files()->create([
            'file_path' => $path,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'file_type' => $uploadedFile->getClientOriginalExtension(),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.cn-units.addFile', $id)->with('success', 'File berhasil diunggah.');
    }

    public function deleteFile($id)
    {
        $file = CnUnitFile::findOrFail($id);
        $cnUnitId = $file->cn_unit_id;

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return redirect()->route('admin.cn-units.addFile', $cnUnitId)->with('success', 'File berhasil dihapus.');
    }
}
