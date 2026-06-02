<?php
namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrackController extends Controller {

    public function index() {
        $tracks   = Track::where('is_public',true)->with('user')->latest()->paginate(40);
        $featured = Track::where('is_public',true)->orderByDesc('plays_count')->limit(5)->with('user')->get();
        $genres   = Track::where('is_public',true)->selectRaw('genre, count(*) as n')->groupBy('genre')->orderByDesc('n')->limit(8)->pluck('genre');
        $mine     = auth()->check() ? Track::where('user_id',auth()->id())->latest()->get() : collect();
        return view('music.index', compact('tracks','featured','genres','mine'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title'    => 'required|string|max:200',
            'artist'   => 'nullable|string|max:200',
            'album'    => 'nullable|string|max:200',
            'genre'    => 'nullable|string|max:60',
            'file'     => 'required|file|mimes:mp3,ogg,wav,flac,aac,m4a|max:512000',
            'cover'    => 'nullable|image|max:102400',
            'duration' => 'nullable|integer|min:0',
            'is_public'=> 'boolean',
        ]);

        $audio = $request->file('file');
        $fn = 'track_'.uniqid().'.'.$audio->extension();
        $audio->storeAs('tracks', $fn, 'public');

        $track = Track::create([
            'user_id'   => auth()->id(),
            'title'     => $data['title'],
            'artist'    => $data['artist'] ?? null,
            'album'     => $data['album'] ?? null,
            'genre'     => $data['genre'] ?? null,
            'file'      => $fn,
            'duration'  => $data['duration'] ?? 0,
            'is_public' => $request->boolean('is_public', true),
        ]);

        if ($request->hasFile('cover')) {
            $cf = $request->file('cover');
            $cfn = 'cover_'.uniqid().'.'.$cf->extension();
            $cf->storeAs('tracks', $cfn, 'public');
            $track->update(['cover'=>$cfn]);
        }

        return back()->with('success','Трек загружен!');
    }

    public function play(Track $track) {
        $track->increment('plays_count');
        return response()->json(['url'=>asset('storage/tracks/'.$track->file),'track'=>$track]);
    }

    public function like(Track $track) {
        $uid = auth()->id();
        $exists = \DB::table('track_likes')->where('track_id',$track->id)->where('user_id',$uid)->exists();
        if ($exists) {
            \DB::table('track_likes')->where('track_id',$track->id)->where('user_id',$uid)->delete();
            $track->decrement('likes_count');
        } else {
            \DB::table('track_likes')->insert(['track_id'=>$track->id,'user_id'=>$uid,'created_at'=>now(),'updated_at'=>now()]);
            $track->increment('likes_count');
            // Уведомление владельцу трека
            if ($track->user_id !== $uid) {
                Notification::create([
                    'user_id'         => $track->user_id,
                    'actor_id'        => $uid,
                    'type'            => 'track_like',
                    'notifiable_id'   => $track->id,
                    'notifiable_type' => Track::class,
                ]);
            }
        }
        return response()->json(['likes_count'=>$track->fresh()->likes_count,'liked'=>!$exists]);
    }

    public function destroy(Track $track) {
        if ($track->user_id !== auth()->id()) abort(403);
        Storage::disk('public')->delete('tracks/'.$track->file);
        if ($track->cover) Storage::disk('public')->delete('tracks/'.$track->cover);
        $track->delete();
        return back()->with('success','Трек удалён.');
    }
}
