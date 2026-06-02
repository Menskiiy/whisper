<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');   // твоя ретро-форма
    }

    public function store(): RedirectResponse
    {
        $validated = request()->validate([
            'login'    => 'required|string|max:50|unique:users,login',
            'gender'   => 'required|in:male,female,other',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms'    => 'required|accepted',
        ]);

        $user = User::create([
            'login'           => $validated['login'],
            'name'            => $validated['login'],     // потом можно редактировать отдельно
            'email'           => $validated['email'],
            'gender'          => $validated['gender'],
            'terms_accepted'  => true,
            'password'        => Hash::make($validated['password']),
        ]);

        event(new Registered($user));
        auth()->login($user);

        // ВОТ ЭТО САМОЕ ГЛАВНОЕ — куда кидаем после регистрации
        return redirect()->route('feed');   // ← будем делать ленту
        // или временно можно: return redirect('/profile/' . $user->login);
        // или просто: return redirect('/dashboard');
    }
}