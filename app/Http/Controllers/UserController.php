<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('username', 'like', '%' . $search . '%');
        })->get();

        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8', // Bisa kosong jika tidak ingin mengubah password
            'role' => 'required|string',
        ]);

        // Update data pengguna
        $user->username = $request->username;
        if ($request->password) {
            $user->password = bcrypt($request->password); // Enkripsi jika ada input password baru
        }
        $user->type = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->type = $request->role;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }
}
