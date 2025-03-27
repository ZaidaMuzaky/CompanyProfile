<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class GformController extends Controller
{
    public function editGoogleForm()
    {
        $googleFormLink = config('app.google_form_link', 'https://forms.gle/');
        return view('admin.google-form.edit', compact('googleFormLink'));
    }

    public function updateGoogleForm(Request $request)
    {
        $request->validate([
            'google_form_link' => 'required|url',
        ]);

        // Simpan link ke file konfigurasi atau database
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'GOOGLE_FORM_LINK=' . config('app.google_form_link'),
                'GOOGLE_FORM_LINK=' . $request->google_form_link,
                file_get_contents($path)
            ));
        }

        return redirect()->route('admin.google-form.edit')->with('success', 'Google Form link updated successfully.');
    }
}
