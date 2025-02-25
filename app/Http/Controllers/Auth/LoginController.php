<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan view ini sesuai dengan file Anda
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login' => 'required|string', // Field login bisa email atau NIP
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan email atau NIP
        $user = User::where('email', $request->login)
                    ->orWhere('nip', $request->login)
                    ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => 'Email atau NIP tidak ditemukan.',
                ]);
        }

        // Tentukan field login berdasarkan role
        $field = $user->getLoginField();


        // Coba login
        if (Auth::attempt([$field => $request->login, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            // Redirect semua role ke dashboard yang sama
            return redirect()->route('dashboard');
        }

        // Jika password salah
        return redirect()->back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors([
                'password' => 'Password salah.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}