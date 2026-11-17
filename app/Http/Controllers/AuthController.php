<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   // Hiển thị form đăng nhập
    public function showLogin() {
        return view('auth.login'); // Bạn cần tạo file resources/views/auth/login.blade.php
    }

    // Xử lý đăng nhập
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Chuyển hướng dựa vào role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/schedules');
            }
            return redirect()->intended('/my-schedule');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ]);
    }

    // Đăng xuất
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
