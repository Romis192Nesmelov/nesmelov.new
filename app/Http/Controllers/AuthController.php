<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) return redirect(route('admin.tasks'));
        return view('login');
    }

    public function signIn(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect(route('admin.tasks'));
        } else return redirect(route('auth.login'),401);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
