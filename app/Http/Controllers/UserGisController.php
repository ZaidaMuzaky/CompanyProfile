<?php

namespace App\Http\Controllers;

use App\Models\CnUnit;
use App\Models\CnUnitFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserGisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $cnUnits = CnUnit::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->latest()->get();

        return view('user.gis.index', compact('cnUnits'));
    }

   
    public function showLinks($id)
    {
        $unit = CnUnit::findOrFail($id);
        $files = CnUnitFile::where('cn_unit_id', $id)->latest()->get();

        return view('user.gis.links', compact('unit', 'files'));
    }

    public function downloadFile($id)
{
    $file = CnUnitFile::findOrFail($id);

    // pastikan ini valid
    if (!Storage::disk('public')->exists($file->file_path)) {
        abort(404, 'File tidak ditemukan.');
    }

    return Storage::disk('public')->download($file->file_path, $file->file_name);
}


    
}

