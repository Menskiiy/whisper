<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\User;

class PhotoController extends Controller {
    public function index() {
        // All posts with images from public profiles
        $photos = Post::whereNotNull('image')
            ->whereHas('user', fn($q) => $q->where('is_private', false))
            ->with('user')
            ->latest()
            ->paginate(60);
        return view('photos.index', compact('photos'));
    }
}
