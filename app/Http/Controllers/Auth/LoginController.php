<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // tampilkan halaman login
    public function index()
    {
        return view('auth.login');
    }

    // proses login
    public function login(Request $request)
    {
        // validasi
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // cek user berdasarkan email
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan'
            ])->withInput();
        }

        // cek password
        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah'
            ])->withInput();
        }

        // login
        Auth::login($user);

        // redirect berdasarkan role
        if ($user->role === 'owner') {
            return redirect()->route('dashboard.owner');
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        }

        return redirect()->route('dashboard');
    }

    // logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}