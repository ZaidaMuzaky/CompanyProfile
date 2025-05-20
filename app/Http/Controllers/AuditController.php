<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $subAudits = $query->orderBy('created_at', 'desc')->get();

        return view('admin.audit.index', compact('subAudits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        Audit::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.audit.index')->with('success', 'Sub Menu Audit berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $audit = Audit::findOrFail($id);
        $audit->nama = $request->nama;
        $audit->save();

        return redirect()->route('admin.audit.index')->with('success', 'Sub Menu Audit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $audit = Audit::findOrFail($id);
        $audit->delete();

        return redirect()->route('admin.audit.index')->with('success', 'Sub Menu Audit berhasil dihapus.');
    }

    public function show($id)
    {
        $audit = Audit::findOrFail($id);
        // Nanti di sini bisa ditampilkan file audit atau halaman upload
        return view('admin.audit.detail', compact('audit'));
    }
}
