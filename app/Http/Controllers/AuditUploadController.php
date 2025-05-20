<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditUploadController extends Controller
{
    public function index(Request $request, $audit_id)
    {
        $search = $request->input('search');
    
        $audit = Audit::with(['uploads' => function ($query) use ($search) {
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                      ->orWhere('upload_date', 'like', '%' . $search . '%');
                });
            }
            $query->orderBy('upload_date', 'desc');
        }])->findOrFail($audit_id);
    
        return view('admin.audit.detail', compact('audit', 'search'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'audit_id' => 'required|exists:audits,id',
            'image' => 'required|image|max:2048',
            'description' => 'required|string',
            'upload_date' => 'required|date',
        ]);

        $path = $request->file('image')->store('audit_uploads', 'public');

        AuditUpload::create([
            'audit_id' => $request->audit_id,
            'image_path' => $path,
            'description' => $request->description,
            'upload_date' => $request->upload_date,
        ]);

        return back()->with('success', 'Upload berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'description' => 'required|string',
        'upload_date' => 'required|date',
        'image' => 'nullable|image|max:2048',
    ]);

    $upload = AuditUpload::findOrFail($id);

    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if (Storage::disk('public')->exists($upload->image_path)) {
            Storage::disk('public')->delete($upload->image_path);
        }
        // Simpan gambar baru
        $path = $request->file('image')->store('audit_uploads', 'public');
        $upload->image_path = $path;
    }

    $upload->description = $request->description;
    $upload->upload_date = $request->upload_date;
    $upload->save();

    return back()->with('success', 'Upload berhasil diperbarui.');
}


    public function destroy($id)
    {
        $upload = AuditUpload::findOrFail($id);

        if (Storage::disk('public')->exists($upload->image_path)) {
            Storage::disk('public')->delete($upload->image_path);
        }

        $upload->delete();

        return back()->with('success', 'Upload berhasil dihapus.');
    }
}
