<?php

namespace App\Http\Controllers;

use App\Models\CnUnit;
use Illuminate\Http\Request;

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
    $links = collect($unit->links)->sortByDesc('created_at');

    // Ubah semua link ke view-only
    $links = $links->map(function ($link) {
        $link->spreadsheet_link = preg_replace('/\/edit(\?.*)?$/', '/view$1', $link->spreadsheet_link);
        return $link;
    });

    return view('user.gis.links', compact('unit', 'links'));
}

    
}

