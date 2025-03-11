<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Update is_online status to true and record last login time
            $user = Auth::user();
            $user->is_online = true;
            $user->last_login_at = now();
            $user->save();

            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        }

        return response()->json(['success' => false, 'message' => 'Username atau password salah!'], 401);
    }

    public function logout(Request $request)
    {
        // Update is_online status to false
        $user = Auth::user();
        if ($user) {
            $user->is_online = false;
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/home');
    }
}
