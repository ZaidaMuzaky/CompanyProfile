<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class PartsViewController extends Controller
{
     public function index($id)
    {
        // Ambil subkategori berdasarkan ID
        $subcategory = Subcategory::findOrFail($id);

        // Ambil semua parts berdasarkan subkategori
        $parts = Part::where('subcategory_id', $id)->get();

        // Kirim data ke view
        return view('user.parts.index', compact('subcategory', 'parts'));
    }
}
