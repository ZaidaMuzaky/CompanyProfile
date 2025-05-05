<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

            if ($user->type === 'admin') {
            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        } else {
            return response()->json(['success' => true, 'redirect' => route('user.newsview.index')]);
        }
        }

        return response()->json(['success' => false, 'message' => 'Username atau password salah!'], 401);
    }

    public function apiLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Username atau password salah!'], 401);
        }

        // Update status online dan login terakhir
        $user->is_online = true;
        $user->last_login_at = now();
        $user->save();

        // Generate token (pakai Sanctum)
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'username' => $user->username,
                'role' => $user->type, // 👈 masukkan role ke dalam user
            ],
        ]);

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

        return redirect('/');
    }
}
