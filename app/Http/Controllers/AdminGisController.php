<?php

namespace App\Http\Controllers;

use App\Models\CnUnit;
use App\Models\CnUnitLink;
use Illuminate\Http\Request;

class AdminGisController extends Controller
{
    // Tampilkan daftar CN Unit
    public function index(Request $request)
    {
        $search = $request->query('search');
        $cnUnits = CnUnit::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->latest()->get();

        return view('admin.gis.index', compact('cnUnits'));
    }

    // Simpan CN Unit baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CnUnit::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'CN Unit berhasil ditambahkan.');
    }

    // Update CN Unit
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unit = CnUnit::findOrFail($id);
        $unit->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'CN Unit berhasil diperbarui.');
    }

    // Hapus CN Unit
    public function destroy($id)
    {
        $unit = CnUnit::findOrFail($id);
        $unit->delete();

        return redirect()->back()->with('success', 'CN Unit berhasil dihapus.');
    }

    // Tampilkan form untuk menambah link & deskripsi untuk CN Unit tertentu
    public function addLink($id)
    {
        $unit = CnUnit::findOrFail($id);
        $links = $unit->links()->latest()->get();

        return view('admin.gis.links', compact('unit', 'links'));
    }

    // Simpan link & deskripsi ke CN Unit
    public function storeLink(Request $request, $id)
    {
        $request->validate([
            'spreadsheet_link' => 'required|url',
            'description' => 'nullable|string',
        ]);

        $unit = CnUnit::findOrFail($id);

        $unit->links()->create([
            'spreadsheet_link' => $request->spreadsheet_link,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.cn-units.addLink', $id)->with('success', 'Link berhasil ditambahkan.');
    }

    // Hapus link dari CN Unit
    public function deleteLink($id)
    {
        $link = CnUnitLink::findOrFail($id);
        $cnUnitId = $link->cn_unit_id;
        $link->delete();

        return redirect()->route('admin.cn-units.addLink', $cnUnitId)->with('success', 'Link berhasil dihapus.');
    }

    public function updateLink(Request $request, $id)
{
    $request->validate([
        'spreadsheet_link' => 'required|url',
        'description' => 'nullable|string',
    ]);

    $link = CnUnitLink::findOrFail($id);
    $link->update([
        'spreadsheet_link' => $request->spreadsheet_link,
        'description' => $request->description,
    ]);

    return redirect()->route('admin.cn-units.addLink', $link->cn_unit_id)->with('success', 'Link berhasil diperbarui.');
}

}
