<?php

namespace App\Http\Controllers;

use App\Models\MenuBrand;
use Illuminate\Http\Request;

class ParetoViewController extends Controller
{
    public function index($menuBrand)
    {
        $brand = MenuBrand::with(['files', 'section.mainMenu'])->findOrFail($menuBrand);
    
        return view('user.pareto.index', compact('brand'));
    }
}    
