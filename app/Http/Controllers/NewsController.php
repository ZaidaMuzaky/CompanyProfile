<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News; 

class NewsController extends Controller
{
        public function index()
    {
        $news = News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    public function createOrEdit($id = null)
    {
        $news = $id ? News::findOrFail($id) : new News();
        return view('admin.news.form', compact('news'));
    }

    public function storeOrUpdate(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'konten' => 'required|string',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Cek jika update
    if ($request->id) {
        $news = News::findOrFail($request->id);
        $news->judul = $validated['judul'];
        $news->konten = $validated['konten'];

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('thumbnail', 'public');
            $news->gambar = $gambar;
        }

        $news->save();
    } else {
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('thumbnail', 'public');
        }

        News::create($validated);
    }

    return redirect()->route('admin.news.index')->with('success', 'Berita berhasil disimpan.');
}


    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

}
