<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Like;
use App\Models\Notification;

class LikeController extends Controller {
    public function toggle(Post $post) {
        $user = auth()->user();
        $existing = Like::where('user_id',$user->id)->where('post_id',$post->id)->first();
        if ($existing) {
            $existing->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            Like::create(['user_id'=>$user->id,'post_id'=>$post->id]);
            $post->increment('likes_count');
            $liked = true;
            if ($post->user_id !== $user->id) {
                Notification::create(['user_id'=>$post->user_id,'actor_id'=>$user->id,'type'=>'like','notifiable_id'=>$post->id,'notifiable_type'=>Post::class]);
            }
        }
        return response()->json(['likes_count'=>$post->fresh()->likes_count,'liked'=>$liked]);
    }
}
