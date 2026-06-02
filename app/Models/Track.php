<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Track extends Model {
    protected $fillable = ['user_id','title','artist','album','genre','file','cover','duration','plays_count','likes_count','is_public'];
    protected $casts = ['is_public'=>'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function isLikedBy(User $u): bool {
        return \DB::table('track_likes')->where('track_id',$this->id)->where('user_id',$u->id)->exists();
    }
    public function getDurationFormatted(): string {
        $m = floor($this->duration/60);
        $s = $this->duration % 60;
        return sprintf('%d:%02d', $m, $s);
    }
}
