<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsVisitController extends Controller
{
    public function index()
    {
        $news = News::latest()->get(); // Ambil semua berita
        return view('newsvisit.index', compact('news'));
    }

    public function detail($id)
    {
        $news = News::findOrFail($id); // Ambil berita berdasarkan ID
        return view('newsvisit.detail', compact('news'));
    }
}
