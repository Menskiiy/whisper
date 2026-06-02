<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Пробуем найти по полю login ИЛИ по email
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';

        if (Auth::attempt([$field => $request->login, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/'); // или route('home')
        }

        throw ValidationException::withMessages([
            'login' => 'Неверный логин или пароль.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}