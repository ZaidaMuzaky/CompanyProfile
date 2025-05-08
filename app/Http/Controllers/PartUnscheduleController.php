<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PartUnschedule; // Pastikan model diimpor dengan benar
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PartUnscheduleImport;

class PartUnscheduleController extends Controller
{
     public function index(Request $request)
    {
        $search = $request->query('search');
        $partunschedules = PartUnschedule::where('nama_sparepart', 'like', '%' . $search . '%')
                                          ->orWhere('type', 'like', '%' . $search . '%')
                                          ->orWhere('model', 'like', '%' . $search . '%')
                                          ->paginate(10);

        return view('admin.partunschedule.index', compact('partunschedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'type' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'no_orderan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        PartUnschedule::create($validated);

        return redirect()->route('admin.partunschedule.index')->with('success', 'Part Unschedule has been added.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'type' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'no_orderan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $partunschedule = PartUnschedule::findOrFail($id);
        $partunschedule->update($validated);

        return redirect()->route('admin.partunschedule.index')->with('success', 'Part Unschedule has been updated.');
    }

    public function destroy($id)
    {
        $partunschedule = PartUnschedule::findOrFail($id);
        $partunschedule->delete();

        return redirect()->route('admin.partunschedule.index')->with('success', 'Part Unschedule has been deleted.');
    }
       public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Menggunakan Maatwebsite Excel untuk import
        Excel::import(new PartUnscheduleImport, $request->file('file'));

        return back()->with('success', 'Data Part Unschedule berhasil diimport!');
    }
}
