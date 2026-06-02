<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'status'    => 'nullable|string|max:100',
            'bio'       => 'nullable|string|max:160',
            'birthday'  => 'nullable|date|before:today',
            'location'  => 'nullable|string|max:100',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar !== 'default-avatar.png') {
                Storage::delete('public/avatars/' . $user->avatar);
            }
            $file = $request->file('avatar');
            $filename = $user->id . '_' . time() . '.' . $file->extension();
            $file->storeAs('public/avatars', $filename);
            $validated['avatar'] = $filename;
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('status', 'Профиль обновлён!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Удаляем аватарку при удалении аккаунта
        if ($user->avatar && $user->avatar !== 'default-avatar.png') {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}