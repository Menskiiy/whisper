<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => ['required', 'string', 'max:50', 'unique:users,login', 'regex:/^[a-zA-Z0-9_]+$/'],
            'gender'   => ['nullable', 'in:male,female,other'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'terms'    => ['required', 'accepted'],
        ], [
            'login.regex'    => 'Логин может содержать только латинские буквы, цифры и _',
            'login.unique'   => 'Этот логин уже занят.',
            'email.unique'   => 'Этот email уже зарегистрирован.',
            'password.min'   => 'Пароль должен быть не менее 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'terms.accepted' => 'Необходимо принять правила сервиса.',
            'terms.required' => 'Необходимо принять правила сервиса.',
        ]);

        $user = User::create([
            'login'          => $request->login,
            'name'           => $request->login,
            'email'          => $request->email,
            'gender'         => $request->gender ?? 'other',
            'terms_accepted' => true,
            'password'       => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect('/');
    }
}
