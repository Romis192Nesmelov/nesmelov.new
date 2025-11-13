<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) return redirect(route('admin.tasks'));
        return view('login');
    }

    public function signIn(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect(route('admin.tasks'));
        } else return redirect(route('auth.login'))->withErrors([
            'email' => trans('auth.failed'),'password' => trans('auth.failed')
        ])->withInput();
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }
}
