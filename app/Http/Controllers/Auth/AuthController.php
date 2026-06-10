<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            return match (true) {
                $user->hasRole('admin')   => redirect()->route('admin.dashboard'),
                $user->hasRole('teacher') => redirect()->route('teacher.dashboard'),
                $user->hasRole('student') => redirect()->route('student.dashboard'),
                default                   => redirect('/'),
            };
        }

        return back()->withErrors(['email' => 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
