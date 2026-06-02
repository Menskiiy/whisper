<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body'  => 'required|max:560',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,bmp|max:102400',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm,mkv,ogv|max:2097152',
        ]);

        $post = auth()->user()->posts()->create([
            'body' => $validated['body'],
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = $post->id . '_' . time() . '.' . $file->extension();
            $file->storeAs('posts', $filename, 'public');
            $post->update(['video' => $filename, 'media_type' => 'video']);
        } elseif ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $post->id . '_' . time() . '.' . $file->extension();
            $file->storeAs('posts', $filename, 'public');
            $mime = $file->getMimeType();
            $type = ($mime === 'image/gif') ? 'gif' : 'image';
            $post->update(['image' => $filename, 'media_type' => $type]);
        }

        return back();
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) abort(403);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) abort(403);

        $request->validate([
            'body'  => 'required|max:560',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,bmp|max:102400',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm,mkv,ogv|max:2097152',
        ]);

        $post->update(['body' => $request->body]);

        if ($request->hasFile('video')) {
            if ($post->video) Storage::disk('public')->delete('posts/' . $post->video);
            if ($post->image) Storage::disk('public')->delete('posts/' . $post->image);
            $file = $request->file('video');
            $filename = $post->id . '_' . time() . '.' . $file->extension();
            $file->storeAs('posts', $filename, 'public');
            $post->update(['video' => $filename, 'image' => null, 'media_type' => 'video']);
        } elseif ($request->hasFile('image')) {
            if ($post->image) Storage::disk('public')->delete('posts/' . $post->image);
            if ($post->video) Storage::disk('public')->delete('posts/' . $post->video);
            $file = $request->file('image');
            $filename = $post->id . '_' . time() . '.' . $file->extension();
            $file->storeAs('posts', $filename, 'public');
            $mime = $file->getMimeType();
            $type = ($mime === 'image/gif') ? 'gif' : 'image';
            $post->update(['image' => $filename, 'video' => null, 'media_type' => $type]);
        }

        return redirect()->route('profile')->with('success', 'Пост обновлён!');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) abort(403);
        if ($post->image) Storage::disk('public')->delete('posts/' . $post->image);
        if ($post->video) Storage::disk('public')->delete('posts/' . $post->video);
        $post->delete();
        return back()->with('success', 'Пост удалён');
    }
}
