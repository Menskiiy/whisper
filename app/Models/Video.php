<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Video extends Model {
    protected $fillable = ['user_id','title','description','file','thumbnail','category','duration','views_count','likes_count','is_public'];
    protected $casts = ['is_public'=>'boolean','created_at'=>'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function isLikedBy(User $u): bool {
        return \DB::table('video_likes')->where('video_id',$this->id)->where('user_id',$u->id)->exists();
    }
    public function getDurationFormatted(): string {
        $m = floor($this->duration/60);
        $s = $this->duration % 60;
        return sprintf('%d:%02d', $m, $s);
    }
}
