<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     // Tampilkan halaman login
     public function showLoginForm()
     {
         return view('auth.login');
     }
 
     // Proses login
     public function login(Request $request)
     {
         $credentials = $request->validate([
             'email' => ['required', 'email'],
             'password' => ['required'],
         ]);
 
         if (Auth::guard('pengguna')->attempt($credentials)) {
             $request->session()->regenerate();
 
             $request->session()->flash('success', 'Login berhasil! Selamat datang kembali.');
             return redirect()->intended('/dashboard');
         }
 
         return back()->withErrors([
             $request->session()->flash('error', 'Email atau password salah.'),
         ]);
     }
 
     // Proses logout
     public function logout(Request $request)
     {
         Auth::guard('pengguna')->logout();
 
         $request->session()->invalidate();
         $request->session()->regenerateToken();
 
         return redirect('/login');
     }
}
