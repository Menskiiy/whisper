<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\User;

class FeedController extends Controller {
    public function __invoke() {
        $followingIds = auth()->user()->following->pluck('id')->push(auth()->id());

        $posts = Post::with(['user', 'comments.user'])
                     ->whereIn('user_id', $followingIds)
                     ->latest()
                     ->get();

        return view('feed', compact('posts'));
    }
}
