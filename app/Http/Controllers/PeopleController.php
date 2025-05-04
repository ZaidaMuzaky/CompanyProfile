<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;
use Illuminate\Support\Facades\Storage;

class PeopleController extends Controller
{
    public function index()
    {
        $people = People::all();
            return view('admin.ourpeople.index', compact('people'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
        ]);

        $data = $request->only(['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('people_images', 'public');
        }

        People::create($data);

        return redirect()->route('admin.people.index')->with('success', 'Person added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
        ]);

        $person = People::findOrFail($id);
        $data = $request->only(['title']);

        if ($request->hasFile('image')) {
            if ($person->image) {
                Storage::disk('public')->delete($person->image);
            }
            $data['image'] = $request->file('image')->store('people_images', 'public');
        }

        $person->update($data);

        return redirect()->route('admin.people.index')->with('success', 'Person updated successfully.');
    }

    public function destroy($id)
    {
        $person = People::findOrFail($id);

        if ($person->image) {
            Storage::disk('public')->delete($person->image);
        }

        $person->delete();

        return redirect()->route('admin.people.index')->with('success', 'Person deleted successfully.');
    }
}