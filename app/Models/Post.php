<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $fillable = ['user_id', 'body', 'image', 'video', 'media_type', 'likes_count', 'reposts_count'];
    protected $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class)->latest(); }
    public function likes() { return $this->hasMany(Like::class); }

    public function isLikedBy(User $user): bool {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
