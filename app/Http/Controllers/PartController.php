<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Category;
use App\Models\Subcategory;
use App\Imports\PartsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(10);

        return view('admin.parts.index', compact('categories'));
    }

    // Menampilkan halaman untuk menambah part baru
    public function create()
    {
        return view('admin.parts.index');
    }

    // Menyimpan part baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.parts.index')->with('success', 'Part added successfully');
    }

    // Menampilkan halaman untuk mengedit part
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Memperbarui part
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.parts.index')->with('success', 'Part updated successfully');
    }

    // Menghapus part
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.parts.index')->with('success', 'Part deleted successfully');
    }

    // Menampilkan subkategori berdasarkan kategori
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $subcategories = $category->subcategories; // Relasi dengan subkategori
        return view('admin.parts.show', compact('category', 'subcategories'));
    }

    // search subkategori
    public function listByCategory(Request $request, $category_id)
    {
        $category = Category::findOrFail($category_id);

        $query = SubCategory::where('category_id', $category_id);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $subcategories = $query->get();

        return view('admin.parts.show', compact('category', 'subcategories'));
    }


    // Menyimpan subkategori baru
    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Validasi ID kategori yang ada
        ]);

        // Simpan subkategori baru
        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.parts.show', $request->category_id)
            ->with('success', 'Sub-Kategori berhasil ditambahkan');
    }

    // Menampilkan halaman untuk mengedit subkategori
    public function editSubcategory($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        return response()->json($subcategory);
    }

    // Memperbarui subkategori
    public function updateSubcategory(Request $request, $category_id, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.parts.show', $category_id)
            ->with('success', 'Sub-Kategori berhasil diperbarui');
    }


    // Menghapus subkategori
    public function destroySubcategory($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $categoryId = $subcategory->category_id;
        $subcategory->delete();

        return redirect()->route('admin.parts.show', $categoryId)
            ->with('success', 'Sub-Kategori berhasil dihapus');
    }


        public function partList(Request $request, $sub_id)
    {
        $subcategory = SubCategory::findOrFail($sub_id);
        $query = Part::where('subcategory_id', $sub_id); // <-- diperbaiki di sini

        if ($request->has('search')) {
            $query->where('nama_sparepart', 'like', '%' . $request->search . '%');
        }

        $parts = $query->get();

        return view('admin.parts.main', compact('subcategory', 'parts'));
    }

   public function storePart(Request $request)
        {
            $request->validate([
                'subcategory_id' => 'required|exists:subcategories,id',
                'nama_sparepart' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                'qty_stock' => 'nullable|integer',
                'status' => 'required|in:open,close',
            ]);

            Log::info('Request input:', $request->all());

            Part::create([
                'subcategory_id' => $request->subcategory_id,
                'nama_sparepart' => $request->nama_sparepart,
                'type' => $request->type,
                'qty_stock' => $request->qty_stock,
                'status' => $request->status,
            ]);

            return back()->with('success', 'Part berhasil ditambahkan');
        }


    public function updatePart(Request $request, $id)
{
    $validated = $request->validate([
        'nama_sparepart' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'qty_stock' => 'required|integer|min:0',
        'status' => 'required|in:open,close',
    ]);

    $part = Part::findOrFail($id);
    $part->update($validated);

    return redirect()->back()->with('success', 'Part berhasil diperbarui!');
}


        public function destroyPart($id)
        {
            $part = Part::findOrFail($id);
            $part->delete();

            return back()->with('success', 'Part berhasil dihapus');
        }

        public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Mengimpor data parts dari file Excel
        Excel::import(new PartsImport($request->subcategory_id), $request->file('file'));

        return redirect()->route('admin.parts.main', $request->subcategory_id)
                         ->with('success', 'Parts berhasil diimpor!');
    }
}
