<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsViewController extends Controller
{
    public function index()
    {
        $news = News::latest()->get(); // Ambil semua berita
        return view('user.newsview.index', compact('news'));
    }

    public function detail($id)
    {
        $news = News::findOrFail($id); // Ambil berita berdasarkan ID
        return view('user.newsview.detail', compact('news'));
    }
}
