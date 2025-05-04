<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achivements;
use Illuminate\Support\Facades\Storage;

class AchivementsController extends Controller
{
    /**
     * Tampilkan daftar prestasi.
     */
    public function index()
    {
        $achievements = Achivements::all(); // Ambil semua data dari tabel
        return view('admin.achievement.index', compact('achievements'));
    }

    /**
     * Tampilkan form untuk menambahkan prestasi baru.
     */
    public function create()
    {
        return view('admin.achievement.create');
    }

    /**
     * Simpan data prestasi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:51200', // Ubah max file menjadi 50MB
        ]);

        $data = $request->only(['judul', 'deskripsi']);

        // Simpan gambar jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('achievements', 'public');
        }

        Achivements::create($data);

        return redirect()->route('admin.achievement.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit prestasi.
     */
    public function edit($id)
    {
        $achievement = Achivements::findOrFail($id);
        return view('admin.achievement.edit', compact('achievement'));
    }

    /**
     * Update data prestasi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:51200', // Ubah max file menjadi 50MB
        ]);

        $achievement = Achivements::findOrFail($id);
        $data = $request->only(['judul', 'deskripsi']);

        // Update gambar jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($achievement->gambar) {
                $oldImagePath = public_path('storage/' . $achievement->gambar);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                Storage::disk('public')->delete($achievement->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('achievements', 'public');
        }

        $achievement->update($data);

        return redirect()->route('admin.achievement.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    /**
     * Hapus data prestasi.
     */
    public function destroy($id)
    {
        $achievement = Achivements::findOrFail($id);

        // Hapus gambar jika ada
        if ($achievement->gambar) {
            Storage::disk('public')->delete($achievement->gambar);
        }

        $achievement->delete();

        return redirect()->route('admin.achievement.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}
