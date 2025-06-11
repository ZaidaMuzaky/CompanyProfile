<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InspectionHeader;
use Illuminate\Support\Facades\Storage;

class InspectionHeaderController extends Controller
{
    public function edit()
    {
        $header = InspectionHeader::first();
        return view('admin.inspection.inspection-header', compact('header'));
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'header_image' => 'image|max:204800', // Maksimal 20MB
        ]);

        $header = InspectionHeader::first() ?? new InspectionHeader();

        if ($request->hasFile('header_image')) {
            if ($header->header_image && Storage::exists($header->header_image)) {
                Storage::delete($header->header_image);
            }

            $path = $request->file('header_image')->store('public/inspection_headers');
            $header->header_image = $path;
        }

        $header->save();

        return back()->with('success', 'Gambar header inspection berhasil diperbarui.');
    }
}
