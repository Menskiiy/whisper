<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller {
    public function store(Request $request, Post $post) {
        $validated = $request->validate([
            'body'  => 'required|max:560',
            'image' => 'nullable|image|mimes:jpg,png,gif,webp|max:4096',
        ]);
        $comment = auth()->user()->comments()->create(['post_id'=>$post->id,'body'=>$validated['body']]);
        if ($request->hasFile('image')) {
            $f = $request->file('image');
            $fn = $comment->id.'_'.time().'.'.$f->extension();
            $f->storeAs('comments', $fn, 'public');
            $comment->update(['image'=>$fn]);
        }
        if ($post->user_id !== auth()->id()) {
            Notification::create(['user_id'=>$post->user_id,'actor_id'=>auth()->id(),'type'=>'comment','notifiable_id'=>$post->id,'notifiable_type'=>Post::class]);
        }
        $comment->load('user');
        if ($request->ajax()) {
            return response()->json([
                'id'       => $comment->id,
                'body'     => $comment->body,
                'avatar'   => $comment->user->avatar ? asset('storage/avatars/'.$comment->user->avatar) : asset('images/default.png'),
                'name'     => $comment->user->name ?: $comment->user->login,
                'login'    => $comment->user->login,
                'time'     => $comment->created_at->diffForHumans(),
                'mine'     => true,
                'image'    => $comment->image ? asset('storage/comments/'.$comment->image) : null,
                'count'    => $post->comments()->count(),
            ]);
        }
        return back()->with('success','Комментарий добавлен!');
    }

    public function destroy(\App\Models\Comment $comment) {
        if ($comment->user_id !== auth()->id()) abort(403);
        if ($comment->image) Storage::disk('public')->delete('comments/'.$comment->image);
        $comment->delete();
        if (request()->ajax()) return response()->json(['success'=>true]);
        return back();
    }
}
