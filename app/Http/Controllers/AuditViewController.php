<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditViewController extends Controller
{
    public function view($id, Request $request)
    {
        $search = $request->input('search');

        $audit = Audit::with(['uploads' => function ($query) use ($search) {
            if ($search) {
                $query->where('description', 'like', '%' . $search . '%')
                      ->orWhere('upload_date', 'like', '%' . $search . '%');
            }
        }])->findOrFail($id);

        return view('user.audit.index', compact('audit', 'search'));
    }
}
