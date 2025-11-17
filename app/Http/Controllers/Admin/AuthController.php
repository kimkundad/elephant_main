<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Attempt Login
        if (Auth::attempt($request->only('email', 'password'))) {

            // เช็ค role superAdmin หรือ admin
            if (!Auth::user()->hasAnyRole(['superAdmin', 'admin'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'คุณไม่มีสิทธิ์เข้าสู่ระบบหลังบ้าน']);
            }
           // dd(Auth::user());
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
