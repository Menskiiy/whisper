<?php
namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller {

    public function index() {
        $videos   = Video::where('is_public', 1)->with('user')->latest()->paginate(24);
        $trending = Video::where('is_public', 1)->with('user')->orderByDesc('views_count')->limit(6)->get();
        $categories = Video::where('is_public', 1)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->selectRaw('category, COUNT(*) as n')
            ->groupBy('category')
            ->orderByDesc('n')
            ->limit(10)
            ->pluck('category');
        return view('videos.index', compact('videos', 'trending', 'categories'));
    }

    public function show(Video $video) {
        if (!$video->is_public) abort(404);
        $video->increment('views_count');
        $related = Video::where('is_public', 1)
            ->where('id', '!=', $video->id)
            ->when($video->category, fn($q) => $q->where('category', $video->category))
            ->with('user')
            ->limit(8)
            ->get();
        $liked = auth()->check() ? $video->isLikedBy(auth()->user()) : false;
        return view('videos.show', compact('video', 'related', 'liked'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'category'    => 'nullable|string|max:60',
            'file'        => 'required|file|mimes:mp4,mov,avi,webm,mkv|max:10240000',
            'thumbnail'   => 'nullable|image|max:102400',
        ], [
            'file.required' => 'Выберите видео файл.',
            'file.mimes'    => 'Поддерживаются форматы: MP4, MOV, AVI, WebM, MKV.',
            'file.max'      => 'Файл не должен превышать 2 ГБ.',
            'title.required'=> 'Введите название видео.',
        ]);

        $vf = $request->file('file');
        $fn = 'video_'.uniqid().'.'.$vf->extension();
        $vf->storeAs('videos', $fn, 'public');

        $video = Video::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'file'        => $fn,
            'is_public'   => $request->boolean('is_public', true),
        ]);

        if ($request->hasFile('thumbnail')) {
            $tf = $request->file('thumbnail');
            $tfn = 'thumb_'.uniqid().'.'.$tf->extension();
            $tf->storeAs('videos', $tfn, 'public');
            $video->update(['thumbnail' => $tfn]);
        }

        return back()->with('success', 'Видео загружено!');
    }

    public function like(Video $video) {
        $uid = auth()->id();
        $exists = \DB::table('video_likes')
            ->where('video_id', $video->id)->where('user_id', $uid)->exists();
        if ($exists) {
            \DB::table('video_likes')->where('video_id', $video->id)->where('user_id', $uid)->delete();
            $video->decrement('likes_count');
        } else {
            \DB::table('video_likes')->insert([
                'video_id'   => $video->id,
                'user_id'    => $uid,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $video->increment('likes_count');
        }
        return response()->json(['likes_count' => $video->fresh()->likes_count, 'liked' => !$exists]);
    }

    public function destroy(Video $video) {
        if ($video->user_id !== auth()->id()) abort(403);
        Storage::disk('public')->delete('videos/'.$video->file);
        if ($video->thumbnail) Storage::disk('public')->delete('videos/'.$video->thumbnail);
        $video->delete();
        return back()->with('success', 'Видео удалено.');
    }
}
