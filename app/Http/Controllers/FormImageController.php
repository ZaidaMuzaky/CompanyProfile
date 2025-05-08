<?php

namespace App\Http\Controllers;

use App\Models\FormImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormImageController extends Controller
{
        public function upload(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:20480',
    ]);

    $path = $request->file('image')->store('form-images', 'public');
    $url = asset('storage/' . $path);

    $formImage = FormImage::create([
        'image_path' => $path,
        'image_url' => $url,
        'section' => $request->input('section', null),
        'form_id' => $request->input('form_id', null),
    ]);

    return response()->json([
        'success' => true,
        'url' => $formImage->image_url,
    ]);
}



}
