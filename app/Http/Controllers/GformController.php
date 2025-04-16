<?php

namespace App\Http\Controllers;

use App\Models\GoogleForm;
use Illuminate\Http\Request;

class GformController extends Controller
{
    public function editGoogleForm(Request $request)
    {
        $query = GoogleForm::query();

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $googleForms = $query->get()->toArray(); // Convert to array to avoid issues with empty collections
        return view('admin.google-form.edit', compact('googleForms'));
    }

    public function updateGoogleForm(Request $request)
    {
        $request->validate([
            'google_forms' => 'required|array',
            'google_forms.*.title' => 'required|string',
            'google_forms.*.description' => 'nullable|string',
            'google_forms.*.status' => 'required|in:active,inactive',
            'google_forms.*.url' => 'required|url',
        ]);

        // Save new forms
        foreach ($request->google_forms as $form) {
            GoogleForm::create($form);
        }

        return redirect()->route('admin.google-form.edit')->with('success', 'Google Forms added successfully.');
    }

    public function updateSpecificGoogleForm(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'url' => 'required|url',
        ]);

        $form = GoogleForm::findOrFail($id);
        $form->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'url' => $request->url,
        ]);

        return redirect()->route('admin.google-form.edit')->with('success', 'Google Form updated successfully.');
    }

    public function deleteGoogleForm($id)
    {
        $form = GoogleForm::findOrFail($id);
        $form->delete();

        return redirect()->route('admin.google-form.edit')->with('success', 'Google Form deleted successfully.');
    }

    public function userIndex()
    {
        $googleForms = GoogleForm::where('status', 'active')->get();
        return view('user.Gform.index', compact('googleForms'));
    }
}
