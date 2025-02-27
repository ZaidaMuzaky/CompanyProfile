<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // Ambil semua user
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
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type
        ]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
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
