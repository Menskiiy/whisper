<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller {
    public function show() { return view('profile'); }
    public function edit() { return view('profile.edit'); }

    public function update(Request $request) {
        $user = Auth::user();
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'status'       => 'nullable|string|max:100',
            'bio'          => 'nullable|string|max:300',
            'birthday'     => 'nullable|date|before:today',
            'location'     => 'nullable|string|max:100',
            'website'      => 'nullable|url|max:200',
            'vk'           => 'nullable|string|max:100',
            'telegram'     => 'nullable|string|max:100',
            'instagram'    => 'nullable|string|max:100',
            'accent_color' => 'nullable|string|max:7',
            'is_private'   => 'boolean',
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'banner'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar !== 'default-avatar.png') Storage::disk('public')->delete('avatars/'.$user->avatar);
            $f = $request->file('avatar');
            $fn = $user->id.'_av_'.time().'.'.$f->extension();
            $f->storeAs('avatars', $fn, 'public');
            $validated['avatar'] = $fn;
        }
        if ($request->hasFile('banner')) {
            if ($user->banner) Storage::disk('public')->delete('banners/'.$user->banner);
            $f = $request->file('banner');
            $fn = $user->id.'_bn_'.time().'.'.$f->extension();
            $f->storeAs('banners', $fn, 'public');
            $validated['banner'] = $fn;
        }
        $validated['is_private'] = $request->boolean('is_private');
        $user->update($validated);
        return redirect()->route('profile')->with('success','Профиль обновлён!');
    }

    public function showByLogin($login) {
        $user = User::where('login', $login)->firstOrFail();
        $canView = !$user->is_private || (auth()->check() && auth()->user()->isFollowing($user)) || auth()->id() === $user->id;
        $posts   = $canView ? $user->posts()->latest()->get() : collect();
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;
        return view('profile.show', compact('user','posts','isFollowing','canView'));
    }
}
