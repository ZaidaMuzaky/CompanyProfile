<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    /**
     * Tampilkan daftar komunitas.
     */
    public function index()
    {
        $communities = Community::all(); // Ambil semua data dari tabel
        return view('admin.community.index', compact('communities'));
    }

    /**
     * Simpan data komunitas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200', // Ubah max file menjadi 50MB
        ]);

        $data = $request->only(['title']);

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('community_images', 'public');
        }

        Community::create($data);

        // Tambahkan pesan sukses ke session
        return redirect()->route('admin.community.index')->with('success', 'Community item added successfully.');
    }

    /**
     * Update data komunitas.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200', // Ubah max file menjadi 50MB
        ]);

        $community = Community::findOrFail($id);
        $data = $request->only(['title']);

        // Update gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($community->image) {
                $oldImagePath = public_path('storage/' . $community->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                Storage::disk('public')->delete($community->image);
            }
            $data['image'] = $request->file('image')->store('community_images', 'public');
        }

        $community->update($data);

        // Tambahkan pesan sukses ke session
        return redirect()->route('admin.community.index')->with('success', 'Community item updated successfully.');
    }

    /**
     * Hapus data komunitas.
     */
    public function destroy($id)
    {
        $community = Community::findOrFail($id);

        // Hapus gambar jika ada
        if ($community->image) {
            Storage::disk('public')->delete($community->image);
        }

        $community->delete();

        // Tambahkan pesan sukses ke session
        return redirect()->route('admin.community.index')->with('success', 'Community item deleted successfully.');
    }
}