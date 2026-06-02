<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model {
    protected $fillable = ['community_id','user_id','body','image','likes_count','is_pinned'];
    protected $casts = ['is_pinned'=>'boolean','created_at'=>'datetime'];

    public function user()      { return $this->belongsTo(User::class); }
    public function community() { return $this->belongsTo(Community::class); }
    public function isLikedBy(User $u): bool {
        return \DB::table('community_post_likes')->where('community_post_id',$this->id)->where('user_id',$u->id)->exists();
    }
}
