<?php

namespace App\Http\Controllers;

use App\Models\PartUnschedule;
use Illuminate\Http\Request;

class PartUnscheduleViewController extends Controller
{
    public function index(Request $request)
{
    $query = PartUnschedule::query();

    if ($request->has('search') && $request->search != '') {
        $query->where('nama_sparepart', 'like', '%' . $request->search . '%')
              ->orWhere('type', 'like', '%' . $request->search . '%');
    }

    $partunschedules = $query->get();

    return view('user.partunschedule.index', compact('partunschedules'));
}

}
