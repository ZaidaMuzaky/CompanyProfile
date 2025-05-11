<?php

namespace App\Http\Controllers;

use App\Models\MenuFile;
use App\Models\MenuBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuFileController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil parameter brand dari query string
        $brandId = $request->query('brand');
        $brand = MenuBrand::findOrFail($brandId);
        $section = $brand->section;

        // Menyiapkan query untuk mendapatkan file terkait brand
        $query = $brand->files();

        // Menambahkan filter pencarian jika ada
        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Mengambil file berdasarkan query yang sudah difilter
        $files = $query->latest()->get();

        return view('admin.pareto.main', compact('files', 'brand', 'section'));

    }

    public function store(Request $request, MenuBrand $menuBrand)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:file,image',
            'file' => 'required|file|max:10240', // Maksimal 10MB
        ]);

        // Simpan file di direktori public/uploads/pareto
        $path = $request->file('file')->store('uploads/pareto', 'public');

        // Pastikan menu_brand_id disertakan saat membuat file baru
        $menuBrand->files()->create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'tipe' => $validated['tipe'],
            'path' => $path,
            'menu_brand_id' => $menuBrand->id, // Menyertakan menu_brand_id
        ]);

        // Mengarahkan kembali ke halaman daftar file dengan pesan sukses
        return redirect()->route('admin.menuFiles.index', ['brand' => $menuBrand->id])
                         ->with('success', 'File berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Menemukan file yang ingin diperbarui
        $file = MenuFile::findOrFail($id);

        // Validasi input dari form
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:file,image',
            'file' => 'nullable|file', // File optional
        ]);

        // Jika ada file baru, simpan file dan update path-nya
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads/pareto', 'public');
            $file->path = $path;
        }

        // Update data lainnya
        $file->judul = $validated['judul'];
        $file->deskripsi = $validated['deskripsi'];
        $file->tipe = $validated['tipe'];
        $file->save();

        // Mengarahkan kembali ke halaman daftar file dengan pesan sukses
        return redirect()->route('admin.menuFiles.index', ['brand' => $file->menu_brand_id])
                         ->with('success', 'File berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Menemukan file yang ingin dihapus
        $file = MenuFile::findOrFail($id);
        $brandId = $file->menu_brand_id;

        // Opsional: Hapus file dari storage (misalnya, jika tidak diperlukan lagi)
        // Storage::disk('public')->delete($file->path);

        // Hapus file dari database
        $file->delete();

        // Mengarahkan kembali ke halaman daftar file dengan pesan sukses
        return redirect()->route('admin.menuFiles.index', ['brand' => $brandId])
                         ->with('success', 'File berhasil dihapus.');
    }
}
