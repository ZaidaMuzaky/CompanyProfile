<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BacklogHeader;
use Illuminate\Support\Facades\Storage;

class BacklogHeaderController extends Controller
{
    // Tampilkan halaman edit header image
    public function edit()
    {
        $header = BacklogHeader::first(); // atau berdasarkan ID jika banyak header
        return view('admin.backlog.backlog-header', compact('header'));
    }

    // Simpan perubahan gambar
    public function updateImage(Request $request)
    {
        $request->validate([
            'header_image' => 'image|max:2048',
        ]);

        $header = BacklogHeader::first(); // atau berdasarkan ID
        if (!$header) {
            $header = new BacklogHeader(); // buat baru jika belum ada
        }

        if ($request->hasFile('header_image')) {
            // Hapus gambar lama kalau ada
            if ($header->header_image && Storage::exists($header->header_image)) {
                Storage::delete($header->header_image);
            }

            $path = $request->file('header_image')->store('public/backlog_headers');
            $header->header_image = $path;
        }

        $header->save();

        return back()->with('success', 'Gambar header berhasil diperbarui.');
    }
}
